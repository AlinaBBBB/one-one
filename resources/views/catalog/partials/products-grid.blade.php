@if($products->count() > 0)
<div class="row g-4" id="productsGrid">
    @foreach($products as $product)
    <div class="col-xl-3 col-lg-4 col-md-6">
        @include('catalog.partials.product-card', ['product' => $product])
    </div>
    @endforeach
</div>

@if($products->hasPages())
<div class="row mt-5">
    <div class="col-12">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                @if($products->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">‹</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->previousPageUrl() }}" 
                           onclick="loadPage(event, '{{ $products->previousPageUrl() }}'); return false;">‹</a>
                    </li>
                @endif
                
                @php
                    $current = $products->currentPage();
                    $last = $products->lastPage();
                    $start = max(1, $current - 2);
                    $end = min($last, $current + 2);
                @endphp
                
                @if($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->url(1) }}" 
                           onclick="loadPage(event, '{{ $products->url(1) }}'); return false;">1</a>
                    </li>
                    @if($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif
                
                @for($i = $start; $i <= $end; $i++)
                    @if($i == $current)
                        <li class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->url($i) }}" 
                               onclick="loadPage(event, '{{ $products->url($i) }}'); return false;">{{ $i }}</a>
                        </li>
                    @endif
                @endfor
                
                @if($end < $last)
                    @if($end < $last - 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->url($last) }}" 
                           onclick="loadPage(event, '{{ $products->url($last) }}'); return false;">{{ $last }}</a>
                    </li>
                @endif
                
                @if($products->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->nextPageUrl() }}" 
                           onclick="loadPage(event, '{{ $products->nextPageUrl() }}'); return false;">›</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">›</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endif

@else
<div class="col-12">
    <div class="text-center py-5">
        <i class="bi bi-search display-1 text-muted mb-3"></i>
        <h4>Товары не найдены</h4>
        <p class="text-muted">Попробуйте изменить параметры фильтрации</p>
        <button class="btn btn-dark mt-3" onclick="resetFilters()">
            Сбросить фильтры
        </button>
    </div>
</div>
@endif