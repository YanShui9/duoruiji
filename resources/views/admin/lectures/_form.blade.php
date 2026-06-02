<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label">讲座标题 <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $lecture->title ?? '') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">讲座简介</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $lecture->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">直播链接</label>
            <input type="url" name="live_url" class="form-control @error('live_url') is-invalid @enderror" value="{{ old('live_url', $lecture->live_url ?? '') }}">
            @error('live_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">直播开始时间</label>
                    <input type="datetime-local" name="live_start_time" class="form-control" value="{{ old('live_start_time', isset($lecture) && $lecture->live_start_time ? $lecture->live_start_time->format('Y-m-d\TH:i') : '') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">直播结束时间</label>
                    <input type="datetime-local" name="live_end_time" class="form-control" value="{{ old('live_end_time', isset($lecture) && $lecture->live_end_time ? $lecture->live_end_time->format('Y-m-d\TH:i') : '') }}">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">关联专家</label>
            <select name="expert_ids[]" class="form-select" multiple size="5">
                @foreach($experts as $expert)
                    <option value="{{ $expert->id }}" {{ in_array($expert->id, old('expert_ids', $lecture->expert_ids ?? [])) ? 'selected' : '' }}>
                        {{ $expert->name }} - {{ $expert->hospital }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">按住 Ctrl 多选</small>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">排序</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $lecture->sort_order ?? 0) }}" min="0">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">状态</label>
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
            <label class="form-label">封面图片</label>
            <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
            @error('cover_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if(isset($lecture) && $lecture->cover_image)
                <div class="mt-2">
                    <img src="{{ Storage::url($lecture->cover_image) }}" alt="当前封面" class="img-thumbnail">
                </div>
            @endif
        </div>
    </div>
</div>
