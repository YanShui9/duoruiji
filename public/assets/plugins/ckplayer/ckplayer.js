/**
 * ckplayer 兼容层 - 基于 HTML5 Video
 * 提供与 ckplayer 相同的 API 接口
 */
(function(window) {
    'use strict';

    var ckplayer = function(config) {
        this.config = Object.assign({
            container: '#video-player',
            variable: 'player',
            autoplay: false,
            video: '',
            poster: '',
            width: '100%',
            height: '100%'
        }, config || {});

        this.container = null;
        this.video = null;
        this.controls = null;
        this.isPlaying = false;
        this.isMuted = false;
        this.isFullscreen = false;
        this.duration = 0;
        this.currentTime = 0;

        this._init();
    };

    ckplayer.prototype._init = function() {
        var containerEl = document.querySelector(this.config.container);
        if (!containerEl) {
            console.error('ckplayer: Container not found:', this.config.container);
            return;
        }
        this.container = containerEl;
        this.container.style.position = 'relative';
        this.container.style.overflow = 'hidden';
        this.container.style.background = '#000';
        this.container.style.cursor = 'pointer';

        // 创建视频元素
        this.video = document.createElement('video');
        this.video.style.width = '100%';
        this.video.style.height = '100%';
        this.video.style.objectFit = 'contain';
        this.video.preload = 'metadata';
        this.video.playsInline = true;
        this.video.webkitPlaysInline = true;
        this.video.setAttribute('x5-video-player-type', 'h5');
        this.video.setAttribute('x5-video-player-fullscreen', 'false');
        this.video.setAttribute('x5-video-orientation', 'portraint');

        // 微信环境下禁用自动播放
        var isWechat = /MicroMessenger/i.test(navigator.userAgent);
        if (this.config.autoplay && !isWechat) {
            this.video.autoplay = true;
        }
        if (this.config.poster) {
            this.video.poster = this.config.poster;
        }
        if (this.config.video) {
            this.video.src = this.config.video;
        }

        this.container.appendChild(this.video);

        // 创建自定义控制栏
        this._createControls();

        // 绑定事件
        this._bindEvents();

        // 设置全局变量
        if (this.config.variable) {
            window[this.config.variable] = this;
        }
    };

    ckplayer.prototype._createControls = function() {
        var self = this;

        // 控制栏容器
        this.controls = document.createElement('div');
        this.controls.className = 'ckplayer-controls';
        this.controls.style.cssText = 'position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,0.85));padding:20px 15px 12px;transition:opacity 0.3s;z-index:10;';

        // 进度条
        this.progressContainer = document.createElement('div');
        this.progressContainer.style.cssText = 'width:100%;height:4px;background:rgba(255,255,255,0.3);border-radius:2px;margin-bottom:10px;cursor:pointer;position:relative;transition:height 0.2s;';
        this.progressContainer.addEventListener('mouseenter', function() {
            self.progressContainer.style.height = '6px';
        });
        this.progressContainer.addEventListener('mouseleave', function() {
            self.progressContainer.style.height = '4px';
        });

        this.progressPlayed = document.createElement('div');
        this.progressPlayed.style.cssText = 'height:100%;background:#c4956a;border-radius:2px;width:0%;position:relative;transition:width 0.1s;';

        this.progressBuffer = document.createElement('div');
        this.progressBuffer.style.cssText = 'position:absolute;top:0;left:0;height:100%;background:rgba(255,255,255,0.2);border-radius:2px;width:0%;';

        this.progressThumb = document.createElement('div');
        this.progressThumb.style.cssText = 'position:absolute;right:-6px;top:50%;transform:translateY(-50%);width:12px;height:12px;background:#fff;border-radius:50%;box-shadow:0 0 4px rgba(0,0,0,0.5);opacity:0;transition:opacity 0.2s;';

        this.progressPlayed.appendChild(this.progressThumb);
        this.progressContainer.appendChild(this.progressBuffer);
        this.progressContainer.appendChild(this.progressPlayed);

        // 进度条点击
        this.progressContainer.addEventListener('click', function(e) {
            var rect = self.progressContainer.getBoundingClientRect();
            var pos = (e.clientX - rect.left) / rect.width;
            self.video.currentTime = pos * self.duration;
        });

        // 控制按钮行
        var controlsRow = document.createElement('div');
        controlsRow.style.cssText = 'display:flex;align-items:center;gap:12px;color:#fff;';

        // 播放按钮
        this.btnPlay = this._createButton('▶', function() {
            self.togglePlay();
        });
        this.btnPlay.style.fontSize = '18px';

        // 时间显示
        this.timeDisplay = document.createElement('span');
        this.timeDisplay.style.cssText = 'font-size:13px;color:rgba(255,255,255,0.9);font-variant-numeric:tabular-nums;min-width:100px;';
        this.timeDisplay.textContent = '00:00 / 00:00';

        // 弹性间隔
        var spacer = document.createElement('div');
        spacer.style.flex = '1';

        // 音量按钮
        this.btnMute = this._createButton('🔊', function() {
            self.toggleMute();
        });
        this.btnMute.style.fontSize = '16px';

        // 音量滑块
        this.volumeSlider = document.createElement('input');
        this.volumeSlider.type = 'range';
        this.volumeSlider.min = '0';
        this.volumeSlider.max = '1';
        this.volumeSlider.step = '0.05';
        this.volumeSlider.value = '1';
        this.volumeSlider.style.cssText = 'width:70px;height:4px;accent-color:#c4956a;cursor:pointer;';
        this.volumeSlider.addEventListener('input', function() {
            self.video.volume = parseFloat(this.value);
            self.video.muted = false;
            self.isMuted = false;
            self.btnMute.textContent = parseFloat(this.value) > 0 ? '🔊' : '🔇';
        });

        // 全屏按钮
        this.btnFullscreen = this._createButton('⛶', function() {
            self.toggleFullscreen();
        });
        this.btnFullscreen.style.fontSize = '18px';

        controlsRow.appendChild(this.btnPlay);
        controlsRow.appendChild(this.timeDisplay);
        controlsRow.appendChild(spacer);
        controlsRow.appendChild(this.btnMute);
        controlsRow.appendChild(this.volumeSlider);
        controlsRow.appendChild(this.btnFullscreen);

        this.controls.appendChild(this.progressContainer);
        this.controls.appendChild(controlsRow);
        this.container.appendChild(this.controls);

        // 中央播放大按钮
        this.bigPlayBtn = document.createElement('div');
        this.bigPlayBtn.style.cssText = 'position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:70px;height:70px;background:rgba(255,255,255,0.95);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 8px 30px rgba(0,0,0,0.3);transition:transform 0.3s,opacity 0.3s;z-index:5;';
        this.bigPlayBtn.innerHTML = '<span style="font-size:28px;color:#1a3a2a;margin-left:4px;">▶</span>';
        this.bigPlayBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            self.togglePlay();
        });
        this.container.appendChild(this.bigPlayBtn);

        // 加载指示器
        this.loadingIndicator = document.createElement('div');
        this.loadingIndicator.style.cssText = 'position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:4;display:none;';
        this.loadingIndicator.innerHTML = '<div style="width:40px;height:40px;border:3px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:ckplayer-spin 0.8s linear infinite;"></div>';
        this.container.appendChild(this.loadingIndicator);

        // 添加动画样式
        if (!document.getElementById('ckplayer-styles')) {
            var style = document.createElement('style');
            style.id = 'ckplayer-styles';
            style.textContent = '@keyframes ckplayer-spin{to{transform:translate(-50%,-50%) rotate(360deg)}}';
            document.head.appendChild(style);
        }
    };

    ckplayer.prototype._createButton = function(text, onClick) {
        var btn = document.createElement('button');
        btn.textContent = text;
        btn.style.cssText = 'background:none;border:none;color:#fff;cursor:pointer;padding:4px 8px;line-height:1;opacity:0.9;transition:opacity 0.2s;';
        btn.addEventListener('mouseenter', function() { btn.style.opacity = '1'; });
        btn.addEventListener('mouseleave', function() { btn.style.opacity = '0.9'; });
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            onClick();
        });
        return btn;
    };

    ckplayer.prototype._bindEvents = function() {
        var self = this;
        var hideTimeout;

        // 视频事件
        this.video.addEventListener('loadedmetadata', function() {
            self.duration = self.video.duration;
            self._updateTimeDisplay();
        });

        this.video.addEventListener('timeupdate', function() {
            self.currentTime = self.video.currentTime;
            if (self.duration > 0) {
                var pct = (self.currentTime / self.duration) * 100;
                self.progressPlayed.style.width = pct + '%';
            }
            self._updateTimeDisplay();
        });

        this.video.addEventListener('progress', function() {
            if (self.video.buffered.length > 0 && self.duration > 0) {
                var buffered = self.video.buffered.end(self.video.buffered.length - 1);
                self.progressBuffer.style.width = (buffered / self.duration * 100) + '%';
            }
        });

        this.video.addEventListener('play', function() {
            self.isPlaying = true;
            self.btnPlay.textContent = '⏸';
            self.bigPlayBtn.style.opacity = '0';
            self.bigPlayBtn.style.pointerEvents = 'none';
        });

        this.video.addEventListener('pause', function() {
            self.isPlaying = false;
            self.btnPlay.textContent = '▶';
            self.bigPlayBtn.style.opacity = '1';
            self.bigPlayBtn.style.pointerEvents = 'auto';
        });

        this.video.addEventListener('ended', function() {
            self.isPlaying = false;
            self.btnPlay.textContent = '▶';
            self.bigPlayBtn.style.opacity = '1';
            self.bigPlayBtn.style.pointerEvents = 'auto';
        });

        this.video.addEventListener('waiting', function() {
            self.loadingIndicator.style.display = 'block';
        });

        this.video.addEventListener('canplay', function() {
            self.loadingIndicator.style.display = 'none';
        });

        // 容器点击播放/暂停
        this.container.addEventListener('click', function(e) {
            if (e.target === self.video || e.target === self.container) {
                self.togglePlay();
            }
        });

        // 双击全屏
        this.container.addEventListener('dblclick', function(e) {
            if (e.target === self.video) {
                self.toggleFullscreen();
            }
        });

        // 鼠标移动显示控制栏
        this.container.addEventListener('mousemove', function() {
            self.controls.style.opacity = '1';
            self.container.style.cursor = 'default';
            clearTimeout(hideTimeout);
            hideTimeout = setTimeout(function() {
                if (self.isPlaying) {
                    self.controls.style.opacity = '0';
                    self.container.style.cursor = 'none';
                }
            }, 3000);
        });

        this.container.addEventListener('mouseleave', function() {
            if (self.isPlaying) {
                self.controls.style.opacity = '0';
            }
        });

        // 键盘控制
        document.addEventListener('keydown', function(e) {
            if (!self.container.matches(':hover')) return;
            switch(e.key) {
                case ' ':
                case 'k':
                    e.preventDefault();
                    self.togglePlay();
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    self.video.currentTime = Math.max(0, self.video.currentTime - 5);
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    self.video.currentTime = Math.min(self.duration, self.video.currentTime + 5);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    self.video.volume = Math.min(1, self.video.volume + 0.1);
                    self.volumeSlider.value = self.video.volume;
                    break;
                case 'ArrowDown':
                    e.preventDefault();
                    self.video.volume = Math.max(0, self.video.volume - 0.1);
                    self.volumeSlider.value = self.video.volume;
                    break;
                case 'f':
                    e.preventDefault();
                    self.toggleFullscreen();
                    break;
                case 'm':
                    e.preventDefault();
                    self.toggleMute();
                    break;
            }
        });

        // 全屏变化监听
        document.addEventListener('fullscreenchange', function() {
            self.isFullscreen = !!document.fullscreenElement;
            self.btnFullscreen.textContent = self.isFullscreen ? '⛶' : '⛶';
        });
    };

    ckplayer.prototype._updateTimeDisplay = function() {
        this.timeDisplay.textContent = this._formatTime(this.currentTime) + ' / ' + this._formatTime(this.duration);
    };

    ckplayer.prototype._formatTime = function(seconds) {
        if (isNaN(seconds)) return '00:00';
        var m = Math.floor(seconds / 60);
        var s = Math.floor(seconds % 60);
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    };

    // 公开 API 方法
    ckplayer.prototype.togglePlay = function() {
        if (this.video.paused) {
            this.video.play();
        } else {
            this.video.pause();
        }
    };

    ckplayer.prototype.play = function() {
        this.video.play();
    };

    ckplayer.prototype.pause = function() {
        this.video.pause();
    };

    ckplayer.prototype.toggleMute = function() {
        this.video.muted = !this.video.muted;
        this.isMuted = this.video.muted;
        this.btnMute.textContent = this.isMuted ? '🔇' : '🔊';
    };

    ckplayer.prototype.toggleFullscreen = function() {
        if (!document.fullscreenElement) {
            if (this.container.requestFullscreen) {
                this.container.requestFullscreen();
            } else if (this.container.webkitRequestFullscreen) {
                this.container.webkitRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    };

    ckplayer.prototype.seek = function(time) {
        this.video.currentTime = time;
    };

    ckplayer.prototype.setVolume = function(vol) {
        this.video.volume = vol;
        this.volumeSlider.value = vol;
    };

    ckplayer.prototype.changeVideo = function(url) {
        this.video.src = url;
        this.video.load();
    };

    ckplayer.prototype.getPosition = function() {
        return this.video.currentTime;
    };

    ckplayer.prototype.getDuration = function() {
        return this.video.duration;
    };

    ckplayer.prototype.getState = function() {
        if (this.video.paused) return 0; // 暂停
        return 1; // 播放中
    };

    // 导出
    window.ckplayer = ckplayer;

})(window);
