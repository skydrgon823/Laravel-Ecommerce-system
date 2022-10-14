<div class="sidebar-widget widget_tags mb-50">
    <div class="widget-header position-relative mb-20 pb-10">
        <h5 class="widget-title">{{ $config['name'] }}</h5>
    </div>
    <div class="tagcloud">
        @foreach (get_popular_tags($config['number_display'], ['slugable'], ['posts']) as $tag)
            <a class="tag-cloud-link" href="{{ $tag->url }}">{{ $tag->name }} </a>
        @endforeach
    </div>
</div>
