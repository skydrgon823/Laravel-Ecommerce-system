<?php

namespace Botble\SimpleSlider;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('simple_sliders');
        Schema::dropIfExists('simple_slider_items');
    }
}
