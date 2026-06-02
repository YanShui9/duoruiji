/**
 * 直播倒计时脚本
 * 用于首页和讲座详情页的直播倒计时显示
 */
class LiveCountdown {
    constructor(targetTime, containerId) {
        this.targetTime = new Date(targetTime).getTime();
        this.container = document.getElementById(containerId);
        this.daysEl = this.container.querySelector('#days');
        this.hoursEl = this.container.querySelector('#hours');
        this.minutesEl = this.container.querySelector('#minutes');
        this.secondsEl = this.container.querySelector('#seconds');

        this.update();
        this.timer = setInterval(() => this.update(), 1000);
    }

    update() {
        const now = new Date().getTime();
        const diff = this.targetTime - now;

        if (diff <= 0) {
            clearInterval(this.timer);
            this.onComplete();
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        if (this.daysEl) this.daysEl.textContent = String(days).padStart(2, '0');
        if (this.hoursEl) this.hoursEl.textContent = String(hours).padStart(2, '0');
        if (this.minutesEl) this.minutesEl.textContent = String(minutes).padStart(2, '0');
        if (this.secondsEl) this.secondsEl.textContent = String(seconds).padStart(2, '0');
    }

    onComplete() {
        // 倒计时结束，刷新页面
        location.reload();
    }

    destroy() {
        clearInterval(this.timer);
    }
}
