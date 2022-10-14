<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            @foreach ($crumbs = Theme::breadcrumb()->getCrumbs() as $i => $crumb)
                @if ($i != (count($crumbs) - 1))
                    <a href="{{ $crumb['url'] }}" itemprop="item" title="{{ $crumb['label'] }}">
                        {{ $crumb['label'] }}
                        <meta itemprop="name" content="{{ $crumb['label'] }}" />
                        <meta itemprop="position" content="{{ $i + 1}}" />
                    </a>
                    <span></span>
                @else
                    {{ $crumb['label'] }}
                    <meta itemprop="name" content="{{ $crumb['label'] }}" />
                    <meta itemprop="position" content="{{ $i + 1}}" />
                @endif
            @endforeach
        </div>
    </div>
</div>
