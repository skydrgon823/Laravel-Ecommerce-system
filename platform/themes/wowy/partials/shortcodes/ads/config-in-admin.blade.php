@for ($i = 1; $i < 5; $i++)
    <label class="control-label">Ad {{ $i }}</label>
    <div class="ui-select-wrapper form-group">
        <select name="ads_{{ $i }}" class="form-control ui-select">
            <option value="">{{ __('-- select --') }}</option>
            @foreach($ads as $ad)
                <option value="{{ $ad->key }}" @if ($ad->key == Arr::get($attributes, 'ads_' . $i)) selected @endif>{{ $ad->name }}</option>
            @endforeach
        </select>
        <svg class="svg-next-icon svg-next-icon-size-16">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
        </svg>
    </div>
@endfor
