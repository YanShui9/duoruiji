<!-- 引入 flatpickr 时间选择器 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/zh.js"></script>

<div class="row">
    <div class="col-md-8">
        <div class="row g-3">
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">
                        讲座标题 <span style="color: var(--color-danger);">*</span>
                    </label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $lecture->title ?? '') }}"
                           placeholder="请输入讲座标题"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">
                        讲座简介
                    </label>
                    <textarea name="description" class="form-control rich-editor @error('description') is-invalid @enderror"
                              rows="5"
                              placeholder="请输入讲座简介...">{{ old('description', $lecture->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">
                        讲座分类
                    </label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror">
                        <option value="">请选择分类</option>
                        @foreach(\App\Models\Lecture::getCategories() as $key => $name)
                            <option value="{{ $key }}" {{ old('category', $lecture->category ?? '') == $key ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">
                        直播链接
                    </label>
                    <input type="url" name="live_url" class="form-control @error('live_url') is-invalid @enderror"
                           value="{{ old('live_url', $lecture->live_url ?? '') }}"
                           placeholder="https://...">
                    @error('live_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">
                        直播开始时间
                    </label>
                    <input type="text" name="live_start_time" id="live_start_time"
                           class="form-control @error('live_start_time') is-invalid @enderror"
                           value="{{ old('live_start_time', isset($lecture) && $lecture->live_start_time ? $lecture->live_start_time->format('Y-m-d H:i') : '') }}"
                           placeholder="点击选择开始时间"
                           readonly
                           style="cursor: pointer; background: #fff;">
                    @error('live_start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">
                        直播结束时间
                    </label>
                    <input type="text" name="live_end_time" id="live_end_time"
                           class="form-control @error('live_end_time') is-invalid @enderror"
                           value="{{ old('live_end_time', isset($lecture) && $lecture->live_end_time ? $lecture->live_end_time->format('Y-m-d H:i') : '') }}"
                           placeholder="点击选择结束时间"
                           readonly
                           style="cursor: pointer; background: #fff;">
                    @error('live_end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="time-error" style="color: var(--color-danger); font-size: 0.8rem; display: none; margin-top: 0.25rem;">
                        结束时间必须晚于开始时间
                    </div>
                </div>
            </div>

            <!-- 关联专家 -->
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">
                        关联专家
                    </label>
                    <div id="selected-experts" class="mb-2" style="min-height: 40px;">
                        @php
                            $selectedIds = old('expert_ids', $lecture->expert_ids ?? []);
                        @endphp
                        @foreach($experts->whereIn('id', $selectedIds) as $expert)
                            <span class="badge rounded-pill me-1 mb-1" id="expert-tag-{{ $expert->id }}"
                                  style="background: var(--color-primary); color: #fff; padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                                {{ $expert->name }}
                                <button type="button" class="btn-close btn-close-white ms-1" style="font-size: 0.6rem;"
                                        onclick="removeExpert({{ $expert->id }})"></button>
                                <input type="hidden" name="expert_ids[]" value="{{ $expert->id }}">
                            </span>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-admin-outline" data-bs-toggle="modal" data-bs-target="#expertModal">
                        <i class="bi bi-person-plus me-1"></i> 选择专家
                    </button>
                    @error('expert_ids')
                        <div style="color: var(--color-danger); font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">
                        状态
                    </label>
                    <select name="status" class="form-select">
                        <option value="0" {{ old('status', $lecture->status ?? 0) == 0 ? 'selected' : '' }}>未开始</option>
                        <option value="1" {{ old('status', $lecture->status ?? 0) == 1 ? 'selected' : '' }}>直播中</option>
                        <option value="2" {{ old('status', $lecture->status ?? 0) == 2 ? 'selected' : '' }}>已结束</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">
                封面图片
            </label>
            <div class="text-center p-4" style="background: var(--color-bg-warm); border-radius: var(--radius-md); border: 1px dashed var(--color-border);">
                @if(isset($lecture) && $lecture->cover_image)
                    <img src="{{ $lecture->thumb_cover }}" alt="当前封面"
                         class="rounded-3 mb-3"
                         style="width: 100%; height: 150px; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <p style="color: var(--color-text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">当前封面</p>
                @else
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-3"
                         style="width: 100%; height: 150px; background: linear-gradient(135deg, var(--color-sage-light), var(--color-sage));">
                        <i class="bi bi-camera text-white" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                    <p style="color: var(--color-text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">暂无封面</p>
                @endif
                <input type="file" name="cover_image" id="coverImageInput" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                @error('cover_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small style="color: var(--color-text-light); display: block; margin-top: 0.5rem;">支持 JPG、PNG，最大 2MB</small>
            </div>
        </div>
    </div>
</div>

<!-- 专家选择弹窗 -->
<div class="modal fade" id="expertModal" tabindex="-1" aria-labelledby="expertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: var(--radius-md); border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: var(--color-bg-warm); border-bottom: 1px solid var(--color-border);">
                <h5 class="modal-title" id="expertModalLabel" style="font-weight: 600;">
                    <i class="bi bi-people me-2" style="color: var(--color-sage);"></i>选择专家
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <div id="expertList" style="max-height: 400px; overflow-y: auto;">
                    @foreach($experts as $expert)
                        <div class="expert-option d-flex align-items-center gap-3 p-3 rounded-3 mb-2"
                             data-name="{{ $expert->name }}"
                             data-hospital="{{ $expert->hospital }}"
                             style="background: var(--color-bg); border: 1px solid var(--color-border); cursor: pointer; transition: all 0.2s ease;"
                             onclick="toggleExpert({{ $expert->id }}, '{{ $expert->name }}', this)"
                             onmouseover="this.style.borderColor='var(--color-sage)'"
                             onmouseout="this.style.borderColor=this.classList.contains('selected') ? 'var(--color-primary)' : 'var(--color-border)'">
                            @if($expert->avatar)
                                <img src="{{ $expert->thumb_avatar }}" class="rounded-circle"
                                     style="width: 45px; height: 45px; object-fit: cover;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 45px; height: 45px; background: linear-gradient(135deg, var(--color-sage-light), var(--color-sage)); color: #fff;">
                                    {{ mb_substr($expert->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div style="font-weight: 600; font-size: 0.95rem;">{{ $expert->name }}</div>
                                <div style="color: var(--color-text-secondary); font-size: 0.8rem;">{{ $expert->title }} · {{ $expert->hospital }}</div>
                            </div>
                            <div class="expert-check" style="display: none;">
                                <i class="bi bi-check-circle-fill" style="color: var(--color-primary); font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--color-border); padding: 1rem 1.5rem;">
                <span id="selectedCount" style="color: var(--color-text-secondary); font-size: 0.9rem;">已选择 0 位专家</span>
                <button type="button" class="btn btn-admin-primary" data-bs-dismiss="modal">
                    <i class="bi bi-check-lg me-1"></i> 确定
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .expert-option.selected {
        border-color: var(--color-primary) !important;
        background: rgba(26, 58, 42, 0.05) !important;
    }
    .expert-option.selected .expert-check {
        display: block !important;
    }
    /* flatpickr 自定义样式 */
    .flatpickr-confirm {
        background: var(--color-primary) !important;
        color: #fff !important;
        border: none !important;
        padding: 0.5rem 1.5rem !important;
        border-radius: var(--radius-sm) !important;
        cursor: pointer !important;
        font-weight: 500 !important;
    }
    .flatpickr-confirm:hover {
        background: var(--color-primary-light) !important;
    }
    .flatpickr-cancel {
        background: transparent !important;
        color: var(--color-text-secondary) !important;
        border: 1px solid var(--color-border) !important;
        padding: 0.5rem 1.5rem !important;
        border-radius: var(--radius-sm) !important;
        cursor: pointer !important;
        margin-right: 0.5rem !important;
    }
</style>

<script>
// 初始化 flatpickr 时间选择器（带确定按钮）
flatpickr("#live_start_time", {
    locale: "zh",
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    time_24hr: true,
    minuteIncrement: 5,
    showMonths: 1,
    onClose: function(selectedDates, dateStr, instance) {
        validateTimes();
    }
});

flatpickr("#live_end_time", {
    locale: "zh",
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    time_24hr: true,
    minuteIncrement: 5,
    showMonths: 1,
    onClose: function(selectedDates, dateStr, instance) {
        validateTimes();
    }
});

// 时间验证
function validateTimes() {
    const startInput = document.getElementById('live_start_time');
    const endInput = document.getElementById('live_end_time');
    const errorDiv = document.getElementById('time-error');
    const submitBtn = document.querySelector('button[type="submit"]');

    if (startInput.value && endInput.value) {
        const start = new Date(startInput.value);
        const end = new Date(endInput.value);

        if (start >= end) {
            errorDiv.style.display = 'block';
            endInput.classList.add('is-invalid');
            if (submitBtn) submitBtn.disabled = true;
            return false;
        } else {
            errorDiv.style.display = 'none';
            endInput.classList.remove('is-invalid');
            if (submitBtn) submitBtn.disabled = false;
        }
    }
    return true;
}

// 专家选择
const selectedExperts = new Map();

document.querySelectorAll('#selected-experts input[name="expert_ids[]"]').forEach(input => {
    selectedExperts.set(parseInt(input.value), true);
});

function toggleExpert(id, name, element) {
    if (selectedExperts.has(id)) {
        removeExpert(id);
        element.classList.remove('selected');
    } else {
        selectedExperts.set(id, true);
        element.classList.add('selected');
        addExpertTag(id, name);
    }
    updateSelectedCount();
}

function addExpertTag(id, name) {
    const container = document.getElementById('selected-experts');
    if (document.getElementById('expert-tag-' + id)) return;

    const tag = document.createElement('span');
    tag.className = 'badge rounded-pill me-1 mb-1';
    tag.id = 'expert-tag-' + id;
    tag.style.cssText = 'background: var(--color-primary); color: #fff; padding: 0.4rem 0.75rem; font-size: 0.85rem;';
    tag.innerHTML = `
        ${name}
        <button type="button" class="btn-close btn-close-white ms-1" style="font-size: 0.6rem;"
                onclick="removeExpert(${id})"></button>
        <input type="hidden" name="expert_ids[]" value="${id}">
    `;
    container.appendChild(tag);
}

function removeExpert(id) {
    selectedExperts.delete(id);
    const tag = document.getElementById('expert-tag-' + id);
    if (tag) tag.remove();
    // 同步移除弹窗中对应专家选项的选中状态
    const expertOptions = document.querySelectorAll('.expert-option');
    expertOptions.forEach(option => {
        const onclickAttr = option.getAttribute('onclick');
        if (onclickAttr && onclickAttr.includes(`toggleExpert(${id},`)) {
            option.classList.remove('selected');
        }
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    document.getElementById('selectedCount').textContent = `已选择 ${selectedExperts.size} 位专家`;
}

// 表单提交验证
document.querySelector('form')?.addEventListener('submit', function(e) {
    if (!validateTimes()) {
        e.preventDefault();
        alert('请检查时间设置：结束时间必须晚于开始时间');
    }
});

// 封面图片实时预览
(function() {
    var coverInput = document.getElementById('coverImageInput');
    if (coverInput) {
        coverInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) return;

            var reader = new FileReader();
            reader.onload = function(ev) {
                var container = coverInput.closest('.text-center');
                if (!container) return;

                var existingImg = container.querySelector('img[alt="当前封面"]');
                var placeholder = container.querySelector('div[style*="linear-gradient"]');

                if (existingImg) {
                    existingImg.src = ev.target.result;
                } else if (placeholder) {
                    var img = document.createElement('img');
                    img.src = ev.target.result;
                    img.alt = '封面预览';
                    img.className = 'rounded-3 mb-3';
                    img.style.cssText = 'width:100%;height:150px;object-fit:cover;box-shadow:0 4px 15px rgba(0,0,0,0.1);';
                    placeholder.parentNode.replaceChild(img, placeholder);

                    var hint = container.querySelector('p');
                    if (hint) hint.textContent = '封面预览';
                }
            };
            reader.readAsDataURL(file);
        });
    }
})();
</script>
