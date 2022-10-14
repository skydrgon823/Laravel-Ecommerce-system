<input type="hidden" name="page" data-value="{{ $products->currentPage() }}">

<div class="row">
    @forelse ($products as $product)
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6">
            @include('plugins/ecommerce::themes.includes.default-product', compact('product'))
        </div>
    @empty
        <div class="alert alert-warning" role="alert">
            {{ __(':total Products found', ['total' => 0]) }}
        </div>
    @endforelse
</div>

{!! $products->withQueryString()->links() !!}
