<div class="row">
    <div class="col-md-8">
        <div class="row g-3">
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">
                        所属讲座 <span style="color: var(--color-danger);">*</span>
                    </label>
                    <select name="lecture_id" class="form-select @error('lecture_id') is-invalid @enderror" required>
                        <option value="">请选择讲座</option>
                        @foreach($lectures as $lecture)
                            <option value="{{ $lecture->id }}" {{ old('lecture_id', $video->lecture_id ?? '') == $lecture->id ? 'selected' : '' }}>
                                {{ $lecture->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('lecture_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">
                        视频标题 <span style="color: var(--color-danger);">*</span>
                    </label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $video->title ?? '') }}"
                           placeholder="请输入视频标题"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">
                        视频简介
                    </label>
                    <textarea name="description" class="form-control rich-editor @error('description') is-invalid @enderror"
                              rows="4"
                              placeholder="请输入视频简介...">{{ old('description', $video->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">
                        视频文件 {{ isset($video) ? '' : '(必填)' }}
                    </label>
                    <input type="file" name="video_file" class="form-control @error('video_file') is-invalid @enderror" accept="video/*">
                    @error('video_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if(isset($video) && $video->video_url)
                        <div class="mt-2 p-3 rounded-3" style="background: var(--color-bg-warm);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-play me-2" style="color: var(--color-sage);"></i>
                                <span style="color: var(--color-text-secondary);">当前文件: {{ basename($video->video_url) }}</span>
                            </div>
                        </div>
                    @endif
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
                            $selectedIds = old('expert_ids', $video->expert_ids ?? []);
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
                        时长（秒）
                    </label>
                    <input type="number" name="duration" class="form-control"
                           value="{{ old('duration', $video->duration ?? 0) }}"
                           min="0"
                           placeholder="视频时长">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">
                        状态
                    </label>
                    <select name="status" class="form-select">
                        <option value="1" {{ old('status', $video->status ?? 1) == 1 ? 'selected' : '' }}>启用</option>
                        <option value="0" {{ old('status', $video->status ?? 1) == 0 ? 'selected' : '' }}>禁用</option>
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
                @if(isset($video) && $video->cover_image)
                    <img src="{{ $video->thumb_cover }}" alt="当前封面"
                         class="rounded-3 mb-3"
                         style="width: 100%; height: 150px; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <p style="color: var(--color-text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">当前封面</p>
                @else
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-3"
                         style="width: 100%; height: 150px; background: linear-gradient(135deg, var(--color-sage-light), var(--color-sage));">
                        <i class="bi bi-play-circle text-white" style="font-size: 3rem; opacity: 0.5;"></i>
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
                <div class="mb-3">
                    <input type="text" id="expertSearch" class="form-control" placeholder="搜索专家姓名、医院..."
                           oninput="filterExperts()">
                </div>
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
</style>

<script>
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
    updateSelectedCount();
}

function updateSelectedCount() {
    document.getElementById('selectedCount').textContent = `已选择 ${selectedExperts.size} 位专家`;
}

function filterExperts() {
    const search = document.getElementById('expertSearch').value.toLowerCase();
    document.querySelectorAll('.expert-option').forEach(option => {
        const name = option.dataset.name.toLowerCase();
        const hospital = option.dataset.hospital.toLowerCase();
        option.style.display = (name.includes(search) || hospital.includes(search)) ? 'flex' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    updateSelectedCount();

    // 封面图片实时预览
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

                // 查找已有的预览图片或占位符
                var existingImg = container.querySelector('img[alt="当前封面"]');
                var placeholder = container.querySelector('div[style*="linear-gradient"]');

                if (existingImg) {
                    // 更新已有图片
                    existingImg.src = ev.target.result;
                } else if (placeholder) {
                    // 替换绿色占位符为图片
                    var img = document.createElement('img');
                    img.src = ev.target.result;
                    img.alt = '封面预览';
                    img.className = 'rounded-3 mb-3';
                    img.style.cssText = 'width:100%;height:150px;object-fit:cover;box-shadow:0 4px 15px rgba(0,0,0,0.1);';
                    placeholder.parentNode.replaceChild(img, placeholder);

                    // 更新提示文字
                    var hint = container.querySelector('p');
                    if (hint) hint.textContent = '封面预览';
                }
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>
