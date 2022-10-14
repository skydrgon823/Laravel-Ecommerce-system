@php
    $page->loadMissing('metadata');

    Theme::set('page', $page);
@endphp

@if ($page->template == 'default')
    <section class="mt-60 mb-60">
        {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, BaseHelper::clean($page->content), $page) !!}
    </section>
@else
    {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, BaseHelper::clean($page->content), $page) !!}
@endif
