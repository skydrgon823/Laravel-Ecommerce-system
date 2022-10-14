@for ($i = 1; $i < 5; $i++)
    <div class="form-group">
        <label class="control-label">{{ __('Icon :number', ['number' => $i]) }}</label>
        {!! Form::mediaImage('icon' . $i, Arr::get($attributes, 'icon' . $i)) !!}
    </div>

    <div class="form-group">
        <label class="control-label">{{ __('Title :number', ['number' => $i]) }}</label>
        <input type="text" name="title{{ $i }}" value="{{ Arr::get($attributes, 'title' . $i) }}" class="form-control" placeholder="{{ __('Title :number', ['number' => $i]) }}">
    </div>

    <div class="form-group">
        <label class="control-label">{{ __('Subtitle :number', ['number' => $i]) }}</label>
        <input type="text" name="subtitle{{ $i }}" value="{{ Arr::get($attributes, 'subtitle' . $i) }}" class="form-control"
               placeholder="{{ __('Subtitle :number', ['number' => $i]) }}">
    </div>
@endfor
