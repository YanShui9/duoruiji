<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label">所属讲座 <span class="text-danger">*</span></label>
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

        <div class="mb-3">
            <label class="form-label">视频标题 <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $video->title ?? '') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">视频简介</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $video->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">视频文件 {{ isset($video) ? '' : '(必填)' }}</label>
            <input type="file" name="video_file" class="form-control @error('video_file') is-invalid @enderror" accept="video/*">
            @error('video_file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if(isset($video) && $video->video_url)
                <small class="text-muted">当前文件: {{ basename($video->video_url) }}</small>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">关联专家</label>
            <select name="expert_ids[]" class="form-select" multiple size="5">
                @foreach($experts as $expert)
                    <option value="{{ $expert->id }}" {{ in_array($expert->id, old('expert_ids', $video->expert_ids ?? [])) ? 'selected' : '' }}>
                        {{ $expert->name }} - {{ $expert->hospital }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">按住 Ctrl 多选</small>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">时长（秒）</label>
                    <input type="number" name="duration" class="form-control" value="{{ old('duration', $video->duration ?? 0) }}" min="0">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">排序</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $video->sort_order ?? 0) }}" min="0">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">状态</label>
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
            <label class="form-label">封面图片</label>
            <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
            @error('cover_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if(isset($video) && $video->cover_image)
                <div class="mt-2">
                    <img src="{{ Storage::url($video->cover_image) }}" alt="当前封面" class="img-thumbnail">
                </div>
            @endif
        </div>
    </div>
</div>
