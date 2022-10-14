<section class="popular-categories bg-grey-9 section-padding-60" id="featured-product-categories">
    <div class="container wow fadeIn animated">
        <h3 class="section-title mb-30">{!! BaseHelper::clean($title) !!}</h3>
        <featured-product-categories-component url="{{ route('public.ajax.featured-product-categories') }}"></featured-product-categories-component>
    </div>
</section>
