<div class="sidebar-widget widget_search mb-50">
    <div class="widget-header position-relative mb-20 pb-10">
        <h5 class="widget-title">{{ $config['name'] }}</h5>
    </div>
    <div class="search-form">
        <form action="{{ route('public.search') }}" method="GET">
            <input type="text" name="q" value="{{ request()->query('q') }}" placeholder="{{ __('Search...') }}">
            <button type="submit"> <i class="far fa-search"></i> </button>
        </form>
    </div>
</div>
