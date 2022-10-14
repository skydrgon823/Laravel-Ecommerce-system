<div class="hero-slider-content-2">
    @if ($subtitle = $slider->getMetaData('subtitle', true))
        <h4 class="animated">{!! BaseHelper::clean($subtitle) !!}</h4>
    @endif

    @if ($slider->title)
        <h2 class="animated fw-900">{!! BaseHelper::clean($slider->title) !!}</h2>
    @endif

    @if ($highlightText = $slider->getMetaData('highlight_text', true))
        <h1 class="animated fw-900 text-brand">{!! BaseHelper::clean($highlightText) !!}</h1>
    @endif

    @if ($slider->description)
        <p class="animated">{!! BaseHelper::clean($slider->description) !!}</p>
    @endif
    @if ($slider->link)
        <a class="animated btn btn-default btn-rounded" href="{{ url($slider->link) }}">{!! BaseHelper::clean($slider->getMetaData('button_text', true)) !!} <i class="fa fa-arrow-right"></i> </a>
    @endif
</div>
