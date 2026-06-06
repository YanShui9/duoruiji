<div class="row">
    <div class="col-md-8">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">
                        专家姓名 <span style="color: var(--color-danger);">*</span>
                    </label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $expert->name ?? '') }}"
                           placeholder="请输入专家姓名"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">
                        职称 <span style="color: var(--color-danger);">*</span>
                    </label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $expert->title ?? '') }}"
                           placeholder="如：主任医师、教授"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">
                        所属医院 <span style="color: var(--color-danger);">*</span>
                    </label>
                    <input type="text" name="hospital" class="form-control @error('hospital') is-invalid @enderror"
                           value="{{ old('hospital', $expert->hospital ?? '') }}"
                           placeholder="请输入所属医院"
                           required>
                    @error('hospital')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">
                        科室 <span style="color: var(--color-danger);">*</span>
                    </label>
                    <input type="text" name="department" class="form-control @error('department') is-invalid @enderror"
                           value="{{ old('department', $expert->department ?? '') }}"
                           placeholder="请输入科室"
                           required>
                    @error('department')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">
                        专家简介
                    </label>
                    <textarea name="bio" class="form-control rich-editor @error('bio') is-invalid @enderror"
                              rows="5"
                              placeholder="请输入专家简介...">{{ old('bio', $expert->bio ?? '') }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">
                        状态
                    </label>
                    <select name="status" class="form-select">
                        <option value="1" {{ old('status', $expert->status ?? 1) == 1 ? 'selected' : '' }}>启用</option>
                        <option value="0" {{ old('status', $expert->status ?? 1) == 0 ? 'selected' : '' }}>禁用</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">
                专家头像
            </label>
            <div class="text-center p-4" style="background: var(--color-bg-warm); border-radius: var(--radius-md); border: 1px dashed var(--color-border);">
                @if(isset($expert) && $expert->avatar)
                    <img src="{{ $expert->thumb_avatar }}" alt="当前头像"
                         class="rounded-circle mb-3"
                         style="width: 150px; height: 150px; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <p style="color: var(--color-text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">当前头像</p>
                @else
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                         style="width: 150px; height: 150px; background: linear-gradient(135deg, var(--color-sage-light), var(--color-sage));">
                        <i class="bi bi-person text-white" style="font-size: 4rem;"></i>
                    </div>
                    <p style="color: var(--color-text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">暂无头像</p>
                @endif
                <input type="file" name="avatar" id="avatarInput" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                @error('avatar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small style="color: var(--color-text-light); display: block; margin-top: 0.5rem;">支持 JPG、PNG、GIF，最大 2MB</small>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 头像图片实时预览
    var avatarInput = document.getElementById('avatarInput');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) return;

            var reader = new FileReader();
            reader.onload = function(ev) {
                var container = avatarInput.closest('.text-center');
                if (!container) return;

                var existingImg = container.querySelector('img[alt="当前头像"]');
                var placeholder = container.querySelector('div[style*="linear-gradient"]');

                if (existingImg) {
                    existingImg.src = ev.target.result;
                } else if (placeholder) {
                    var img = document.createElement('img');
                    img.src = ev.target.result;
                    img.alt = '头像预览';
                    img.className = 'rounded-circle mb-3';
                    img.style.cssText = 'width:150px;height:150px;object-fit:cover;box-shadow:0 4px 15px rgba(0,0,0,0.1);';
                    placeholder.parentNode.replaceChild(img, placeholder);

                    var hint = container.querySelector('p');
                    if (hint) hint.textContent = '头像预览';
                }
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>
