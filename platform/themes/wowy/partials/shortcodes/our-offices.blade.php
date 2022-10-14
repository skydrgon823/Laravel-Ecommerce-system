@if (theme_option('contact_info_boxes'))
    <div class="mt-50 pb-50">
        <div class="row">
            @foreach(json_decode(theme_option('contact_info_boxes'), true) as $item)
                @if (count($item) == 4)
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h4 class="mb-15 text-muted">{!! BaseHelper::clean($item[0]['value']) !!}</h4>
                        {!! BaseHelper::clean($item[1]['value']) !!}<br>
                        <abbr title="{{ __('Phone') }}">{{ __('Phone') }}:</abbr> {!! BaseHelper::clean($item[2]['value']) !!}<br>
                        <abbr title="{{ __('Email') }}">{{ __('Email') }}: </abbr>{!! BaseHelper::clean($item[3]['value']) !!}<br>
                        <a class="btn btn-outline btn-sm btn-brand-outline font-weight-bold text-brand bg-white text-hover-white mt-20 border-radius-5 btn-shadow-brand hover-up" href="https://maps.google.com/?q={{ urlencode(clean($item[1]['value'])) }}"><i class="fa fa-map text-muted mr-15"></i>{{ __('View map') }}</a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <hr>
@endif
