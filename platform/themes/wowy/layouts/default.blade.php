{!! Theme::partial('header') !!}

<main class="main" id="main-section">
    @if (Theme::get('hasBreadcrumb', true))
        {!! Theme::partial('breadcrumb') !!}
    @endif

    <div class="container">
        {!! Theme::content() !!}
    </div>
</main>

{!! Theme::partial('footer') !!}
