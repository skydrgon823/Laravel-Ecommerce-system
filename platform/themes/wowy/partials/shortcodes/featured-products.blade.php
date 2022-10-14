<section class="section-padding-60">
    <div class="container wow fadeIn animated">
        @if (clean($title))
            <h3 class="section-title style-1 mb-30">{!! BaseHelper::clean($title) !!}</h3>
        @endif
        <featured-products-component url="{{ route('public.ajax.featured-products', ['limit' => $limit]) }}"></featured-products-component>
    </div>
</section>
