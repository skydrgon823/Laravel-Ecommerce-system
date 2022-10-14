<div class="sidebar-widget widget_categories mb-50">
    <div class="widget-header position-relative mb-20 pb-10">
        <h5 class="widget-title">{{ $config['name'] }}</h5>
    </div>
    <div class="post-block-list post-module-1 post-module-5">
        <ul>
            @foreach(app(\Botble\Blog\Repositories\Interfaces\CategoryInterface::class)->advancedGet(['condition' => ['status' => \Botble\Base\Enums\BaseStatusEnum::PUBLISHED], 'take' => $config['number_display'], 'with' => ['slugable'], 'withCount' => ['posts']]) as $category)
            <li class="cat-item cat-item-2">
                <a href="{{ $category->url }}">{{ $category->name }}</a> ({{ $category->posts_count }})
            @endforeach
        </ul>
    </div>
</div>
