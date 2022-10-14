@foreach($attributeSets as $attributeSet)
    <ul class="widget-content widget-sidebar widget-filter-color">
        @if(view()->exists('plugins/ecommerce::themes.attributes._layouts-filter.' . $attributeSet->display_layout))
            @include('plugins/ecommerce::themes.attributes._layouts-filter.' . $attributeSet->display_layout, [
                'set'        => $attributeSet,
                'attributes' => $attributeSet->attributes,
                'selected'   => (array)request()->query('attributes', []),
            ])
        @else
            @include('plugins/ecommerce::themes.attributes._layouts.dropdown', [
                'set'        => $attributeSet,
                'attributes' => $attributeSet->attributes,
                'selected'   => (array)request()->query('attributes', []),
            ])
        @endif
    </ul>
@endforeach
