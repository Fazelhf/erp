@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between" dir="rtl">
        <div class="d-flex flex-fill justify-content-between">
            <ul class="pagination">
                {{-- Previous Page Link (در RTL این بعدی است - فلش راست) --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">&rsaquo; بعدی</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&rsaquo; بعدی</a>
                    </li>
                @endif

                {{-- Next Page Link (در RTL این قبلی است - فلش چپ) --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">قبلی &lsaquo;</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">قبلی &lsaquo;</span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif
