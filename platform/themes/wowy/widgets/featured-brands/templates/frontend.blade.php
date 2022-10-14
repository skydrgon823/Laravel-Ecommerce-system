<div class="sidebar-widget widget_categories mb-50 p-20 bg-grey border-radius-10">
    <div class="widget-header position-relative mb-20 pb-10">
        <h5 class="widget-title mb-10">{{ $config['name'] }}</h5>
        <div class="bt-1 border-color-1"></div>
    </div>
    <div>
        <ul class="categor-list">
            @foreach(get_featured_brands($config['number_display'], ['slugable'], ['products']) as $brand)
                <li class="cat-item text-muted"><a href="{{ $brand->url }}">{{ $brand->name }}</a>({{ $brand->products_count }})</li>
            @endforeach
        </ul>
    </div>
</div>
