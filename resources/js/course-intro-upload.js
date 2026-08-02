/**
 * Chunked + resumable course intro video uploader (Alpine component).
 * Persists session fingerprint in localStorage so interrupted uploads can continue.
 */
export function courseIntroUpload(config) {
    const storageKey = `course-intro-upload:${config.courseId}`;

    return {
        courseId: config.courseId,
        urls: config.urls,
        csrf: config.csrf,
        existing: config.existing || null,

        file: null,
        uploading: false,
        assembling: false,
        paused: false,
        error: null,
        message: null,
        progress: 0,
        uploadedBytes: 0,
        totalBytes: 0,
        uploadId: null,
        receivedChunks: [],
        totalChunks: 0,
        chunkSize: 2 * 1024 * 1024,
        resumable: null,

        init() {
            this.restoreResumeHint();
        },

        restoreResumeHint() {
            try {
                const raw = localStorage.getItem(storageKey);
                if (! raw) return;
                const saved = JSON.parse(raw);
                if (saved?.uploadId && saved?.fingerprint) {
                    this.resumable = saved;
                }
            } catch {
                localStorage.removeItem(storageKey);
            }
        },

        formatBytes(bytes) {
            if (! bytes && bytes !== 0) return '—';
            const units = ['B', 'KB', 'MB', 'GB'];
            let n = Number(bytes);
            let i = 0;
            while (n >= 1024 && i < units.length - 1) {
                n /= 1024;
                i++;
            }
            return `${n.toFixed(i === 0 ? 0 : 1)} ${units[i]}`;
        },

        fingerprintFor(file) {
            return `${file.name}::${file.size}::${file.lastModified}`;
        },

        onFilePicked(event) {
            const file = event.target.files?.[0] || null;
            this.file = file;
            this.error = null;
            this.message = null;
            if (! file) return;

            if (! file.type.startsWith('video/') && ! /\.(mp4|webm|mov|mkv|m4v)$/i.test(file.name)) {
                this.error = 'اختر ملف فيديو صالح.';
                this.file = null;
                return;
            }

            if (file.size > 2 * 1024 * 1024 * 1024) {
                this.error = 'الحد الأقصى لحجم الفيديو 2 جيجابايت.';
                this.file = null;
            }
        },

        async startUpload({ resume = false } = {}) {
            if (this.uploading) return;

            let file = this.file;
            let fingerprint = file ? this.fingerprintFor(file) : null;

            if (resume && this.resumable) {
                if (! file) {
                    this.error = 'اختر نفس ملف الفيديو مرة أخرى لإكمال الرفع.';
                    return;
                }
                if (fingerprint !== this.resumable.fingerprint) {
                    this.error = 'الملف المختار مختلف عن الرفع السابق. اختر نفس الملف أو ابدأ رفعاً جديداً.';
                    return;
                }
            }

            if (! file) {
                this.error = 'اختر ملف فيديو أولاً.';
                return;
            }

            this.uploading = true;
            this.paused = false;
            this.assembling = false;
            this.error = null;
            this.message = resume ? 'جاري استئناف الرفع…' : 'جاري تجهيز الرفع…';
            this.totalBytes = file.size;
            this.progress = 0;

            try {
                const initBody = {
                    original_name: file.name,
                    total_size: file.size,
                    mime: file.type || null,
                    fingerprint,
                };

                const initRes = await this.postJson(this.urls.init, initBody);
                const data = initRes.data;
                this.uploadId = data.upload_id;
                this.chunkSize = data.chunk_size;
                this.totalChunks = data.total_chunks;
                this.receivedChunks = data.received_chunks || [];
                this.uploadedBytes = data.uploaded_bytes || 0;
                this.progress = data.progress || 0;
                this.message = initRes.message || 'بدأ الرفع';

                localStorage.setItem(storageKey, JSON.stringify({
                    uploadId: this.uploadId,
                    fingerprint,
                    originalName: file.name,
                    totalSize: file.size,
                }));
                this.resumable = JSON.parse(localStorage.getItem(storageKey));

                await this.uploadMissingChunks(file);

                if (this.paused) {
                    this.message = 'تم إيقاف الرفع مؤقتاً — يمكنك المتابعة لاحقاً.';
                    return;
                }

                this.assembling = true;
                this.message = 'جاري تجميع الفيديو…';
                const done = await this.postJson(this.urls.complete(this.uploadId), {});
                this.existing = {
                    original_name: done.data.original_name,
                    size: done.data.size,
                    mime: done.data.mime,
                    stream_url: done.data.stream_url,
                };
                localStorage.removeItem(storageKey);
                this.resumable = null;
                this.progress = 100;
                this.message = done.message || 'اكتمل الرفع بنجاح.';
                this.file = null;
                const input = this.$refs.fileInput;
                if (input) input.value = '';
            } catch (e) {
                this.error = e?.message || 'فشل الرفع. يمكنك المحاولة مرة أخرى وسيُستأنف من آخر جزء ناجح.';
            } finally {
                this.uploading = false;
                this.assembling = false;
            }
        },

        pause() {
            this.paused = true;
        },

        async uploadMissingChunks(file) {
            const received = new Set(this.receivedChunks);
            for (let index = 0; index < this.totalChunks; index++) {
                if (this.paused) break;
                if (received.has(index)) continue;

                const start = index * this.chunkSize;
                const end = Math.min(start + this.chunkSize, file.size);
                const blob = file.slice(start, end);

                let attempt = 0;
                while (true) {
                    attempt++;
                    try {
                        const payload = await this.postChunk(this.urls.chunk(this.uploadId), index, blob);
                        this.receivedChunks = payload.data.received_chunks || [];
                        this.uploadedBytes = payload.data.uploaded_bytes || end;
                        this.progress = payload.data.progress || Math.round((end / file.size) * 100);
                        this.message = `جاري الرفع… ${this.progress}%`;
                        break;
                    } catch (err) {
                        if (attempt >= 5) throw err;
                        await this.sleep(Math.min(1000 * attempt, 5000));
                    }
                }
            }
        },

        async deleteVideo() {
            if (! confirm('حذف الفيديو التوضيحي؟')) return;
            try {
                await this.request(this.urls.destroy, { method: 'DELETE' });
                this.existing = null;
                this.message = 'تم حذف الفيديو.';
            } catch (e) {
                this.error = e?.message || 'تعذر حذف الفيديو.';
            }
        },

        clearResume() {
            localStorage.removeItem(storageKey);
            this.resumable = null;
            this.message = 'تم إلغاء جلسة الاستئناف.';
        },

        async postJson(url, body) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': this.csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify(body),
            });
            const json = await res.json().catch(() => ({}));
            if (! res.ok) {
                throw new Error(json.message || Object.values(json.errors || {})[0]?.[0] || `خطأ ${res.status}`);
            }
            return json;
        },

        async postChunk(url, index, blob) {
            const form = new FormData();
            form.append('index', String(index));
            form.append('chunk', blob, `chunk-${index}`);

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': this.csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: form,
            });
            const json = await res.json().catch(() => ({}));
            if (! res.ok) {
                throw new Error(json.message || Object.values(json.errors || {})[0]?.[0] || `فشل رفع الجزء ${index}`);
            }
            return json;
        },

        async request(url, options = {}) {
            const res = await fetch(url, {
                ...options,
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': this.csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(options.headers || {}),
                },
                credentials: 'same-origin',
            });
            const json = await res.json().catch(() => ({}));
            if (! res.ok) {
                throw new Error(json.message || `خطأ ${res.status}`);
            }
            return json;
        },

        sleep(ms) {
            return new Promise((resolve) => setTimeout(resolve, ms));
        },
    };
}
