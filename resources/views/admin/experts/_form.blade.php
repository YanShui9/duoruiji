<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label">专家姓名 <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $expert->name ?? '') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">职称 <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $expert->title ?? '') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">所属医院 <span class="text-danger">*</span></label>
            <input type="text" name="hospital" class="form-control @error('hospital') is-invalid @enderror" value="{{ old('hospital', $expert->hospital ?? '') }}" required>
            @error('hospital')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">科室 <span class="text-danger">*</span></label>
            <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department', $expert->department ?? '') }}" required>
            @error('department')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">专家简介</label>
            <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="4">{{ old('bio', $expert->bio ?? '') }}</textarea>
            @error('bio')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">排序</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $expert->sort_order ?? 0) }}" min="0">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">状态</label>
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
            <label class="form-label">专家头像</label>
            <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
            @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if(isset($expert) && $expert->avatar)
                <div class="mt-2">
                    <img src="{{ Storage::url($expert->avatar) }}" alt="当前头像" class="img-thumbnail" width="150">
                </div>
            @endif
        </div>
    </div>
</div>
