/**
 * In-lesson video player: stream playback + progress reporting, no download UI.
 */
export function lessonPlayer(config = {}) {
    return {
        progressUrl: config.progressUrl,
        csrf: config.csrf,
        startAt: config.startAt || 0,
        videoComplete: !!config.alreadyComplete,
        src: config.src,
        title: config.title || 'فيديو الدرس',
        playing: false,
        muted: false,
        duration: 0,
        current: 0,
        buffered: 0,
        showControls: true,
        hideTimer: null,
        lastSent: 0,
        seeking: false,
        error: null,

        init() {
            const p = this.$refs.player;
            if (!p) return;
            p.addEventListener('play', () => { this.playing = true; this.bumpControls(); });
            p.addEventListener('pause', () => { this.playing = false; this.showControls = true; });
            p.addEventListener('volumechange', () => { this.muted = p.muted || p.volume === 0; });
        },

        onLoaded() {
            const p = this.$refs.player;
            if (!p) return;
            this.duration = p.duration || 0;
            if (this.startAt > 0 && this.startAt < this.duration) {
                p.currentTime = this.startAt;
                this.current = this.startAt;
            }
        },

        onTimeUpdate() {
            const p = this.$refs.player;
            if (!p || !p.duration || this.seeking) return;
            this.current = p.currentTime;
            this.duration = p.duration;
            if (p.buffered?.length) {
                this.buffered = p.buffered.end(p.buffered.length - 1);
            }
            const t = Math.floor(p.currentTime);
            if (t - this.lastSent < 5) return;
            this.lastSent = t;
            this.sendProgress(t, (p.currentTime / p.duration) >= 0.9);
        },

        onEnded() {
            this.playing = false;
            this.showControls = true;
            this.sendProgress(Math.floor(this.$refs.player?.currentTime || 0), true);
        },

        onError() {
            this.error = 'تعذر تشغيل الفيديو. حاول مرة أخرى أو تواصل مع الدعم.';
        },

        togglePlay() {
            const p = this.$refs.player;
            if (!p) return;
            if (p.paused) {
                p.play().catch(() => { this.error = 'تعذر بدء التشغيل.'; });
            } else {
                p.pause();
            }
            this.bumpControls();
        },

        toggleMute() {
            const p = this.$refs.player;
            if (!p) return;
            p.muted = !p.muted;
            this.muted = p.muted;
            this.bumpControls();
        },

        seekTo(event) {
            const p = this.$refs.player;
            const bar = event.currentTarget;
            if (!p || !p.duration || !bar) return;
            const rect = bar.getBoundingClientRect();
            // RTL: measure from the right edge
            const ratio = Math.min(1, Math.max(0, (rect.right - event.clientX) / rect.width));
            p.currentTime = ratio * p.duration;
            this.current = p.currentTime;
            this.bumpControls();
        },

        startSeek() {
            this.seeking = true;
        },

        endSeek(event) {
            this.seeking = false;
            this.seekTo(event);
            this.sendProgress(Math.floor(this.current), this.duration > 0 && (this.current / this.duration) >= 0.9);
        },

        toggleFullscreen() {
            const shell = this.$refs.shell;
            if (!shell) return;
            if (document.fullscreenElement) {
                document.exitFullscreen?.();
            } else {
                shell.requestFullscreen?.();
            }
            this.bumpControls();
        },

        bumpControls() {
            this.showControls = true;
            clearTimeout(this.hideTimer);
            this.hideTimer = setTimeout(() => {
                if (this.playing) this.showControls = false;
            }, 2800);
        },

        markComplete() {
            this.sendProgress(Math.floor(this.$refs.player?.currentTime || 0), true);
        },

        sendProgress(position, completed) {
            if (completed) this.videoComplete = true;
            if (!this.progressUrl) return;
            fetch(this.progressUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': this.csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ position_seconds: position, completed: !!completed }),
            })
                .then((r) => r.json())
                .then((data) => {
                    if (data.video_completed) this.videoComplete = true;
                    if (data.exam_unlocked) window.location.reload();
                })
                .catch(() => {});
        },

        formatTime(seconds) {
            const s = Math.max(0, Math.floor(seconds || 0));
            const m = Math.floor(s / 60);
            const r = s % 60;
            return `${m}:${r.toString().padStart(2, '0')}`;
        },

        get progressPct() {
            if (!this.duration) return 0;
            return (this.current / this.duration) * 100;
        },

        get bufferedPct() {
            if (!this.duration) return 0;
            return (this.buffered / this.duration) * 100;
        },
    };
}
