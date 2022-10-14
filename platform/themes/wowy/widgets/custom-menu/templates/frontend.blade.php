<div class="col-lg-2 col-md-3">
    <h5 class="widget-title mb-30 wow fadeIn animated">{{ $config['name'] }}</h5>
    {!!
        Menu::generateMenu(['slug' => $config['menu_id'], 'options' => ['class' => 'footer-list wow fadeIn animated mb-sm-5 mb-md-0']])
    !!}
</div>
