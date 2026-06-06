/**
 * AJAX 文件上传组件
 * 替代 Ajaxfileupload，提供现代的异步上传体验
 */
(function(window) {
    'use strict';

    var AjaxUpload = function(options) {
        this.config = Object.assign({
            inputSelector: 'input[type="file"]',
            previewSelector: null,
            uploadUrl: null,
            fieldName: 'file',
            maxFileSize: 2 * 1024 * 1024, // 2MB
            allowedTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            onSuccess: null,
            onError: null,
            onProgress: null,
            autoUpload: false,
            showPreview: true,
            previewWidth: 200,
            previewHeight: 200
        }, options || {});

        this.init();
    };

    AjaxUpload.prototype.init = function() {
        var self = this;
        var inputs = document.querySelectorAll(this.config.inputSelector);

        inputs.forEach(function(input) {
            self._setupInput(input);
        });
    };

    AjaxUpload.prototype._setupInput = function(input) {
        var self = this;

        // 创建包装容器
        var wrapper = document.createElement('div');
        wrapper.className = 'ajax-upload-wrapper';
        wrapper.style.cssText = 'position:relative;display:inline-block;width:100%;';

        // 创建拖放区域
        var dropZone = document.createElement('div');
        dropZone.className = 'ajax-upload-dropzone';
        dropZone.style.cssText = 'border:2px dashed var(--color-border, #e8e5e0);border-radius:12px;padding:2rem;text-align:center;cursor:pointer;transition:all 0.3s ease;background:var(--color-bg-warm, #f8f5f0);';
        dropZone.innerHTML = '<div style="margin-bottom:1rem;"><i class="bi bi-cloud-arrow-up" style="font-size:2.5rem;color:var(--color-sage, #8fa89a);"></i></div>' +
                            '<p style="margin:0;color:var(--color-text-secondary, #666);font-size:0.95rem;">点击或拖放文件到此处上传</p>' +
                            '<small style="color:var(--color-text-light, #999);">支持 JPG、PNG、GIF，最大 2MB</small>';

        // 创建预览区域
        var previewContainer = document.createElement('div');
        previewContainer.className = 'ajax-upload-preview';
        previewContainer.style.cssText = 'display:none;margin-top:1rem;text-align:center;';

        // 创建进度条
        var progressBar = document.createElement('div');
        progressBar.className = 'ajax-upload-progress';
        progressBar.style.cssText = 'display:none;margin-top:1rem;';
        progressBar.innerHTML = '<div style="background:var(--color-bg-warm, #f8f5f0);border-radius:999px;height:8px;overflow:hidden;">' +
                               '<div class="progress-bar" style="height:100%;background:var(--color-sage, #8fa89a);border-radius:999px;width:0%;transition:width 0.3s ease;"></div>' +
                               '</div>' +
                               '<p class="progress-text" style="margin:0.5rem 0 0;font-size:0.85rem;color:var(--color-text-secondary, #666);">上传中...</p>';

        // 创建状态消息
        var statusMessage = document.createElement('div');
        statusMessage.className = 'ajax-upload-status';
        statusMessage.style.cssText = 'display:none;margin-top:0.75rem;padding:0.75rem;border-radius:8px;font-size:0.9rem;';

        // 隐藏原始input
        input.style.display = 'none';

        // 组装DOM
        wrapper.appendChild(dropZone);
        wrapper.appendChild(previewContainer);
        wrapper.appendChild(progressBar);
        wrapper.appendChild(statusMessage);
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        // 绑定事件
        this._bindEvents(input, dropZone, previewContainer, progressBar, statusMessage);
    };

    AjaxUpload.prototype._bindEvents = function(input, dropZone, previewContainer, progressBar, statusMessage) {
        var self = this;

        // 点击上传
        dropZone.addEventListener('click', function() {
            input.click();
        });

        // 文件选择
        input.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                self._handleFile(e.target.files[0], input, dropZone, previewContainer, progressBar, statusMessage);
            }
        });

        // 拖放事件
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = 'var(--color-sage, #8fa89a)';
            dropZone.style.background = 'rgba(143, 168, 154, 0.1)';
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = 'var(--color-border, #e8e5e0)';
            dropZone.style.background = 'var(--color-bg-warm, #f8f5f0)';
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = 'var(--color-border, #e8e5e0)';
            dropZone.style.background = 'var(--color-bg-warm, #f8f5f0)';

            if (e.dataTransfer.files.length > 0) {
                input.files = e.dataTransfer.files;
                self._handleFile(e.dataTransfer.files[0], input, dropZone, previewContainer, progressBar, statusMessage);
            }
        });
    };

    AjaxUpload.prototype._handleFile = function(file, input, dropZone, previewContainer, progressBar, statusMessage) {
        var self = this;

        // 验证文件类型
        if (this.config.allowedTypes.length > 0 && !this.config.allowedTypes.includes(file.type)) {
            this._showStatus(statusMessage, 'error', '不支持的文件格式');
            return;
        }

        // 验证文件大小
        if (file.size > this.config.maxFileSize) {
            this._showStatus(statusMessage, 'error', '文件大小超过限制（最大 ' + Math.round(this.config.maxFileSize / 1024 / 1024) + 'MB）');
            return;
        }

        // 显示预览
        if (this.config.showPreview && file.type.startsWith('image/')) {
            this._showPreview(file, previewContainer);
        }

        // 隐藏状态消息
        statusMessage.style.display = 'none';

        // 如果自动上传
        if (this.config.autoUpload && this.config.uploadUrl) {
            this.upload(file, input, progressBar, statusMessage);
        }
    };

    AjaxUpload.prototype._showPreview = function(file, previewContainer) {
        var reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.style.display = 'block';
            previewContainer.innerHTML = '<img src="' + e.target.result + '" style="max-width:' + this.config.previewWidth + 'px;max-height:' + this.config.previewHeight + 'px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);">';
        }.bind(this);
        reader.readAsDataURL(file);
    };

    AjaxUpload.prototype._showStatus = function(statusMessage, type, message) {
        var colors = {
            success: { bg: 'rgba(45, 90, 61, 0.1)', color: '#2d5a3d', border: '1px solid rgba(45, 90, 61, 0.2)' },
            error: { bg: 'rgba(192, 57, 43, 0.1)', color: '#c0392b', border: '1px solid rgba(192, 57, 43, 0.2)' },
            info: { bg: 'rgba(143, 168, 154, 0.1)', color: '#1a3a2a', border: '1px solid rgba(143, 168, 154, 0.2)' }
        };

        var style = colors[type] || colors.info;
        statusMessage.style.display = 'block';
        statusMessage.style.background = style.bg;
        statusMessage.style.color = style.color;
        statusMessage.style.border = style.border;
        statusMessage.textContent = message;
    };

    AjaxUpload.prototype.upload = function(file, input, progressBar, statusMessage) {
        var self = this;
        var formData = new FormData();
        formData.append(this.config.fieldName, file);

        // 显示进度条
        progressBar.style.display = 'block';
        var progressBarInner = progressBar.querySelector('.progress-bar');
        var progressText = progressBar.querySelector('.progress-text');

        var xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                var percent = Math.round((e.loaded / e.total) * 100);
                progressBarInner.style.width = percent + '%';
                progressText.textContent = '上传中... ' + percent + '%';

                if (self.config.onProgress) {
                    self.config.onProgress(percent, e.loaded, e.total);
                }
            }
        });

        xhr.addEventListener('load', function() {
            progressBar.style.display = 'none';

            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    self._showStatus(statusMessage, 'success', '上传成功');
                    if (self.config.onSuccess) {
                        self.config.onSuccess(response, file);
                    }
                } catch (e) {
                    self._showStatus(statusMessage, 'success', '上传成功');
                    if (self.config.onSuccess) {
                        self.config.onSuccess(xhr.responseText, file);
                    }
                }
            } else {
                self._showStatus(statusMessage, 'error', '上传失败：' + xhr.statusText);
                if (self.config.onError) {
                    self.config.onError(xhr.statusText, xhr);
                }
            }
        });

        xhr.addEventListener('error', function() {
            progressBar.style.display = 'none';
            self._showStatus(statusMessage, 'error', '网络错误，请重试');
            if (self.config.onError) {
                self.config.onError('Network error', xhr);
            }
        });

        xhr.open('POST', this.config.uploadUrl, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        xhr.send(formData);
    };

    // 导出
    window.AjaxUpload = AjaxUpload;

    // 自动初始化
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('[data-ajax-upload]')) {
            new AjaxUpload();
        }
    });

})(window);
