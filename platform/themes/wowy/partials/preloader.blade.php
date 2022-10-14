@if (theme_option('preloader_enabled', 'no') == 'yes')
    @if (theme_option('preloader_version', 'v1') == 'v1' && theme_option('preloader_image'))
        <style>
            .preloader {
                background-color: #f7f7f7;
                bottom: 0;
                height: 100%;
                left: 0;
                margin: 0 auto;
                position: fixed;
                right: 0;
                top: 0;
                transition: .6s;
                width: 100%;
                z-index: 9999999;
            }
        </style>
        <!-- Preloader Start -->
        <div id="preloader-active">
            <div class="preloader d-flex align-items-center justify-content-center">
                <div class="preloader-inner position-relative">
                    <div class="text-center">
                        @if (theme_option('preloader_image') || theme_option('favicon'))
                            <img class="jump" src="{{ RvMedia::getImageUrl(theme_option('preloader_image') ?: theme_option('favicon')) }}" alt="logo">
                        @endif
                        <h5 class="mb-5">{{ __('Now Loading') }}</h5>
                        <div class="loader">
                            <div class="bar bar1"></div>
                            <div class="bar bar2"></div>
                            <div class="bar bar3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (theme_option('preloader_version', 'v1') == 'v2')
        <style>
            .preloader {
                position         : fixed;
                top              : 0;
                left             : 0;
                width            : 100%;
                height           : 100%;
                z-index          : 99999;
                background-color : rgba(255, 255, 255, 0.82);
            }

            .preloader .preloader-loading {
                position  : absolute;
                top       : 50%;
                left      : 50%;
                transform : translate(-50%, -50%);
                display   : block;
            }

            .preloader .preloader-loading::after {
                content           : " ";
                display           : block;
                border-radius     : 50%;
                border-width      : 1px;
                border-style      : solid;
                -webkit-animation : lds-dual-ring 0.5s linear infinite;
                animation         : lds-dual-ring 0.5s linear infinite;
                width             : 40px;
                height            : 40px;
                border-color      : var(--color-brand) transparent var(--color-brand) transparent;
            }

            @keyframes lds-dual-ring {
                0% {
                    transform : rotate(0deg);
                }
                100% {
                    transform : rotate(360deg);
                }
            }

            @-webkit-keyframes lds-dual-ring {
                0% {
                    transform : rotate(0deg);
                }
                100% {
                    transform : rotate(360deg);
                }
            }
        </style>
        <div class="preloader" id="preloader-active">
            <div class="preloader-loading"></div>
        </div>
    @endif

@endif
