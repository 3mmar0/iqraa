/**
 * Generic media uploader with local preview + XHR progress.
 */
export function mediaUploader(config = {}) {
    return {
        csrf: config.csrf,
        uploadUrl: config.uploadUrl || null,
        getUploadUrl: config.getUploadUrl || null,
        lessonBaseUrl: config.lessonBaseUrl || null,
        lessonId: config.lessonId || null,
        defaultType: config.defaultType || '',
        showTypeSelect: config.showTypeSelect !== false,
        reloadOnSuccess: config.reloadOnSuccess !== false,
        onUploaded: config.onUploaded || null,

        file: null,
        previewUrl: null,
        previewKind: null,
        type: config.defaultType || '',
        uploading: false,
        progress: 0,
        error: null,
        message: null,
        xhr: null,
        dragging: false,

        resolveUrl() {
            if (typeof this.getUploadUrl === 'function') {
                return this.getUploadUrl();
            }
            if (this.lessonBaseUrl && this.lessonId) {
                return `${this.lessonBaseUrl}/${this.lessonId}/media`;
            }
            return this.uploadUrl;
        },

        onPick(event) {
            this.assignFile(event.target.files?.[0] || null);
        },

        onDrop(event) {
            this.dragging = false;
            if (this.uploading) return;
            const file = event.dataTransfer?.files?.[0] || null;
            this.assignFile(file);
            if (file && this.$refs?.fileInput) {
                try {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    this.$refs.fileInput.files = dt.files;
                } catch {
                    // Some browsers block programmatic FileList assignment; file state is enough.
                }
            }
        },

        assignFile(file) {
            this.clearPreview();
            this.file = file;
            this.error = null;
            this.message = null;
            this.progress = 0;

            if (! file) return;

            const mime = file.type || '';
            if (mime.startsWith('video/') || /\.(mp4|webm|mov|mkv|m4v)$/i.test(file.name)) {
                this.previewKind = 'video';
                this.previewUrl = URL.createObjectURL(file);
                if (! this.type) this.type = 'video';
            } else if (mime.startsWith('image/')) {
                this.previewKind = 'image';
                this.previewUrl = URL.createObjectURL(file);
                if (! this.type) this.type = 'image';
            } else if (mime === 'application/pdf' || /\.pdf$/i.test(file.name)) {
                this.previewKind = 'pdf';
                this.previewUrl = URL.createObjectURL(file);
                if (! this.type) this.type = 'pdf';
            } else {
                this.previewKind = 'file';
                this.previewUrl = null;
            }
        },

        clearFile() {
            if (this.uploading) return;
            this.resetInput();
            this.error = null;
            this.message = null;
        },

        clearPreview() {
            if (this.previewUrl) {
                URL.revokeObjectURL(this.previewUrl);
            }
            this.previewUrl = null;
            this.previewKind = null;
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

        cancel() {
            if (this.xhr) {
                this.xhr.abort();
                this.xhr = null;
            }
            this.uploading = false;
            this.message = 'تم إلغاء الرفع.';
        },

        startUpload() {
            if (this.uploading) return;
            if (! this.file) {
                this.error = 'اختر ملفاً أولاً.';
                return;
            }

            const url = this.resolveUrl();
            if (! url) {
                this.error = 'تعذر تحديد وجهة الرفع.';
                return;
            }

            this.uploading = true;
            this.error = null;
            this.message = 'جاري الرفع…';
            this.progress = 0;

            const form = new FormData();
            form.append('file', this.file, this.file.name);
            if (this.type) {
                form.append('type', this.type);
            }

            const xhr = new XMLHttpRequest();
            this.xhr = xhr;
            xhr.open('POST', url);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('X-CSRF-TOKEN', this.csrf);

            xhr.upload.onprogress = (event) => {
                if (event.lengthComputable) {
                    this.progress = Math.min(99, Math.round((event.loaded / event.total) * 100));
                    this.message = `جاري الرفع… ${this.progress}%`;
                }
            };

            xhr.onload = () => {
                this.uploading = false;
                this.xhr = null;
                let json = {};
                try {
                    json = JSON.parse(xhr.responseText || '{}');
                } catch {
                    json = {};
                }

                if (xhr.status >= 200 && xhr.status < 300) {
                    this.progress = 100;
                    this.message = json.message || 'تم الرفع بنجاح.';
                    if (typeof this.onUploaded === 'function' && json.data) {
                        this.onUploaded(json.data);
                    }
                    if (this.reloadOnSuccess) {
                        setTimeout(() => window.location.reload(), 500);
                    } else {
                        this.resetInput();
                    }
                    return;
                }

                this.progress = 0;
                this.error = json.message
                    || Object.values(json.errors || {})[0]?.[0]
                    || `فشل الرفع (${xhr.status})`;
                this.message = null;
            };

            xhr.onerror = () => {
                this.uploading = false;
                this.xhr = null;
                this.progress = 0;
                this.error = 'انقطع الاتصال أثناء الرفع. حاول مرة أخرى.';
                this.message = null;
            };

            xhr.onabort = () => {
                this.uploading = false;
                this.xhr = null;
                this.progress = 0;
            };

            xhr.send(form);
        },

        resetInput() {
            this.file = null;
            this.clearPreview();
            this.progress = 0;
            this.type = this.defaultType || '';
            const input = this.$refs?.fileInput;
            if (input) input.value = '';
        },
    };
}
