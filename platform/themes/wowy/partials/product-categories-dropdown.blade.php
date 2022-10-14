@foreach($categories as $category)
    @if ((!$more && $loop->index < 10) || ($more && $loop->index >= 10))
        <li @if ($category->activeChildren->count() > 0) class="has-children" @endif>
            <a href="{{ $category->url }}">
                @if ($category->getMetaData('icon_image', true))
                    <img src="{{ RvMedia::getImageUrl($category->getMetaData('icon_image', true)) }}" alt="{{ $category->name }}" width="18" height="18">
                @elseif ($category->getMetaData('icon', true))
                    <i class="{{ $category->getMetaData('icon', true) }}"></i>
                @endif {{ $category->name }}
            </a>

            @if ($category->activeChildren->count() > 0)
                <div class="dropdown-menu">
                    <ul>
                        @foreach($category->activeChildren as $childCategory)
                            <li @if ($childCategory->activeChildren->count() > 0) class="has-children" @endif>
                                <a class="dropdown-item nav-link nav_item" href="{{ $childCategory->url }}">{{ $childCategory->name }}</a>

                                @if ($childCategory->activeChildren->count() > 0)
                                    <div class="dropdown-menu">
                                        <ul>
                                            @foreach($childCategory->activeChildren as $childOfChildCategory)
                                                <li>
                                                    <a class="dropdown-item nav-link nav_item" href="{{ $childOfChildCategory->url }}">{{ $childOfChildCategory->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </li>
    @endif
@endforeach
