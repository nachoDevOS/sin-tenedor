@php
    $cantStock = $product->itemSalestocks->sum('stock');
    $isOutOfStock = ($product->typeSale == 'Venta Con Stock' && $cantStock == 0);
    $image = $product->image ? asset('storage/'.str_replace(['.jpg', '.png'], '-cropped.webp', $product->image)) : asset('images/default.jpg');
@endphp

<div class="col-md-3 col-sm-4 col-xs-6" style="margin-bottom: 15px;">
    <div class="product-card-wrapper">
        <div class="product-card {{ $isOutOfStock ? 'out-of-stock' : '' }}" 
             data-product-id="{{ $product->id }}" 
             data-type-sale="{{ $product->typeSale }}">
            
            <img src="{{ $image }}" class="img-responsive">
            <div class="product-info">
                {{-- <h5>{{ $product->name }}</h5> --}}
                <p>{{Str::limit($product->name, 20, '...')}}</p>
                <p class="text-success">Bs. {{ number_format($product->price, 2, ',', '.') }}</p>
                @if ($product->typeSale == 'Venta Con Stock')
                    Stock: <small class="{{ $cantStock == 0 ? 'text-danger' : '' }}">{{ number_format($cantStock, 2, ',', '.') }}</small>
                @else
                    <small class="text-info">Venta Libre</small>
                @endif
            </div>
        </div>
    </div>
</div>