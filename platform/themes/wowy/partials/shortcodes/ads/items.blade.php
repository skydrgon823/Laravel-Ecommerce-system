@if ($keys->count())
    <section class="banners pt-60">
        <div class="container">
            <div class="row">
                @foreach ($keys as $key)
                    <div class="col-md-{{ 12 / $keys->count() }}">
                        {!! display_ad($key) !!}
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
