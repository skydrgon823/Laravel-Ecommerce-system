<section class="section-padding-60">
    <div class="container">
        <div class="col-12">
            @if (clean($title))
                <h3 class="section-title style-1 mb-30 wow fadeIn animated">{!! BaseHelper::clean($title) !!}</h3>
            @endif
            <featured-news-component url="{{ route('public.ajax.posts') }}"></featured-news-component>
        </div>
    </div>
</section>
