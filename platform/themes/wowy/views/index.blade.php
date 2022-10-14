@php Theme::layout('homepage'); @endphp

<div class="container">
    <div style="margin: 40px 0;">
        <h4 style="color: #f00; margin-bottom: 15px;">You need to setup your homepage first!</h4>

        <p><strong>1. Go to Admin -> Plugins then activate all plugins.</strong></p>
        <p><strong>2. Go to Admin -> Pages and create a page:</strong></p>

        <div style="margin: 20px 0;">
            <div>- Content:</div>
            <div style="border: 1px solid rgba(0,0,0,.1); padding: 10px; margin-top: 10px;">
                <div>[simple-slider key="home-slider-1"][/simple-slider]</div>
                {{-- <div>[site-features icon1="general/icon-truck.png" title1="Free Shipping" subtitle1="Orders $50 or more" icon2="general/icon-purchase.png" title2="Free Returns" subtitle2="Within 30 days" icon3="general/icon-bag.png" title3="Get 20% Off 1 Item" subtitle3="When you sign up" icon4="general/icon-operator.png" title4="Support Center" subtitle4="24/7 amazing services"][/site-features]</div> --}}
                <div>[featured-product-categories title="Top Categories"][/featured-product-categories]</div>
                <div>[product-collections title="Exclusive Products"][/product-collections]</div>
                <div>[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]</div>
                <div>[featured-products title="Featured products"][/featured-products]</div>
                <div>[flash-sale show_popup="yes"][/flash-sale]</div>
                <div>[featured-brands title="Featured Brands"][/featured-brands]</div>
                <div>[product-category-products category_id="17"][/product-category-products]</div>
                <div>[featured-news title="Visit Our Blog"][/featured-news]</div>
            </div>
            <br>
            <div>- Template: <strong>Homepage</strong>.</div>
        </div>

        <p><strong>3. Then go to Admin -> Appearance -> Theme options -> Page to set your homepage.</strong></p>
    </div>
</div>
