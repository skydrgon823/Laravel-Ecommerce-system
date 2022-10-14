@foreach($attributeSets as $attributeSet)
    @if(view()->exists(Theme::getThemeNamespace(). '::views.ecommerce.attributes._layouts-filter.' . $attributeSet->display_layout))
        @include(Theme::getThemeNamespace(). '::views.ecommerce.attributes._layouts-filter.' . $attributeSet->display_layout, [
            'set'        => $attributeSet,
            'attributes' => $attributeSet->attributes,
            'selected'   => (array)request()->query('attributes', []),
        ])
    @else
        @include(Theme::getThemeNamespace(). '::views.ecommerce.attributes._layouts.dropdown', [
            'set'        => $attributeSet,
            'attributes' => $attributeSet->attributes,
            'selected'   => (array)request()->query('attributes', []),
        ])
    @endif
@endforeach
