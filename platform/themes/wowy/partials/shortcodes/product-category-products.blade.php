@if ($category)
    <section class="bg-grey-9 section-padding-60">
        <product-category-products-component :category="{{ json_encode($category) }}" :children="{{ json_encode($category->activeChildren) }}" url="{{ route('public.ajax.product-category-products') }}" all="{{ $category->url }}"></product-category-products-component>
    </section>
@endif
