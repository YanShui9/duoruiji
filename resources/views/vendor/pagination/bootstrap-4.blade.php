@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $start = max(1, $current - 4);
        $end = min($last, $current + 4);
        if ($current - $start < 4) {
            $end = min($last, $end + (4 - ($current - $start)));
        }
        if ($end - $current < 4) {
            $start = max(1, $start - (4 - ($end - $current)));
        }
        if ($end - $start > 9) {
            if ($current - $start < 5) {
                $end = $start + 9;
            } else {
                $start = $end - 9;
            }
        }
    @endphp

    <div class="frontend-pagination">
        <div class="pagination-nav">
            {{-- 上一页 --}}
            @if ($paginator->onFirstPage())
                <span class="page-btn disabled">
                    <i class="bi bi-chevron-left" style="font-size: 0.8rem;"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">
                    <i class="bi bi-chevron-left" style="font-size: 0.8rem;"></i>
                </a>
            @endif

            {{-- 首页 --}}
            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" class="page-btn">1</a>
                @if ($start > 2)
                    <span class="page-ellipsis">···</span>
                @endif
            @endif

            {{-- 页码 --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <span class="page-btn active">{{ $i }}</span>
                @else
                    <a href="{{ $paginator->url($i) }}" class="page-btn">{{ $i }}</a>
                @endif
            @endfor

            {{-- 末页 --}}
            @if ($end < $last)
                @if ($end < $last - 1)
                    <span class="page-ellipsis">···</span>
                @endif
                <a href="{{ $paginator->url($last) }}" class="page-btn">{{ $last }}</a>
            @endif

            {{-- 下一页 --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">
                    <i class="bi bi-chevron-right" style="font-size: 0.8rem;"></i>
                </a>
            @else
                <span class="page-btn disabled">
                    <i class="bi bi-chevron-right" style="font-size: 0.8rem;"></i>
                </span>
            @endif
        </div>

        {{-- 页码信息与跳转 --}}
        <div class="page-info">
            <span>{{ $paginator->total() }} 条</span>
            <div class="page-jump">
                <input type="number" id="page-jump"
                       min="1" max="{{ $paginator->lastPage() }}" value="{{ $current }}"
                       onkeydown="if(event.key==='Enter'){this.nextElementSibling.click();}">
                <button type="button" onclick="jumpToPage(this)">跳转</button>
            </div>
        </div>
    </div>

    <script>
    if (!window.__pageJumpDefined) {
        window.__pageJumpDefined = true;
        window.jumpToPage = function(btn) {
            var input = btn.previousElementSibling;
            var page = parseInt(input.value);
            var maxPage = parseInt(input.getAttribute('max'));
            if (page < 1 || page > maxPage || isNaN(page)) {
                input.focus();
                return;
            }
            var url = new URL(window.location.href);
            url.searchParams.set('page', page);
            window.location.href = url.toString();
        };
    }
    </script>
@endif
