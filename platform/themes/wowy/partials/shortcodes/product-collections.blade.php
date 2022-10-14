<section class="product-tabs pt-40 pb-30 wow fadeIn animated">
    <product-collections-component title="{!! BaseHelper::clean($title) !!}" :product_collections="{{ json_encode($productCollections) }}" url="{{ route('public.ajax.products') }}"></product-collections-component>
</section>
