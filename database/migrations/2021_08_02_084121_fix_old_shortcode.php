<?php

use Botble\Page\Models\Page;
use Illuminate\Database\Migrations\Migration;

class FixOldShortcode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Page::get() as $page) {
            $page->content = str_replace('[site-features][/site-features]', '[site-features icon1="general/icon-truck.png" title1="Free Shipping" subtitle1="Orders $50 or more" icon2="general/icon-purchase.png" title2="Free Returns" subtitle2="Within 30 days" icon3="general/icon-bag.png" title3="Get 20% Off 1 Item" subtitle3="When you sign up" icon4="general/icon-operator.png" title4="Support Center" subtitle4="24/7 amazing services"][/site-features]', $page->content);
            $page->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
