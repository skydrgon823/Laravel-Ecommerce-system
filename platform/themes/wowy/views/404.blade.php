@php
    SeoHelper::setTitle(__('404 - Not found'));
    Theme::fireEventGlobalAssets();
@endphp

{!! Theme::partial('header') !!}

<main class="main page-404">
    <div class="container">
        <div class="row align-items-center text-center">
            <div class="col-lg-8 m-auto mt-50 mb-50">
                <p class="mb-50"><img src="{{ Theme::asset()->url('images/404.png') }}" alt="{{ theme_option('site_title') }}" class="hover-up"></p>
                <h2 class="mb-30">{{ __('Page Not Found') }}</h2>
                <p class="font-lg text-grey-700 mb-30">
                    {!! BaseHelper::clean(__('The link you clicked may be broken or the page may have been removed.<br> visit the <a href=":link"> <span> Homepage</span></a> or <a href=":mail"><span>Contact us</span></a> about the problem.', ['link' => route('public.index'), 'mail' => 'mailto:' . theme_option('email')])) !!}
                </p>
                @if (is_plugin_active('ecommerce'))
                    <form class="contact-form-style text-center" id="contact-form" action="{{ route('public.products') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-6 m-auto">
                                <div class="input-style mb-20 hover-up">
                                    <input name="q" placeholder="{{ __('Search...') }}" type="text">
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-default submit-auto-width font-xs hover-up" href="{{ route('public.index') }}">{{ __('Back To Home Page') }}</a>
                    </form>
                @endif
            </div>
        </div>
    </div>
</main>

{!! Theme::partial('footer') !!}


