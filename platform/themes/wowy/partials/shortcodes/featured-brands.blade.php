<section class="section-padding-60">
    <div class="container">
        <h3 class="section-title style-1 mb-30 wow fadeIn animated">{!! BaseHelper::clean($title) !!}</h3>
        <featured-brands-component url="{{ route('public.ajax.featured-brands') }}"></featured-brands-component>
    </div>
</section>
