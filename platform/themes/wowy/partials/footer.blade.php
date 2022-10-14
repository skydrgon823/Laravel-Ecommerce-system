    @if (is_plugin_active('newsletter'))
        {!! do_shortcode('[newsletter-form title="' . __('Sign up to Newsletter') . '" description="' . __('...and receive $25 coupon for first shopping.') . '"][/newsletter-form]') !!}
    @endif
    <footer class="main">
        <section class="section-padding-60">
            <div class="container">
                <div class="row">
                    {!! dynamic_sidebar('footer_sidebar') !!}
                </div>
            </div>
        </section>
        <div class="container pb-20 wow fadeIn animated">
            <div class="row">
                <div class="col-12 mb-20">
                    <div class="footer-bottom"></div>
                </div>
                <div class="col-lg-6">
                    <p class="float-md-left font-sm text-muted mb-0">{{ theme_option('copyright') }}</p>
                </div>
                <div class="col-lg-6">
                    <p class="text-lg-end text-start font-sm text-muted mb-0">
                        {{ __('All rights reserved.') }}
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Quick view -->
    <div class="modal fade custom-modal" id="quick-view-modal" tabindex="-1" aria-labelledby="quick-view-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <div class="half-circle-spinner loading-spinner">
                        <div class="circle circle-1"></div>
                        <div class="circle circle-2"></div>
                    </div>
                    <div class="quick-view-content"></div>
                </div>
            </div>
        </div>
    </div>

        <script>
            window.siteUrl = "{{ route('public.index') }}";
        </script>

        @if (is_plugin_active('ecommerce'))
            <script>
                window.currencies = {!! json_encode(get_currencies_json()) !!};
            </script>
        @endif

        {!! Theme::footer() !!}

    <script>
        window.trans = {
            "Views": "{{ __('Views') }}",
            "Read more": "{{ __('Read more') }}",
            "days": "{{ __('days') }}",
            "hours": "{{ __('hours') }}",
            "mins": "{{ __('mins') }}",
            "sec": "{{ __('sec') }}",
            "No reviews!": "{{ __('No reviews!') }}"
        };
    </script>

    {!! Theme::place('footer') !!}

    @if (session()->has('success_msg') || session()->has('error_msg') || (isset($errors) && $errors->count() > 0) || isset($error_msg))
            <script type="text/javascript">
                $(document).ready(function () {
                    @if (session()->has('success_msg'))
                    window.showAlert('alert-success', '{{ session('success_msg') }}');
                    @endif

                    @if (session()->has('error_msg'))
                    window.showAlert('alert-danger', '{{ session('error_msg') }}');
                    @endif

                    @if (isset($error_msg))
                    window.showAlert('alert-danger', '{{ $error_msg }}');
                    @endif

                    @if (isset($errors))
                    @foreach ($errors->all() as $error)
                    window.showAlert('alert-danger', '{!! BaseHelper::clean($error) !!}');
                    @endforeach
                    @endif
                });
            </script>
        @endif
    </body>
</html>
