@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
<div>
    <div class="one-row-actions">
        <div class="flexbox-grid">
            <div class="flexbox-content">
                <div class="body">
                    <div class="box-wrap-emptyTmpl text-center col-12">
                        <h1 class="mt20 mb20 ws-nm font-size-emptyDisplayTmpl">{{ trans('plugins/ecommerce::discount.intro.title') }}</h1>
                        <p class="text-info-displayTmpl">{{ trans('plugins/ecommerce::discount.intro.description') }}</p>
                        <div class="empty-displayTmpl-pdtop">
                            <div class="empty-displayTmpl-image">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1" id="Layer_1" viewBox="0 0 512.5 270"><style>
                                        .st0 {
                                            fill: #eef0f3;
                                        }

                                        .st1 {
                                            fill: #f7f8fc;
                                        }

                                        .st2 {
                                            opacity: .4;
                                        }

                                        .st3 {
                                            clip-path: url(#SVGID_2_);
                                        }

                                        .st4 {
                                            fill: none;
                                            stroke: #e0e7ef;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st5 {
                                            fill: #fff;
                                        }

                                        .st6 {
                                            opacity: .1;
                                            fill: #d0d5e8;
                                        }

                                        .st7 {
                                            fill: #867c99;
                                        }

                                        .st10, .st8, .st9 {
                                            fill: #fff;
                                            stroke: #e0e7ef;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st10, .st9 {
                                            stroke: #d6dfe2;
                                            stroke-width: 1.8189;
                                        }

                                        .st10 {
                                            stroke: #ffdb98;
                                        }

                                        .st11 {
                                            fill: #d6dfe2;
                                        }

                                        .st12 {
                                            fill: #fff;
                                            stroke: #d6dfe2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st12, .st13, .st14 {
                                            stroke-width: 2;
                                        }

                                        .st13 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                            fill: #fff;
                                            stroke: #ffdb98;
                                        }

                                        .st14 {
                                            fill: #5f5371;
                                            stroke: #d0d5e8;
                                        }

                                        .st14, .st15, .st16 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st15 {
                                            stroke-width: 2;
                                            fill: #f7f8fc;
                                            stroke: #5f5371;
                                        }

                                        .st16 {
                                            fill: none;
                                            stroke: #e0e7ef;
                                        }

                                        .st16, .st17, .st18 {
                                            stroke-width: 2;
                                        }

                                        .st17 {
                                            stroke: #5f5371;
                                            stroke-miterlimit: 10;
                                            fill: #babad5;
                                        }

                                        .st18 {
                                            fill: #d0d5e8;
                                        }

                                        .st18, .st19, .st20, .st21 {
                                            stroke: #5f5371;
                                            stroke-miterlimit: 10;
                                        }

                                        .st19 {
                                            stroke-width: 2;
                                            fill: #e7e8f2;
                                        }

                                        .st20, .st21 {
                                            fill: #867b99;
                                        }

                                        .st21 {
                                            fill: #d0d5e8;
                                            fill-opacity: .1;
                                            stroke: #e0e7ef;
                                            stroke-width: 2;
                                        }

                                        .st22 {
                                            fill: #e0e7ef;
                                        }

                                        .st23, .st24 {
                                            fill: #fff;
                                            stroke: #e0e7ef;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st24 {
                                            fill: none;
                                        }

                                        .st25, .st26 {
                                            fill: #e7e8f2;
                                        }

                                        .st26 {
                                            stroke: #e0e7ef;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st27 {
                                            opacity: .1;
                                        }

                                        .st28 {
                                            fill: #d0d5e8;
                                        }

                                        .st29 {
                                            fill: none;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st29, .st30, .st31 {
                                            stroke: #e0e7ef;
                                        }

                                        .st30 {
                                            fill: none;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-dasharray: 3.8978,5.8467;
                                        }

                                        .st31 {
                                            stroke-dasharray: 4.1,6.15;
                                        }

                                        .st31, .st32, .st33, .st34, .st35 {
                                            fill: none;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st32 {
                                            stroke: #e0e7ef;
                                            stroke-dasharray: 4.2154,6.3231;
                                        }

                                        .st33, .st34, .st35 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st33 {
                                            stroke-dasharray: 4,6;
                                            stroke: #e0e7ef;
                                        }

                                        .st34, .st35 {
                                            stroke: #766f89;
                                            stroke-width: 2;
                                        }

                                        .st35 {
                                            fill: #766f89;
                                        }

                                        .st36 {
                                            fill: #babad5;
                                            fill-opacity: .8;
                                        }

                                        .st37, .st38, .st39 {
                                            fill: none;
                                            stroke: #d0d5e8;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st38, .st39 {
                                            stroke: #766f89;
                                            stroke-width: 3;
                                        }

                                        .st39 {
                                            fill: #fff;
                                            stroke: #5f5371;
                                            stroke-width: 1.8189;
                                        }

                                        .st40 {
                                            fill: #5f5371;
                                        }

                                        .st41, .st42 {
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                            stroke-linejoin: round;
                                        }

                                        .st41 {
                                            stroke-miterlimit: 10;
                                            fill: #90ccd7;
                                        }

                                        .st42 {
                                            fill: #d0ecf0;
                                        }

                                        .st42, .st43, .st44 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st43 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            fill: none;
                                            stroke: #90ccd7;
                                        }

                                        .st44 {
                                            fill: #fff;
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                        }

                                        .st45, .st46, .st47 {
                                            fill: none;
                                            stroke: #a1c083;
                                            stroke-width: 3;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st46, .st47 {
                                            stroke-dasharray: 9.6223,11.5468;
                                        }

                                        .st47 {
                                            stroke-dasharray: 9.2794,11.1353;
                                        }

                                        .st48, .st49, .st50 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st48 {
                                            fill: none;
                                            stroke: #a1c083;
                                            stroke-width: 2;
                                        }

                                        .st49, .st50 {
                                            stroke-linecap: round;
                                        }

                                        .st49 {
                                            stroke-linejoin: round;
                                            fill: none;
                                            stroke: #a1c083;
                                            stroke-width: 3;
                                        }

                                        .st50 {
                                            fill: #5f5371;
                                            stroke: #d0d5e8;
                                            stroke-width: 2;
                                        }

                                        .st51 {
                                            fill: #babad5;
                                        }

                                        .st52, .st53, .st54 {
                                            fill: none;
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                            stroke-miterlimit: 10;
                                        }

                                        .st53, .st54 {
                                            stroke: #e0e7ef;
                                            stroke-width: 2.6617;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st54 {
                                            stroke-width: 1.7745;
                                        }

                                        .st55 {
                                            opacity: .1;
                                            fill: #5f5371;
                                        }

                                        .st56 {
                                            fill: #f8e7dd;
                                        }

                                        .st57, .st58 {
                                            stroke: #5f5371;
                                            stroke-linejoin: round;
                                        }

                                        .st57 {
                                            stroke-linecap: round;
                                            stroke-miterlimit: 10;
                                            fill: none;
                                        }

                                        .st58 {
                                            fill: #babad5;
                                            stroke-width: 2;
                                        }

                                        .st58, .st59, .st60, .st61 {
                                            stroke-linecap: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st59 {
                                            stroke-width: 2;
                                            stroke-linejoin: round;
                                            fill: #5f5371;
                                            stroke: #5f5371;
                                        }

                                        .st60, .st61 {
                                            fill: none;
                                            stroke: #d0d5e8;
                                        }

                                        .st61 {
                                            fill: #fff;
                                        }

                                        .st62, .st63 {
                                            fill: none;
                                            stroke-linejoin: round;
                                        }

                                        .st62 {
                                            stroke: #a1c083;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                        }

                                        .st63 {
                                            stroke-dasharray: 6.1846,8.2461;
                                        }

                                        .st63, .st64, .st65 {
                                            stroke: #a1c083;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                        }

                                        .st64 {
                                            stroke-linejoin: round;
                                            stroke-dasharray: 5.8274,7.7698;
                                            fill: none;
                                        }

                                        .st65 {
                                            fill: #5f5371;
                                            stroke-miterlimit: 10;
                                        }

                                        .st66 {
                                            stroke-dasharray: 6,8;
                                        }

                                        .st66, .st67 {
                                            fill: none;
                                            stroke: #a1c083;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st68 {
                                            fill: #a1c083;
                                        }

                                        .st69, .st70, .st71 {
                                            stroke-linecap: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st69 {
                                            stroke: #fff;
                                            stroke-width: 2;
                                            fill: none;
                                        }

                                        .st70, .st71 {
                                            fill: #fff;
                                            stroke-linejoin: round;
                                        }

                                        .st70 {
                                            stroke-width: 2;
                                            stroke: #645b71;
                                        }

                                        .st71 {
                                            stroke: #d0d5e8;
                                        }

                                        .st72 {
                                            fill: #645b71;
                                        }

                                        .st73, .st74 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st73 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            fill: none;
                                            stroke: #ffdb98;
                                            stroke-width: 1.8189;
                                        }

                                        .st74 {
                                            fill: #fff;
                                            stroke: #5f5371;
                                            stroke-width: 3;
                                        }

                                        .st74, .st75, .st76, .st77 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st75 {
                                            fill: none;
                                            stroke-width: 3;
                                            stroke-miterlimit: 10;
                                            stroke: #ffdb98;
                                        }

                                        .st76, .st77 {
                                            stroke: #645b71;
                                        }

                                        .st76 {
                                            stroke-width: 2;
                                            stroke-miterlimit: 10;
                                            fill: #766f89;
                                        }

                                        .st77 {
                                            fill: #d0d5e8;
                                        }

                                        .st77, .st78, .st79 {
                                            stroke-width: 2;
                                            stroke-miterlimit: 10;
                                        }

                                        .st78 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            fill: none;
                                            stroke: #645b71;
                                        }

                                        .st79 {
                                            fill: #d0d5e8;
                                            stroke: #5f5371;
                                        }

                                        .st79, .st80, .st81, .st82 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st80 {
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                            stroke-miterlimit: 10;
                                            fill: #e7e8f2;
                                        }

                                        .st81, .st82 {
                                            fill: none;
                                        }

                                        .st81 {
                                            stroke-miterlimit: 10;
                                            stroke: #d0d5e8;
                                            stroke-width: 2;
                                        }

                                        .st82 {
                                            stroke: #babad5;
                                            stroke-width: 3;
                                        }

                                        .st82, .st83, .st84 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st83 {
                                            opacity: .2;
                                            fill: #d0d5e8;
                                            stroke: #babad5;
                                        }

                                        .st84 {
                                            fill: none;
                                            stroke: #fff;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st85 {
                                            opacity: .2;
                                            fill: #d0d5e8;
                                        }

                                        .st86 {
                                            fill: none;
                                            stroke: #babad5;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st87 {
                                            fill: #e3eaf1;
                                        }

                                        .st88 {
                                            fill: #d3dce6;
                                        }

                                        .st89, .st90, .st91 {
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st89 {
                                            stroke: #e3eaf1;
                                            stroke-miterlimit: 10;
                                            fill: none;
                                        }

                                        .st90, .st91 {
                                            fill: #eef0f3;
                                        }

                                        .st90 {
                                            stroke-miterlimit: 10;
                                            stroke: #e3eaf1;
                                        }

                                        .st91 {
                                            stroke: #d3dce6;
                                        }

                                        .st91, .st92, .st93, .st94 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st92 {
                                            stroke-width: 3;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            fill: #867b99;
                                            stroke: #5f5371;
                                        }

                                        .st93, .st94 {
                                            fill: #f7f8fc;
                                            stroke: #fff;
                                        }

                                        .st94 {
                                            fill: none;
                                            stroke: #5f5371;
                                            stroke-width: 3;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st95 {
                                            opacity: .1;
                                            fill: #867b99;
                                        }

                                        .st96, .st97 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st96 {
                                            stroke-miterlimit: 10;
                                            fill: #4fb2c4;
                                            stroke: #4fb2c4;
                                            stroke-width: 2;
                                        }

                                        .st97 {
                                            fill: none;
                                            stroke: #fff;
                                            stroke-width: 3;
                                        }

                                        .st100, .st97, .st98, .st99 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st98 {
                                            stroke-linejoin: round;
                                            fill: none;
                                            stroke: #fff;
                                            stroke-width: 3;
                                        }

                                        .st100, .st99 {
                                            fill: #867b99;
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                        }

                                        .st100 {
                                            stroke-width: 3;
                                            stroke-linejoin: round;
                                        }

                                        .st101 {
                                            opacity: .2;
                                            fill: #5f5371;
                                        }

                                        .st102 {
                                            opacity: .6;
                                        }

                                        .st103, .st104 {
                                            fill: #90ccd7;
                                            stroke: #90ccd7;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st104 {
                                            fill: none;
                                            stroke-width: 3;
                                        }

                                        .st105 {
                                            fill: #90ccd7;
                                        }

                                        .st106, .st107 {
                                            stroke: #90ccd7;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st106 {
                                            stroke-miterlimit: 10;
                                            fill: none;
                                        }

                                        .st107 {
                                            fill: #90ccd7;
                                        }

                                        .st107, .st108, .st109 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st108 {
                                            stroke-width: 3;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            fill: #90ccd7;
                                            stroke: #90ccd7;
                                        }

                                        .st109 {
                                            fill: none;
                                            stroke: #ffdb98;
                                        }

                                        .st110, .st111, .st112 {
                                            fill: #e78988;
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st111, .st112 {
                                            fill: #d7696f;
                                        }

                                        .st112 {
                                            stroke-width: 3;
                                        }

                                        .st113, .st114, .st115, .st116 {
                                            fill: #867b99;
                                            stroke-miterlimit: 10;
                                        }

                                        .st113 {
                                            stroke-width: 3;
                                            stroke: #5f5371;
                                        }

                                        .st114, .st115, .st116 {
                                            stroke: #ffdb98;
                                        }

                                        .st115, .st116 {
                                            fill: #5f5371;
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                            stroke-linejoin: round;
                                        }

                                        .st116 {
                                            fill: #fff;
                                            stroke: #e78988;
                                            stroke-width: 3;
                                            stroke-linecap: round;
                                        }

                                        .st117 {
                                            fill: #e78988;
                                        }

                                        .st118 {
                                            fill: #fff;
                                            stroke: #fff;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st119 {
                                            opacity: .4;
                                            fill: #d3dce6;
                                        }

                                        .st120 {
                                            fill: #766f89;
                                        }

                                        .st121 {
                                            fill: #d0ecf0;
                                        }

                                        .st122 {
                                            fill: #867b99;
                                        }

                                        .st123 {
                                            fill: #fcb663;
                                        }

                                        .st124 {
                                            fill: #ffdb98;
                                        }

                                        .st125 {
                                            fill: #fff;
                                            stroke: #fcb663;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st126 {
                                            opacity: .2;
                                        }

                                        .st127, .st128 {
                                            fill: none;
                                            stroke: #e3eaf1;
                                            stroke-width: 3;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st128 {
                                            fill: #e3eaf1;
                                            stroke-width: 2;
                                        }

                                        .st129, .st130, .st131 {
                                            fill: none;
                                            stroke: #f7f8fc;
                                            stroke-width: 3;
                                            stroke-miterlimit: 10;
                                        }

                                        .st130, .st131 {
                                            stroke: #d3dce6;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st131 {
                                            stroke: #f7f8fc;
                                            stroke-width: 2;
                                        }

                                        .st132, .st133 {
                                            stroke: #d3dce6;
                                            stroke-miterlimit: 10;
                                        }

                                        .st132 {
                                            stroke-width: 2;
                                            fill: #e3eaf1;
                                        }

                                        .st133 {
                                            fill: #f7f8fc;
                                        }

                                        .st134 {
                                            fill: none;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st134, .st135, .st136 {
                                            stroke: #d3dce6;
                                            stroke-miterlimit: 10;
                                        }

                                        .st135 {
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            fill: #e3eaf1;
                                        }

                                        .st136 {
                                            fill: #d3dce6;
                                        }

                                        .st136, .st137 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st137, .st138, .st139 {
                                            fill: none;
                                            stroke: #e3eaf1;
                                            stroke-width: 2;
                                        }

                                        .st138 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-dasharray: 4.0119,6.0178;
                                        }

                                        .st139 {
                                            stroke-dasharray: 3.4211,5.1317;
                                        }

                                        .st139, .st140, .st141, .st142 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st140 {
                                            fill: #f7f8fc;
                                            stroke: #d3dce6;
                                            stroke-miterlimit: 10;
                                        }

                                        .st141, .st142 {
                                            stroke-width: 2;
                                        }

                                        .st141 {
                                            stroke-miterlimit: 10;
                                            fill: #f7f8fc;
                                            stroke: #d3dce6;
                                        }

                                        .st142 {
                                            fill: none;
                                            stroke: #e3eaf1;
                                            stroke-dasharray: 4.2117,6.3176;
                                        }

                                        .st143, .st144 {
                                            fill: none;
                                            stroke-miterlimit: 10;
                                        }

                                        .st143 {
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-dasharray: 4,6;
                                            stroke: #e3eaf1;
                                        }

                                        .st144 {
                                            stroke: #d0d5e8;
                                        }

                                        .st145, .st146 {
                                            fill: none;
                                            stroke: #a1c083;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-dasharray: 5.7355,7.6473;
                                        }

                                        .st146 {
                                            stroke-dasharray: 6.7526,9.0035;
                                        }

                                        .st147, .st148 {
                                            fill: none;
                                            stroke: #d0d5e8;
                                            stroke-miterlimit: 10;
                                        }

                                        .st147 {
                                            stroke-linecap: round;
                                            stroke-width: 3;
                                        }

                                        .st148 {
                                            stroke-width: 10;
                                        }

                                        .st149, .st150 {
                                            stroke-width: 2;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st149 {
                                            stroke-linecap: round;
                                            fill: #d7696f;
                                            stroke: #645b71;
                                        }

                                        .st150 {
                                            fill: none;
                                            stroke: #d7696f;
                                        }

                                        .st151 {
                                            fill-rule: evenodd;
                                            clip-rule: evenodd;
                                            fill: #867b99;
                                        }

                                        .st152 {
                                            fill: #867b99;
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st153, .st154, .st155 {
                                            stroke: #a1c083;
                                            stroke-miterlimit: 10;
                                        }

                                        .st153 {
                                            stroke-linecap: round;
                                            fill: none;
                                            stroke-width: 3;
                                        }

                                        .st154, .st155 {
                                            fill: #ccdbb0;
                                            stroke-width: 2;
                                        }

                                        .st155 {
                                            fill: none;
                                            stroke-linecap: round;
                                        }

                                        .st156 {
                                            fill: none;
                                            stroke: #fcb663;
                                            stroke-width: 3;
                                            stroke-linejoin: round;
                                        }

                                        .st156, .st157, .st158 {
                                            stroke-linecap: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st157 {
                                            stroke-linejoin: round;
                                            fill: #f7f8fc;
                                            stroke: #d3dce6;
                                            stroke-width: 3;
                                        }

                                        .st158 {
                                            fill: none;
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                        }

                                        .st159 {
                                            fill: #695e7d;
                                        }

                                        .st160, .st161, .st162 {
                                            fill: #695e7d;
                                            stroke: #5f5371;
                                            stroke-width: 3;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st161, .st162 {
                                            fill: none;
                                            stroke-width: 2;
                                        }

                                        .st162 {
                                            fill: #a1c083;
                                        }

                                        .st163 {
                                            fill: #ccdbb0;
                                        }

                                        .st164 {
                                            fill: #eef0f3;
                                            stroke: #e0e7ef;
                                            stroke-width: 3;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st165, .st166, .st167 {
                                            fill: #5f5371;
                                            stroke: #d0d5e8;
                                            stroke-miterlimit: 10;
                                        }

                                        .st166, .st167 {
                                            fill: #babad5;
                                            stroke: #5f5371;
                                        }

                                        .st167 {
                                            fill: #d0d5e8;
                                        }

                                        .st168, .st169 {
                                            fill: none;
                                            stroke: #a1c083;
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-dasharray: 5.5955,7.4607;
                                        }

                                        .st169 {
                                            stroke-dasharray: 7.3834,9.8446;
                                        }

                                        .st170, .st171 {
                                            fill: none;
                                            stroke: #d0d5e8;
                                            stroke-miterlimit: 10;
                                        }

                                        .st170 {
                                            stroke-linecap: round;
                                            stroke-width: 2;
                                        }

                                        .st171 {
                                            stroke-width: 6;
                                        }

                                        .st172 {
                                            stroke: #e0e7ef;
                                        }

                                        .st172, .st173, .st174, .st175, .st176 {
                                            fill: none;
                                            stroke-miterlimit: 10;
                                        }

                                        .st173 {
                                            stroke-width: 1.819;
                                            stroke-linejoin: round;
                                            stroke: #d7696f;
                                        }

                                        .st174, .st175, .st176 {
                                            stroke: #eef0f3;
                                        }

                                        .st175, .st176 {
                                            fill: #e7e8f2;
                                            stroke: #d3dce6;
                                        }

                                        .st176 {
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st177 {
                                            fill: #d7696f;
                                        }

                                        .st178 {
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st178, .st179, .st180 {
                                            fill: none;
                                            stroke: #d3dce6;
                                            stroke-miterlimit: 10;
                                        }

                                        .st180 {
                                            fill: #e3eaf1;
                                        }

                                        .st181, .st182, .st183, .st184 {
                                            stroke: #d3dce6;
                                            stroke-linejoin: round;
                                        }

                                        .st181 {
                                            stroke-miterlimit: 10;
                                            fill: #f7f8fc;
                                        }

                                        .st182, .st183, .st184 {
                                            fill: none;
                                            stroke-linecap: round;
                                        }

                                        .st183, .st184 {
                                            stroke-dasharray: 2.0824,4.1647;
                                        }

                                        .st184 {
                                            stroke-dasharray: 2.0535,4.1071;
                                        }

                                        .st185, .st186 {
                                            stroke: #d3dce6;
                                            stroke-miterlimit: 10;
                                        }

                                        .st185 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-dasharray: 2,4;
                                            fill: none;
                                        }

                                        .st186 {
                                            fill: #fff;
                                        }

                                        .st187 {
                                            opacity: .2;
                                            fill: #d0d5e8;
                                            stroke: #d3dce6;
                                        }

                                        .st187, .st188, .st189 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st188 {
                                            opacity: .1;
                                            fill: #d3dce6;
                                            stroke: #d3dce6;
                                        }

                                        .st189 {
                                            fill: #fcb663;
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                        }

                                        .st189, .st190, .st191 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st190 {
                                            stroke: #5f5371;
                                            stroke-width: 3;
                                            stroke-miterlimit: 10;
                                            fill: #d0d5e8;
                                        }

                                        .st191 {
                                            fill: #766f89;
                                        }

                                        .st191, .st192, .st193 {
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                            stroke-miterlimit: 10;
                                        }

                                        .st192 {
                                            stroke-linecap: round;
                                            fill: #fff;
                                        }

                                        .st193 {
                                            fill: #766f89;
                                        }

                                        .st194, .st195 {
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st194 {
                                            stroke-width: 2;
                                            fill: #e7e8f2;
                                            stroke: #645b71;
                                        }

                                        .st195 {
                                            fill: none;
                                            stroke: #e7e8f2;
                                        }

                                        .st196 {
                                            fill: #fcb764;
                                        }

                                        .st197, .st198, .st199, .st200, .st201, .st202 {
                                            fill: #d0ecf0;
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                            stroke-miterlimit: 10;
                                        }

                                        .st198, .st199, .st200, .st201, .st202 {
                                            fill: #90ccd7;
                                        }

                                        .st199, .st200, .st201, .st202 {
                                            fill: #4fb2c4;
                                        }

                                        .st200, .st201, .st202 {
                                            fill: #e5ebd5;
                                        }

                                        .st201, .st202 {
                                            fill: #ccdbb0;
                                        }

                                        .st202 {
                                            fill: #a1c083;
                                        }

                                        .st203, .st204, .st205, .st206, .st207, .st208, .st209, .st210 {
                                            fill: #e1eef9;
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                            stroke-miterlimit: 10;
                                        }

                                        .st204, .st205, .st206, .st207, .st208, .st209, .st210 {
                                            fill: #b8d4ea;
                                        }

                                        .st205, .st206, .st207, .st208, .st209, .st210 {
                                            fill: #619ed5;
                                        }

                                        .st206, .st207, .st208, .st209, .st210 {
                                            fill: #feefd1;
                                        }

                                        .st207, .st208, .st209, .st210 {
                                            fill: #ffdb98;
                                        }

                                        .st208, .st209, .st210 {
                                            fill: #fcb663;
                                        }

                                        .st209, .st210 {
                                            fill: #f8e7dd;
                                        }

                                        .st210 {
                                            fill: #e78988;
                                        }

                                        .st211 {
                                            fill: #5f5371;
                                            stroke-width: 2;
                                        }

                                        .st211, .st212, .st213 {
                                            stroke: #5f5371;
                                            stroke-miterlimit: 10;
                                        }

                                        .st212 {
                                            stroke-width: 2;
                                            fill: #d7696f;
                                        }

                                        .st213 {
                                            fill: #fff;
                                        }

                                        .st214, .st215, .st216 {
                                            stroke: #d0d5e8;
                                            stroke-miterlimit: 10;
                                        }

                                        .st214 {
                                            stroke-width: 2;
                                            stroke-linejoin: round;
                                            fill: #fff;
                                        }

                                        .st215, .st216 {
                                            fill: #ffdb98;
                                        }

                                        .st216 {
                                            fill: #fcb663;
                                            stroke: #5f5371;
                                        }

                                        .st216, .st217, .st218 {
                                            stroke-width: 2;
                                            stroke-linejoin: round;
                                        }

                                        .st217 {
                                            stroke-miterlimit: 10;
                                            fill: #766f89;
                                            stroke: #5f5371;
                                        }

                                        .st218 {
                                            fill: none;
                                            stroke: #d0d5e8;
                                        }

                                        .st218, .st219, .st220 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st219 {
                                            stroke-width: 2;
                                            stroke-linejoin: round;
                                            fill: #f7f8fc;
                                            stroke: #5f5371;
                                        }

                                        .st220 {
                                            fill: none;
                                            stroke: #d0d5e8;
                                        }

                                        .st220, .st221, .st222 {
                                            stroke-linejoin: round;
                                        }

                                        .st221 {
                                            stroke-width: 2;
                                            stroke-miterlimit: 10;
                                            fill: none;
                                            stroke: #5f5371;
                                        }

                                        .st222 {
                                            fill: #f7f8fc;
                                            stroke: #e7e8f2;
                                        }

                                        .st222, .st223 {
                                            stroke-width: 2;
                                            stroke-linecap: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st223, .st224, .st225, .st226, .st227 {
                                            fill: none;
                                            stroke: #e7e8f2;
                                            stroke-linejoin: round;
                                        }

                                        .st224 {
                                            stroke-miterlimit: 10;
                                        }

                                        .st225, .st226, .st227 {
                                            stroke-linecap: round;
                                        }

                                        .st226, .st227 {
                                            stroke-dasharray: 2.1075,4.215;
                                        }

                                        .st227 {
                                            stroke-dasharray: 1.9183,3.8366;
                                        }

                                        .st228 {
                                            fill: none;
                                            stroke-dasharray: 2,4;
                                        }

                                        .st228, .st229 {
                                            stroke: #e7e8f2;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-miterlimit: 10;
                                        }

                                        .st229, .st230 {
                                            fill: #fff;
                                        }

                                        .st230, .st231, .st232, .st233, .st234 {
                                            stroke: #e7e8f2;
                                            stroke-miterlimit: 10;
                                        }

                                        .st231 {
                                            opacity: .2;
                                            fill: #d0d5e8;
                                        }

                                        .st232, .st233, .st234 {
                                            fill: #f7f8fc;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                        }

                                        .st233, .st234 {
                                            fill: #fff;
                                            stroke: #5f5371;
                                            stroke-width: 2;
                                        }

                                        .st234 {
                                            fill: none;
                                            stroke: #fcb663;
                                        }

                                        .st235 {
                                            fill: #d1d3d4;
                                        }

                                        .st236 {
                                            fill: none;
                                            stroke: #d1d3d4;
                                            stroke-miterlimit: 10;
                                        }
                                    </style>
                                    <path class="st119" d="M489.4 199.6H255.7v-4.5h237.7v.4c0 2.3-1.8 4.1-4 4.1z"></path>
                                    <path class="st88" d="M493.1 195.8H253.3c-2 0-3.6-1.6-3.6-3.6V24.6c0-2 1.6-3.6 3.6-3.6h239.8c2 0 3.6 1.6 3.6 3.6v167.5c0 2.1-1.6 3.7-3.6 3.7zM253.3 23c-.9 0-1.6.7-1.6 1.6v167.5c0 .9.7 1.6 1.6 1.6h239.8c.9 0 1.6-.7 1.6-1.6V24.6c0-.9-.7-1.6-1.6-1.6H253.3z"></path>
                                    <path class="st88" d="M487.7 188.5H258.6V28.4h229.1v160.1zm-228.1-1h227.1V29.4H259.6v158.1z"></path>
                                    <path class="st88" d="M287.4 49c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm22.4-1.6c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm8.2 21.2c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-34.7-6.1c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm38.8 7.2c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm4 13.8c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-24.5-44c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-26.5-1.6c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zM271 49.8c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-4.1 17.5c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm28.6 61.3c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-20.4-74.8c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm18.4-8.7c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm14.3 7.5c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm3.5 62.3c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-19.9-75.4c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm157.3 18.2c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm2-15.9c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm0 25c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-147 17.5c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zM319 72.7c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-41.9 6c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm0 11.2c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm3.6-6.4c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-3.6-11.9c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm51.1 27.8c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-26.6 3.2c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm24.5-10.8c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-47.9 4.8c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm172.5-10.7c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm29.6-38.1c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-4.1 13.5c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zM468 36.7c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-17.3 15.9c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm9.2 13.5c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm16.3 4c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zM468 50.2c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm2.1 27.3c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm0 11.2c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm0-18.3c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm1 25c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-20.5 20.4c1 0 1-1.5 0-1.5-.9 0-1 1.5 0 1.5zm22.5-1.5c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm4.1 11.9c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-30.7 3.2c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm24.5 14.3c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-6.1-37.4c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-6.1-4.1c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-12.3 35.9c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm16.4-13.5c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-4.1 7.9c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm16.3 2.4c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-8.1 26.2c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-2.1-7.9c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-24.5 13.5c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm22.5 1.6c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm2 11.1c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-23.5-5.9c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-11.1-2.8c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-22.5 1.6c1 0 1-1.5 0-1.5-.9 0-1 1.5 0 1.5zm-26.5-8.8c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-40.9 2.4c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-34.7-33.4c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-14.3-11.1c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm38.8-4c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-24.5 24.7c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zM283.3 154c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm24.5 4.8c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm55.1 11.1c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-42.9 0c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm42.9 6.4c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm53.1 3.9c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm26.5 1.6c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm37.8-27.8c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-29.6-1.6c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-32.7 9.5c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-22.4 7.2c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-18.4 8.7c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-12.3-13.5c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-34.7 12c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm24.5 4.7c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-32.6 0c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-20.5 0c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm0-7.1c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm8.2 3.1c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-22.4-11.1c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-18.4-4c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm4.1 10.4c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm12.2 0c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-18.4 5.5c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm10.2 4.8c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-10.2-39.7c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm28.6 3.9c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm4.1 6.4c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm26.5-6.4c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm132.7-25.4c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-144.9-15.9c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-30.6.8c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-8.2 8c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm34.7-5.6c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-34.7 24.6c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm36.8-8.7c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm6.1 10.3c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm12.2 21.5c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm-45.9-17.9c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm37.8 23.8c1 0 1-1.5 0-1.5s-1 1.5 0 1.5zm-55.2-38.5c1 0 1-1.5 0-1.5-.9 0-.9 1.5 0 1.5zm75.6 52.7c1 0 1-1.5 0-1.5s-1 1.5 0 1.5z"></path>
                                    <path class="st119" d="M259.1 28.9h228.1v3.6H259.1z"></path>
                                    <path class="st88" d="M45.1 141H9.7c-.6 0-1-.4-1-1s.4-1 1-1h35.4c.6 0 1 .4 1 1s-.4 1-1 1zM63 120.3H16.4c-.6 0-1-.4-1-1s.4-1 1-1H63c.6 0 1 .4 1 1s-.4 1-1 1zm159.3 0H106c-.6 0-1-.4-1-1s.4-1 1-1h116.3c.6 0 1 .4 1 1s-.5 1-1 1z"></path>
                                    <path class="st88" d="M29.8 141.1c-.6 0-1-.4-1-1v-20.9c0-.6.4-1 1-1s1 .4 1 1v20.9c0 .6-.4 1-1 1zm50 21.1H8.1c-.6 0-1-.4-1-1s.4-1 1-1h71.7c.6 0 1 .4 1 1s-.5 1-1 1zm21.8-63.6H25.2c-.6 0-1-.4-1-1s.4-1 1-1h76.4c.6 0 1 .4 1 1s-.4 1-1 1zm148.7 0H124c-.6 0-1-.4-1-1s.4-1 1-1h126.2c.6 0 1 .4 1 1s-.4 1-.9 1z"></path>
                                    <path class="st88" d="M31.7 184.2c-.6 0-1-.4-1-1v-20.9c0-.6.4-1 1-1s1 .4 1 1v20.9c0 .6-.4 1-1 1zm26.4-64.4c-.6 0-1-.4-1-1V97.6c0-.6.4-1 1-1s1 .4 1 1v21.2c0 .6-.4 1-1 1z"></path>
                                    <path class="st88" d="M70.8 184.3H8.1c-.6 0-1-.4-1-1s.4-1 1-1h62.7c.6 0 1 .4 1 1s-.4 1-1 1zM217.1 141h-57.8c-.6 0-1-.4-1-1s.4-1 1-1h57.8c.6 0 1 .4 1 1s-.4 1-1 1zm-48-21.2c-.6 0-1-.4-1-1V97.6c0-.6.4-1 1-1s1 .4 1 1v21.2c0 .6-.5 1-1 1zm-27.7-21.2c-.6 0-1-.4-1-1V77.2c0-.6.4-1 1-1s1 .4 1 1v20.4c0 .6-.5 1-1 1zm82.5 63.6h-64.6c-.6 0-1-.4-1-1s.4-1 1-1h64.6c.6 0 1 .4 1 1s-.5 1-1 1z"></path>
                                    <path class="st88" d="M169.1 161.7c-.6 0-1-.4-1-1v-20c0-.6.4-1 1-1s1 .4 1 1v20c0 .6-.5 1-1 1zm26.6-20.6c-.6 0-1-.4-1-1v-20.9c0-.6.4-1 1-1s1 .4 1 1v20.9c0 .6-.5 1-1 1zm-43-63.3H37.4c-.6 0-1-.4-1-1s.4-1 1-1h115.3c.6 0 1 .4 1 1s-.4 1-1 1zm97.9 0h-61.9c-.6 0-1-.4-1-1s.4-1 1-1h61.9c.6 0 1 .4 1 1s-.4 1-1 1z"></path>
                                    <path class="st88" d="M86.2 98.6c-.6 0-1-.4-1-1V76.8c0-.6.4-1 1-1s1 .4 1 1v20.9c0 .5-.4.9-1 .9zM69.4 56.1H40.7c-.6 0-1-.4-1-1s.4-1 1-1h28.6c.6 0 1 .4 1 1s-.4 1-.9 1zm163.6 0H79.4c-.6 0-1-.4-1-1s.4-1 1-1H233c.6 0 1 .4 1 1s-.4 1-1 1z"></path>
                                    <path class="st88" d="M58.1 77.4c-.6 0-1-.4-1-1v-21c0-.6.4-1 1-1s1 .4 1 1v21c0 .5-.4 1-1 1zm57.3-1.4c-.6 0-1-.4-1-1V56.3c0-.6.4-1 1-1s1 .4 1 1V75c0 .5-.5 1-1 1zm53.7-4.6c-.6 0-1-.4-1-1v-14c0-.6.4-1 1-1s1 .4 1 1v13.9c0 .6-.5 1.1-1 1.1zm26.6 26.2c-.6 0-1-.4-1-1V76.8c0-.6.4-1 1-1s1 .4 1 1v19.9c0 .5-.5.9-1 .9zm-54.3-41.4c-.6 0-1-.4-1-1V34.8c0-.6.4-1 1-1s1 .4 1 1v20.4c0 .6-.5 1-1 1z"></path>
                                    <path class="st88" d="M241.4 35.4H71.7c-.6 0-1-.4-1-1s.4-1 1-1h169.7c.6 0 1 .4 1 1s-.4 1-1 1z"></path>
                                    <path class="st88" d="M86.2 56.2c-.6 0-1-.4-1-1V34.4c0-.6.4-1 1-1s1 .4 1 1v20.9c0 .5-.4.9-1 .9zm316 192.7c-.6 0-1-.4-1-1V227c0-.6.4-1 1-1s1 .4 1 1v20.9c0 .5-.5 1-1 1z"></path>
                                    <path class="st88" d="M502.7 248.2h-113c-.6 0-1-.4-1-1s.4-1 1-1h113c.6 0 1 .4 1 1s-.4 1-1 1z"></path>
                                    <path class="st88" d="M427.5 269.5c-.6 0-1-.4-1-1v-21.2c0-.6.4-1 1-1s1 .4 1 1v21.2c0 .6-.5 1-1 1zm74.2-42.1h-100c-.6 0-1-.4-1-1s.4-1 1-1h100c.6 0 1 .4 1 1s-.4 1-1 1z"></path>
                                    <path class="st88" d="M455.6 248.3c-.6 0-1-.4-1-1v-20.9c0-.6.4-1 1-1s1 .4 1 1v20.9c0 .5-.5 1-1 1zM427.5 227c-.6 0-1-.4-1-1v-21c0-.6.4-1 1-1s1 .4 1 1v21c0 .6-.5 1-1 1zm-229-213.3H99.6c-.6 0-1-.4-1-1s.4-1 1-1h98.9c.6 0 1 .4 1 1s-.4 1-1 1zm152.4 0H217.1c-.6 0-1-.4-1-1s.4-1 1-1h133.8c.6 0 1 .4 1 1s-.4 1-1 1z"></path>
                                    <path class="st88" d="M115.4 33.6c-.6 0-1-.4-1-1V13.9c0-.6.4-1 1-1s1 .4 1 1v18.7c0 .5-.5 1-1 1zm53.7 1.4c-.6 0-1-.4-1-1V14.1c0-.6.4-1 1-1s1 .4 1 1V34c0 .5-.5 1-1 1zm53.7-1.4c-.6 0-1-.4-1-1V13.9c0-.6.4-1 1-1s1 .4 1 1v18.7c0 .5-.5 1-1 1zM276.5 23c-.6 0-1-.4-1-1v-7.9c0-.6.4-1 1-1s1 .4 1 1V22c0 .5-.4 1-1 1zm51.3 0c-.6 0-1-.4-1-1v-8c0-.6.4-1 1-1s1 .4 1 1v8c0 .6-.5 1-1 1zM195.7 56.2c-.6 0-1-.4-1-1V34.4c0-.6.4-1 1-1s1 .4 1 1v20.9c0 .5-.5.9-1 .9zM303.3 13c-.6 0-1-.4-1-1V1c0-.6.4-1 1-1s1 .4 1 1v11c0 .6-.4 1-1 1zm-51.2 0c-.6 0-1-.4-1-1V1c0-.6.4-1 1-1s1 .4 1 1v11c0 .6-.4 1-1 1zm-110.7 0c-.6 0-1-.4-1-1V1c0-.6.4-1 1-1s1 .4 1 1v11c0 .6-.5 1-1 1zm-26 106.5c-.6 0-1-.4-1-1v-11.7c0-.6.4-1 1-1s1 .4 1 1v11.7c0 .6-.5 1-1 1zm107.4-1c-.6 0-1-.4-1-1V98.9c0-.6.4-1 1-1s1 .4 1 1v18.7c0 .5-.5.9-1 .9zm0-41.9c-.6 0-1-.4-1-1V56.9c0-.6.4-1 1-1s1 .4 1 1v18.7c0 .5-.5 1-1 1zm248.3 128.6h-83.4c-.6 0-1-.4-1-1s.4-1 1-1h83.4c.6 0 1 .4 1 1s-.4 1-1 1zm13.1 21.8c-.6 0-1-.4-1-1v-9c0-.6.4-1 1-1s1 .4 1 1v9c0 .6-.4 1-1 1zm0 42.5c-.6 0-1-.4-1-1v-21.2c0-.6.4-1 1-1s1 .4 1 1v21.2c0 .6-.4 1-1 1z"></path>
                                    <g><path class="st119" d="M340.3 164h93c4.4 0 8-3.6 8-8v-3l-2 3.4c-1.4 2.4-4.1 3.9-6.9 3.9h-92.1v3.7z"></path><path class="st87" d="M429.2 160.3h-89.3c-4.6 0-8.3-3.7-8.3-8.3v-.5h91.1l.8 2.1c1 2.7 2.9 5 5.4 6.5l.3.2z"></path><path class="st88" d="M431.1 160.8H340c-4.8 0-8.8-3.9-8.8-8.8v-1h91.9l.9 2.4c.9 2.6 2.8 4.8 5.2 6.2l1.9 1.2zm-99-8.7c0 4.3 3.5 7.7 7.8 7.7h87.6c-2-1.5-3.6-3.6-4.5-6l-.6-1.8h-90.3z"></path><path class="st88" d="M432.3 160.8c-4.9 0-9-3.9-9.2-8.7h-83.2V40.2h101.7v111.4c-.1 5.1-4.2 9.2-9.3 9.2zm-91.5-9.7H424v.5c0 4.5 3.7 8.2 8.2 8.2s8.2-3.7 8.2-8.2V41.2h-99.7v109.9z"></path><path class="st88" d="M432.8 161.3h-92.5c-5.4 0-9.7-4.4-9.7-9.7 0-.6.4-1 1-1h7.7V40.7c0-.6.4-1 1-1H441c.6 0 1 .4 1 1v110.9c0 5.1-3.9 9.3-8.9 9.7h-.3zm-91.5-10.7h82.2c.6 0 1 .4 1 1 0 4.3 3.5 7.7 7.7 7.7s7.7-3.5 7.7-7.7V41.7h-98.7v108.9zm-8.6 2c.5 3.8 3.7 6.7 7.7 6.7h86c-2.1-1.6-3.5-4-3.8-6.7h-89.9z"></path><path class="st87" d="M363.2 129.2h34.3v5.8h-34.3zM414.5 114.7h-49c-1.6 0-2.9-1.3-2.9-2.9V66.4c0-1.6 1.3-2.9 2.9-2.9h49c1.6 0 2.9 1.3 2.9 2.9v45.5c0 1.5-1.3 2.8-2.9 2.8z"></path><path class="st5" d="M386.3 104.7c-.2 0-.5 0-.7-.1-.9-.3-1.5-1-1.5-1.9l-.2-2.6c0-.4-.2-.7-.5-.9-.3-.2-.7-.3-1-.2l-2.5.6c-.9.2-1.8-.1-2.3-.9-.5-.7-.6-1.7-.1-2.5l1.3-2.2c.2-.3.2-.7.1-1s-.4-.6-.7-.8l-2.4-1c-.8-.4-1.4-1.2-1.4-2.1s.5-1.7 1.4-2.1l2.4-1c.3-.1.6-.4.7-.8s.1-.7-.1-1l-1.3-2.2c-.5-.8-.4-1.7.1-2.5.5-.7 1.4-1.1 2.3-.9l2.5.6c.3.1.7 0 1-.2.3-.2.5-.5.5-.9l.2-2.6c.1-.9.7-1.7 1.5-1.9.9-.3 1.8 0 2.4.7l1.7 2c.5.5 1.4.5 1.9 0l1.7-2c.6-.7 1.5-.9 2.4-.7.9.3 1.5 1 1.5 1.9l.2 2.6c0 .4.2.7.5.9s.7.3 1 .2l2.5-.6c.9-.2 1.8.1 2.3.9.5.7.6 1.7.1 2.5l-1.3 2.2c-.2.3-.2.7-.1 1 .1.3.4.6.7.8l2.4 1c.8.4 1.4 1.2 1.4 2.1s-.5 1.7-1.4 2.1l-2.4 1c-.3.1-.6.4-.7.8-.1.3-.1.7.1 1l1.3 2.2c.5.8.4 1.7-.1 2.5-.5.7-1.4 1.1-2.3.9L399 99c-.3-.1-.7 0-1 .2-.3.2-.5.5-.5.9l-.2 2.6c-.1.9-.7 1.7-1.5 1.9-.9.3-1.8 0-2.4-.7l-1.7-2c-.5-.5-1.4-.5-1.9 0l-1.7 2c-.5.6-1.1.8-1.8.8zm-3.7-6.7c.5 0 .9.1 1.3.4.5.4.9 1 .9 1.6l.2 2.6c.1.6.5 1 .9 1.1.4.1.9.1 1.3-.4l1.7-2c.9-1 2.5-1 3.4 0l1.7 2c.4.5.9.5 1.3.4.4-.1.8-.5.9-1.1l.2-2.6c.1-.6.4-1.2.9-1.6.5-.4 1.2-.5 1.8-.4l2.5.6c.6.1 1.1-.2 1.3-.5.2-.3.4-.9.1-1.4l-1.3-2.2c-.3-.6-.4-1.2-.2-1.8.2-.6.7-1.1 1.3-1.4l2.4-1c.6-.2.8-.7.8-1.1s-.2-.9-.8-1.1l-2.4-1c-.6-.3-1.1-.8-1.3-1.4-.2-.6-.1-1.3.2-1.8l1.3-2.2c.3-.5.2-1.1-.1-1.4-.2-.3-.7-.6-1.3-.5l-2.5.6c-.6.1-1.3 0-1.8-.4s-.9-1-.9-1.6l-.2-2.6c-.1-.6-.5-1-.9-1.1-.4-.1-.9-.1-1.3.4l-1.7 2c-.9 1-2.5 1-3.4 0l-1.7-2c-.4-.5-.9-.5-1.3-.4-.4.1-.8.5-.9 1.1l-.2 2.6c-.1.6-.4 1.2-.9 1.6-.5.4-1.2.5-1.8.4l-2.5-.6c-.6-.1-1.1.2-1.3.5-.2.3-.4.9-.1 1.4l1.3 2.2c.3.6.4 1.2.2 1.8s-.7 1.1-1.3 1.4l-2.4 1c-.6.2-.8.7-.8 1.1s.2.9.8 1.1l2.4 1c.6.3 1.1.8 1.3 1.4s.1 1.3-.2 1.8l-1.3 2.2c-.3.4-.2 1 .1 1.4.2.3.7.6 1.3.5l2.5-.6h.5z"></path><path class="st5" d="M386 87.5h9.4v1H386zm-3.7 2.9h16.8v1h-16.8z"></path><path class="st87" d="M405.5 137.5c-.3 0-.6-.1-.8-.3-.5-.5-.5-1.2 0-1.7l8.4-8.4c.5-.5 1.2-.5 1.7 0s.5 1.2 0 1.7l-8.4 8.4c-.3.1-.6.3-.9.3z"></path><circle class="st87" cx="412.7" cy="135.9" r="1.8"></circle><circle class="st87" cx="406.5" cy="128.2" r="1.8"></circle><g><path class="st88" d="M424.3 144h-1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h.5v-.5c0-.3.2-.5.5-.5s.5.2.5.5v1c0 .3-.2.5-.5.5zm-5.2 0H417c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm-6.2 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm-6.2 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.3.5-.5.5zm-6.3 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm-6.2 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.3.5-.5.5zm-6.3 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm-6.2 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.3.5-.5.5zm-6.3 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm-6.2 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.3.5-.5.5zm-6.3 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm-6.2 0h-1c-.3 0-.5-.2-.5-.5v-1c0-.3.2-.5.5-.5s.5.2.5.5v.5h.5c.3 0 .5.2.5.5s-.2.5-.5.5zm-1-5.1c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.1c-.3 0-.5-.2-.5-.5V124c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .2-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.1c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.1c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .2-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5V87c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.1c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.1c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .2-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-1c0-.3.2-.5.5-.5h1c.3 0 .5.2.5.5s-.2.5-.5.5h-.5v.5c0 .3-.2.5-.5.5zm63.4-1H417c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm-6.2 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm-6.2 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.3.5-.5.5zm-6.3 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm-6.2 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.3.5-.5.5zm-6.3 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm-6.2 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.3.5-.5.5zm-6.3 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm-6.2 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.3.5-.5.5zm-6.3 0h-2.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.1c.3 0 .5.2.5.5s-.2.5-.5.5zm61.4 1c-.3 0-.5-.2-.5-.5v-.5h-.5c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h1c.3 0 .5.2.5.5v1c0 .3-.2.5-.5.5zm0 80.1c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.1c-.3 0-.5-.2-.5-.5V124c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .2-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.1c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.1c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .2-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5V87c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.1c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.2c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .3-.2.5-.5.5zm0-6.1c-.3 0-.5-.2-.5-.5v-2.1c0-.3.2-.5.5-.5s.5.2.5.5v2.1c0 .2-.2.5-.5.5z"></path></g><path class="st88" d="M418.7 120.3h-2c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2c.3 0 .5.2.5.5s-.2.5-.5.5zm-6 0h-2c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2c.3 0 .5.2.5.5s-.2.5-.5.5zm-6 0h-2c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2c.3 0 .5.2.5.5s-.2.5-.5.5zm-6 0h-2c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2c.3 0 .5.2.5.5s-.2.5-.5.5zm-6 0h-2c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2c.3 0 .5.2.5.5s-.2.5-.5.5zm-6 0h-2c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2c.3 0 .5.2.5.5s-.2.5-.5.5zm-6 0h-2c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2c.3 0 .5.2.5.5s-.2.5-.5.5zm-6 0h-2c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2c.3 0 .5.2.5.5s-.2.5-.5.5zm-6 0h-2c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2c.3 0 .5.2.5.5s-.2.5-.5.5zm-6 0h-.5c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h.5c.3 0 .5.2.5.5s-.2.5-.5.5z"></path></g>
                                    <g><path class="st88" d="M74.3 262.1s-.1 0 0 0c-.7-.1-1.2-.6-1.3-1.2-1.3-20.5 1.8-47.6 5.6-63.7 2.3-9.8 5.9-11.1 17.7-15.3 1.6-.6 3.3-1.2 5.3-1.9 2.4-.9 8.3-3.9 11.3-6.7.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4-3.4 3.3-10 6.4-12 7.2-1.9.7-3.7 1.4-5.3 1.9-11.6 4.2-14.4 5.2-16.4 13.9-3.4 14.6-6.3 38.2-5.8 57.7l8.7-39.4c5.6-23.9 8.5-26 18.8-30.4 1.2-.5 2.4-1 3.8-1.6 11.2-5.1 15.1-9.6 15.2-9.7.4-.4 1-.5 1.4-.1.4.4.5 1 .1 1.4-.2.2-4.2 4.9-15.9 10.2-1.4.6-2.6 1.2-3.8 1.7-9.2 3.9-12 5.1-17.6 29l-9.9 44.7c-.2.5-.7.9-1.3.9z"></path><path class="st141" d="M137.3 125.4l-8.1-1.9c-8.3-1.9-16.6 3.2-18.5 11.5l-5.3 22.9c-.9 3.7-.1 7.3 1.7 10.3 1.8 3 4.8 5.2 8.5 6.1 14.2 3.3 28.5-5.5 31.8-19.8l2.2-9.4c2-8.8-3.5-17.6-12.3-19.7zm7 19l-1.8 8.2c-2.7 12.2-14 19.8-25.2 17.1-2.6-.6-4.6-2.3-5.9-4.6-1.3-2.2-1.7-5-1.1-7.8l4.5-20.2c1.5-6.5 7.5-10.7 13.5-9.2l7.7 1.9c6.1 1.6 9.8 8.1 8.3 14.6z"></path><path class="st5" d="M69.8 162.7s1.4 5.9 9.5 15.8c8.6 10.5 12.2 10.4 5.8 37.8l-10.9 44.5c-.1.3.4.5.5.2 10.2-17.8 19.3-43.2 23.1-59.3 2.5-10.5-.9-12.2-12.7-24.7-1.5-1.6-5-6.1-6.7-9.9"></path><path class="st88" d="M74.5 261.7c-.1 0-.2 0-.3-.1-.4-.1-.6-.5-.5-.9l10.9-44.5c5.6-24.1 3.4-26.7-3.1-34.2-.8-1-1.7-2-2.7-3.2-8.1-9.9-9.5-15.7-9.6-16l1-.2c0 .1 1.5 5.9 9.4 15.6.9 1.2 1.8 2.2 2.7 3.1 6.8 7.8 9 10.5 3.3 35.1l-10.6 43c9.9-17.6 18.6-42.2 22.3-57.8 2.1-9-.2-11.3-8.7-20.2-1.2-1.2-2.5-2.6-3.9-4.1-1.4-1.4-4.9-6-6.8-10.1l.9-.4c1.7 3.8 5.2 8.2 6.7 9.8 1.4 1.5 2.7 2.8 3.9 4.1 8.6 8.9 11.2 11.6 8.9 21.1-3.8 16.1-12.9 41.7-23.2 59.5-.1.2-.3.4-.6.4z"></path><g class="st126"><path class="st28" d="M69.8 162.7s1.4 5.9 9.5 15.8c8.6 10.5 12.2 10.4 5.8 37.8l-10.9 44.5c-.1.3.4.5.5.2 10.2-17.8 19.3-43.2 23.1-59.3 2.5-10.5-.9-12.2-12.7-24.7-1.5-1.6-5-6.1-6.7-9.9"></path><path class="st88" d="M74.5 261.7c-.1 0-.2 0-.3-.1-.4-.1-.6-.5-.5-.9l10.9-44.5c5.6-24.1 3.4-26.7-3.1-34.2-.8-1-1.7-2-2.7-3.2-8.1-9.9-9.5-15.7-9.6-16l1-.2c0 .1 1.5 5.9 9.4 15.6.9 1.2 1.8 2.2 2.7 3.1 6.8 7.8 9 10.5 3.3 35.1l-10.6 43c9.9-17.6 18.6-42.2 22.3-57.8 2.1-9-.2-11.3-8.7-20.2-1.2-1.2-2.5-2.6-3.9-4.1-1.4-1.4-4.9-6-6.8-10.1l.9-.4c1.7 3.8 5.2 8.2 6.7 9.8 1.4 1.5 2.7 2.8 3.9 4.1 8.6 8.9 11.2 11.6 8.9 21.1-3.8 16.1-12.9 41.7-23.2 59.5-.1.2-.3.4-.6.4z"></path></g><path class="st1" d="M69.8 162.7s1.4 5.9 9.5 15.8c8.6 10.5 12.2 10.4 5.8 37.8l-10.9 44.5c-.1.3.4.5.5.2 10.2-17.8 19.3-43.2 23.1-59.3 2.5-10.5-.9-12.2-12.7-24.7-1.5-1.6-5-6.1-6.7-9.9"></path><path class="st88" d="M74.5 262.2c-.2 0-.3 0-.5-.1-.6-.2-.9-.9-.8-1.5l10.9-44.5c5.6-23.9 3.6-26.2-3-33.8-.8-1-1.7-2-2.7-3.2-8.1-9.9-9.6-15.9-9.7-16.2-.1-.5.2-1.1.7-1.2.5-.1 1.1.2 1.2.7 0 .1 1.5 5.9 9.3 15.4.9 1.2 1.8 2.2 2.7 3.1 7.3 8.4 9 11.6 3.4 35.5l-9.6 39.2c9.1-17.2 17-39.7 20.4-54.3 2-8.7 0-10.8-8.6-19.7-1.2-1.2-2.5-2.6-3.9-4.1-1.4-1.5-5-6-6.9-10.2-.2-.5 0-1.1.5-1.3.5-.2 1.1 0 1.3.5 1.7 3.7 5.1 8.1 6.6 9.7 1.4 1.5 2.7 2.8 3.9 4.1 8.7 9.1 11.3 11.8 9.1 21.6-3.8 16.1-12.9 41.8-23.2 59.6-.2.4-.6.7-1.1.7z"></path><path class="st1" d="M86.1 113.5l-8.1-1.9c-8.8-2.1-17.6 3.4-19.7 12.3l-2.2 9.4c-2.8 12.1 3.1 24.2 13.7 29.6.5.3 1 .5 1.6.8h.1c1.4.6 2.9 1.1 4.4 1.4 3.7.8 7.3.1 10.3-1.7 3-1.8 5.2-4.8 6.1-8.5l5.3-22.9c2-8.3-3.2-16.6-11.5-18.5zm6.4 19l-4.6 20.4c-.6 2.8-2.2 5.1-4.3 6.5-2.1 1.4-4.7 2-7.2 1.4-11.2-2.7-18-14.9-15.2-27.2l1.9-8.3c1.5-6.6 7.6-10.8 13.6-9.3l7.7 1.9c5.9 1.5 9.6 8 8.1 14.6z"></path><path class="st88" d="M79 166.4c-1.1 0-2.2-.1-3.3-.4-1.6-.4-3.1-.9-4.6-1.5-.6-.3-1.2-.5-1.7-.8-11.3-5.8-17.1-18.4-14.2-30.7l2.2-9.4c2.2-9.4 11.6-15.2 20.9-13l8.1 1.9c4.3 1 7.9 3.6 10.2 7.3 2.3 3.7 3 8.1 2 12.4l-5.3 22.9c-.9 3.8-3.2 7-6.5 9.1-2.4 1.4-5.1 2.2-7.8 2.2zm-4.7-54.3c-7 0-13.3 4.8-15 11.9l-2.2 9.4c-2.7 11.4 2.8 23.1 13.2 28.5.5.3 1 .5 1.5.7 1.4.6 2.8 1.1 4.3 1.4 3.3.8 6.7.2 9.5-1.6 2.9-1.8 4.9-4.6 5.6-7.8l5.3-22.9c.9-3.7.2-7.6-1.8-10.9-2-3.3-5.2-5.6-9-6.4l-8.1-1.9c-1-.2-2.1-.4-3.3-.4zm4.1 50c-.8 0-1.5-.1-2.3-.3-11.7-2.8-18.9-15.6-16-28.4l1.9-8.3c.8-3.6 3-6.8 6-8.6 2.7-1.7 5.8-2.2 8.8-1.5l7.7 1.9c6.5 1.6 10.5 8.7 8.9 15.8l-4.6 20.4c-.7 3-2.4 5.5-4.8 7.2-1.6 1.2-3.6 1.8-5.6 1.8zm-4.3-45.4c-1.8 0-3.5.5-5.1 1.5-2.5 1.6-4.4 4.3-5.1 7.4l-1.9 8.3c-2.6 11.8 3.9 23.4 14.5 26 2.2.5 4.5.1 6.4-1.3 2-1.3 3.4-3.5 3.9-5.9l4.6-20.4c1.4-6.1-2-12.1-7.4-13.4l-7.7-1.9c-.7-.2-1.4-.3-2.2-.3z"></path><circle class="st1" cx="91.4" cy="189.8" r="1.8"></circle><path class="st88" d="M91.4 192.1c-.2 0-.4 0-.5-.1-1.2-.3-2-1.5-1.7-2.8.1-.6.5-1.1 1-1.4.5-.3 1.1-.4 1.7-.3 1.2.3 2 1.5 1.7 2.8-.1.6-.5 1.1-1 1.4-.4.3-.8.4-1.2.4zm0-3.6c-.2 0-.5.1-.7.2-.3.2-.5.5-.6.8-.2.7.3 1.4 1 1.6.3.1.7 0 1-.2.3-.2.5-.5.6-.8.2-.7-.3-1.4-1-1.6h-.3z"></path><g><g class="st126"><path class="st28" d="M106.1 251.3h9.5c6.4 0 11.5-5.2 11.5-11.5v-17.5l-5.8 2.7v13.9c0 3.5-2.9 6.4-6.4 6.4h-8.3l-.5 6z"></path><path class="st88" d="M115.6 251.8h-10.1l.7-7.1h8.7c3.2 0 5.9-2.6 5.9-5.9v-14.2l6.8-3.1v18.2c.1 6.7-5.3 12.1-12 12.1zm-8.9-1h9c6.1 0 11-4.9 11-11v-16.7l-4.8 2.2v13.6c0 3.8-3.1 6.9-6.9 6.9h-7.8l-.5 5z"></path></g><path class="st88" d="M116.1 246.2h-9.4c-.6 0-1-.4-1-1s.4-1 1-1h9.4c2.4 0 4.3-1.9 4.3-4.3v-19.1c0-2.4-1.9-4.3-4.3-4.3h-9.4c-.6 0-1-.4-1-1s.4-1 1-1h9.4c3.5 0 6.3 2.8 6.3 6.3v19.1c0 3.5-2.8 6.3-6.3 6.3z"></path><path class="st88" d="M119.6 252.3h-13.5c-.6 0-1-.4-1-1v-41.8c0-.6.4-1 1-1h13.5c4.7 0 8.5 3.8 8.5 8.5v26.8c.1 4.6-3.8 8.5-8.5 8.5zm-12.5-2h12.5c3.6 0 6.5-2.9 6.5-6.5V217c0-3.6-2.9-6.5-6.5-6.5h-12.5v39.8z"></path><path class="st88" d="M96 269.5H54.3c-6.1 0-11.2-5-11.2-11.2V193c0-.6.4-1 1-1s1 .4 1 1v65.3c0 5 4.1 9.2 9.2 9.2H96c5 0 9.2-4.1 9.2-9.2V193c0-.6.4-1 1-1s1 .4 1 1v65.3c-.1 6.2-5.1 11.2-11.2 11.2z"></path><path class="st88" d="M96.6 263.8h-43c-3.1 0-5.7-2.6-5.7-5.7v-65.7c0-.3.2-.5.5-.5s.5.2.5.5v65.7c0 2.6 2.1 4.7 4.7 4.7h43.1c2.6 0 4.7-2.1 4.7-4.7v-65.7c0-.3.2-.5.5-.5s.5.2.5.5v65.7c0 3.2-2.6 5.7-5.8 5.7z"></path><g class="st27"><path class="st88" d="M44.1 192.4h62V209h-62z"></path><path class="st88" d="M106.6 209.5h-63v-17.6h63v17.6zm-62-1h61v-15.6h-61v15.6z"></path></g><path class="st88" d="M108.6 193.1H41.7c-.6 0-1-.4-1-1s.4-1 1-1h66.9c.6 0 1 .4 1 1s-.5 1-1 1zm0 7.8H41.7c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h66.9c.3 0 .5.2.5.5s-.3.5-.5.5zm0-4.1H41.7c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h66.9c.3 0 .5.2.5.5s-.3.5-.5.5z"></path></g></g>
                                    <g><path class="st88" d="M390.3 52.5c-1.9 0-3.4-1.5-3.4-3.4 0-.3.2-.5.5-.5s.5.2.5.5c0 1.3 1.1 2.4 2.4 2.4s2.4-1.1 2.4-2.4c0-1-.6-1.9-1.6-2.3-.3-.1-.4-.4-.3-.6s.4-.4.6-.3c1.3.5 2.2 1.8 2.2 3.2.1 1.9-1.4 3.4-3.3 3.4z"></path><path class="st88" d="M389 50.2c-1.5 0-2.7-1.2-2.7-2.7s1.2-2.7 2.7-2.7c1.5 0 2.7 1.2 2.7 2.7s-1.2 2.7-2.7 2.7zm0-4.4c-.9 0-1.7.8-1.7 1.7s.8 1.7 1.7 1.7 1.7-.8 1.7-1.7-.8-1.7-1.7-1.7z"></path></g>
                                    <g><path class="st105" d="M402.4 268.2h-155c-1.5 0-2.8-1.2-2.8-2.8v-55.9c0-5.2 4.2-9.3 9.3-9.3h141.8c5.2 0 9.3 4.2 9.3 9.3v55.9c.2 1.6-1 2.8-2.6 2.8z"></path><path class="st40" d="M402.4 269.2h-155c-2.1 0-3.8-1.7-3.8-3.8v-55.9c0-5.7 4.6-10.3 10.3-10.3h141.8c5.7 0 10.3 4.6 10.3 10.3v55.9c.2 2.1-1.5 3.8-3.6 3.8zm-148.4-68c-4.6 0-8.3 3.7-8.3 8.3v55.9c0 1 .8 1.8 1.8 1.8h155c1 0 1.8-.8 1.8-1.8v-55.9c0-4.6-3.7-8.3-8.3-8.3H254z"></path><path class="st5" d="M389.9 261.2h-47c-.2 0-.4-.2-.4-.4V240c0-.2.2-.4.4-.4h47c.2 0 .4.2.4.4v20.8c0 .2-.2.4-.4.4z"></path><path class="st28" d="M350.3 247.8h32.2v6.2h-32.2z"></path><path class="st177" d="M347.9 258.4l-.4-1.8c8.1-1.8 17-5.8 25.5-9.6 4-1.8 7.7-3.4 11.4-4.9l.7 1.7c-3.6 1.4-7.3 3.1-11.3 4.8-8.6 3.9-17.6 7.9-25.9 9.8z"></path><path class="st27 st40" d="M244.7 217.9h160.5v17H244.7z"></path><path class="st121" d="M407.1 227.2H242.8c-.6 0-1-.5-1-1v-27.3c0-2.1 1.7-3.8 3.8-3.8h158.7c2.1 0 3.8 1.7 3.8 3.8v27.3c0 .6-.4 1-1 1z"></path><path class="st40" d="M407.1 228.2H242.8c-1.1 0-2-.9-2-2v-27.3c0-2.7 2.2-4.8 4.8-4.8h158.7c2.7 0 4.8 2.2 4.8 4.8v27.3c0 1.1-.9 2-2 2zm-161.5-32.1c-1.6 0-2.8 1.3-2.8 2.8v27.3h164.3v-27.3c0-1.6-1.3-2.8-2.8-2.8H245.6z"></path><path class="st105" d="M306.8 220.8c-.3-.1-.5-.3-.5-.5.1-2.2.7-4.3 1.7-6.3 0-.2.1-.3.1-.5l-.8 1.1c-.3.4-.9.9-1.4.9-.3 0-.6-.2-.8-.4-.2-.3-.2-.7-.1-.9.1-.6 0-1.2-.1-1.9 0-.3-.1-.7-.1-1-1.4 1.2-3 3.1-3.5 4.8-.1.2-.2.3-.5.4-.2 0-.4-.1-.5-.3-.1-.2-.6-.8-1.1-1.1-1-.9-1.9-1.8-1.5-2.6.1-.2.4-.3.7-.2.2.1.3.4.2.6.1.3.9 1.1 1.3 1.5.3.2.5.5.7.7.9-2 2.8-3.9 4.4-5.1.2-.1.4-.1.6 0 .2.1.3.3.2.5-.1.6 0 1.2.1 1.9s.2 1.4.1 2.2v.2s.3-.2.5-.5l2-3c.1-.2.4-.3.6-.2.2.1.3.3.3.5s0 .4-.1.5c.6-.8 1.3-1.4 2.1-2.1.1-.1.3-.1.5-.1.2.1.3.2.3.4.1.5.2 1 .3 1.4.1.6.3 1.3.3 2 .7-.7 1.3-1.4 1.9-2.1.3-.4.6-.7 1-1.2.1-.2.4-.2.6-.1s.3.3.3.5l-.2 1.8 3-3.8c.4-.7.9-1.3 1.5-1.9.2-.2.5-.2.7 0 .2.2.2.5.1.7l-1.4 1.8c-.8 1.3-1.3 2.8-1.3 4.3 1.3-.8 2.6-1.8 3.6-3 .1-.2.4-.2.6-.1.2.1.3.3.3.5.5-.5 1.2-.8 2-1 .3 0 .5.1.6.4.1.4.1.9 0 1.3l1.8-1.1c.2-.1.4-.1.5 0s.2.3.2.4v1.4l3.2-3.2c.2-.2.4-.2.6-.1.2.1.3.3.3.5-.2.8-.2 1.6 0 2.4l2.8-1c.2-.1.4 0 .5.1.1.1.2.3.1.5l-1.3 4.1c3.3-2 7.1-3.3 11-3.7.3 0 .5.2.5.4 0 .3-.2.5-.4.5-4.2.4-8.3 1.9-11.7 4.3-.2.1-.4.1-.6 0-.2-.1-.2-.4-.2-.6l1.4-4.5-2.4.8h-.4l-.3-.3c-.2-.6-.3-1.3-.3-1.9l-3.1 3.1c-.1.1-.4.2-.6.1-.2-.1-.3-.3-.3-.5l.1-1.8-2.6 1.5c-.2.1-.5.1-.6-.1-.2-.2-.1-.5 0-.7.4-.4.6-.9.7-1.5-.8.3-1.4.9-1.9 1.7-.3 1.5-.9 3.2-1.5 4.7-.4 1-.7 2-.9 2.7-.1.2-.3.4-.5.4s-.4-.2-.4-.5c0-1.3 1-4.1 1.7-5.9.1-.4.3-.7.4-1 .1-.3.2-.5.4-.8-1 1-2.2 1.8-3.5 2.5-.2.1-.3.1-.5 0-.1-.1-.2-.2-.2-.4 0-.8 0-1.7.2-2.5l-2 2.5c-.1.2-.4.2-.6.2-.2-.1-.3-.3-.3-.5l.2-1.9c-.9 1.1-1.6 2-3 3-.2.1-.4.1-.6 0-.2-.1-.2-.4-.2-.6.4-.9.2-1.8-.1-2.8 0-.2-.1-.4-.1-.6-1 .9-1.8 2-2.4 3.1-.4 2.1-1 4.1-1.7 6.1-.3.4-.5.5-.7.5z"></path><path class="st105" d="M311.2 218.6c-.4-.1-.5-.3-.5-.6l.9-4.3c.1-.3.3-.4.6-.4.3.1.4.3.4.6l-.9 4.3c0 .2-.2.4-.5.4zM359 194.6H204c-1.5 0-2.8-1.2-2.8-2.8V136c0-5.2 4.2-9.3 9.3-9.3h149.7c.8 0 1.5.7 1.5 1.5V192c0 1.4-1.2 2.6-2.7 2.6z"></path><path class="st40" d="M359 195.6H204c-2.1 0-3.8-1.7-3.8-3.8V136c0-5.7 4.6-10.3 10.3-10.3h149.7c1.4 0 2.5 1.1 2.5 2.5V192c0 1.9-1.7 3.6-3.7 3.6zm-148.4-68c-4.6 0-8.3 3.7-8.3 8.3v55.9c0 1 .8 1.8 1.8 1.8h155c1 0 1.8-.8 1.8-1.8V128c0-.3-.2-.5-.5-.5H210.6z"></path><path class="st5" d="M346.4 187.6h-47c-.2 0-.4-.2-.4-.4v-20.8c0-.2.2-.4.4-.4h47c.2 0 .4.2.4.4v20.8c0 .2-.2.4-.4.4z"></path><path class="st28" d="M306.8 174.2H339v6.2h-32.2z"></path><path class="st177" d="M304.5 183l-.4-1.8c3.6-.8 7.4-1.5 11.5-2.3 9.3-1.7 18.8-3.5 27.2-6.8l.7 1.7c-8.5 3.4-18.2 5.2-27.5 6.9-4.2.8-8 1.5-11.5 2.3z"></path><path class="st27 st40" d="M201.2 172.7v-45.1h71.5z"></path><path class="st121" d="M297.6 96.4l-132 97.9c-.4.3-1.1.2-1.4-.2l-16.3-22c-1.3-1.7-.9-4.1.8-5.3l127.4-94.6c1.7-1.3 4.1-.9 5.3.8l16.3 22c.4.4.3 1-.1 1.4z"></path><path class="st40" d="M165 195.5c-.6 0-1.2-.3-1.6-.8l-16.3-22c-.8-1-1.1-2.3-.9-3.6.2-1.3.9-2.4 1.9-3.2l127.4-94.6c1-.8 2.3-1.1 3.6-.9 1.3.2 2.4.9 3.2 1.9l16.3 22c.7.9.5 2.1-.4 2.8l-132 97.9c-.3.3-.7.5-1.2.5zM278.4 72.4c-.6 0-1.2.2-1.7.6l-127.4 94.6c-.6.4-1 1.1-1.1 1.9-.1.7.1 1.5.5 2.1l16.3 22 132-97.9-16.3-22c-.4-.6-1.1-1-1.9-1.1-.1-.1-.2-.2-.4-.2z"></path><g><path class="st105" d="M208 149.3c-.1 0-.2 0-.3-.1-.2-.1-1-.2-1.5-.3-1.4-.2-2.7-.3-2.8-1.2 0-.3.1-.5.4-.6.3 0 .5.1.6.4.2.2 1.3.3 1.9.4.3 0 .7.1 1 .1-.5-2.1-.1-4.8.5-6.7.1-.2.2-.3.4-.3s.4.1.5.3c.3.5.7 1 1.2 1.5s1 1.1 1.3 1.7l.1.2s.1-.4.1-.7l-.2-3.6c0-.2.1-.4.4-.5.2-.1.5 0 .6.2.1.2.2.3.3.5 0-1 .2-1.9.4-2.9 0-.2.2-.3.4-.4.2 0 .4 0 .5.1.3.4.7.7 1 1 .5.4 1 .9 1.4 1.4.2-1 .2-1.9.3-2.9 0-.5 0-1 .1-1.5 0-.2.2-.4.4-.5.2-.1.4 0 .5.2l.9 1.6.2-4.8c-.1-.8 0-1.6.1-2.4 0-.3.3-.4.5-.4.3 0 .5.2.5.5l-.1 2.3c.1 1.5.7 3 1.5 4.3.6-1.4 1-3 1.1-4.5 0-.2.2-.4.4-.5.2-.1.4 0 .5.2.2-.7.5-1.4 1-2 .1-.1.2-.2.4-.2.1 0 .3 0 .4.1.3.3.6.7.7 1.1l.8-2c.1-.2.2-.3.4-.3s.4.1.5.2l.8 1.2.7-4.4c0-.2.2-.4.4-.4s.4.1.5.3c.3.7.8 1.4 1.4 1.9l1.7-2.5c.1-.2.3-.2.5-.2s.3.2.4.3l1.4 4.1c1.4-3.6 3.7-6.9 6.6-9.5.2-.2.5-.2.7 0 .2.2.2.5 0 .7-3.1 2.9-5.5 6.5-6.8 10.4-.1.2-.3.3-.5.3s-.4-.1-.5-.3l-1.5-4.4-1.4 2.1c-.1.1-.2.2-.3.2-.1 0-.3 0-.4-.1-.5-.4-1-.8-1.4-1.4l-.6 4.3c0 .2-.2.4-.4.4s-.4 0-.5-.2l-1-1.5-1.2 2.7c-.1.2-.4.4-.6.3-.2-.1-.4-.3-.4-.6.1-.6 0-1.1-.3-1.6-.4.7-.6 1.6-.5 2.5.6 1.4 1.1 3.1 1.7 4.7.3 1 .6 2 .9 2.7.1.2 0 .5-.2.6-.2.1-.5.1-.6-.1-.8-1-1.6-3.9-2.2-5.7-.1-.4-.2-.7-.3-1-.1-.3-.2-.6-.2-.9-.2 1.4-.7 2.8-1.3 4.1-.1.2-.2.3-.4.3s-.3 0-.4-.2c-.5-.7-1-1.4-1.4-2.2l-.1 3.2c0 .2-.2.4-.4.5-.2.1-.4 0-.6-.2l-1-1.7c-.1 1.4-.1 2.6-.6 4.2-.1.2-.3.4-.5.4s-.4-.2-.5-.4c-.3-1-1-1.6-1.7-2.2-.2-.1-.3-.3-.5-.4-.2 1.3-.3 2.6-.1 3.9.9 1.9 1.7 3.9 2.3 5.9.1.2 0 .5-.3.6-.2.1-.5 0-.6-.2-1.3-1.8-2.1-3.9-2.3-6.1-.1-.1-.1-.3-.2-.4l.1 1.4c0 .5-.2 1.2-.6 1.6-.3.2-.6.2-.9.1-.4-.1-.6-.5-.7-.7-.3-.5-.7-1-1.2-1.5-.2-.2-.5-.5-.7-.8-.4 1.9-.6 4.2 0 5.9.1.2 0 .4-.2.6-.2.3-.3.4-.4.4z"></path><path class="st105" d="M217.6 144.8c-.2 0-.4-.1-.5-.3l-1.9-4c-.1-.2 0-.5.2-.7.3-.1.5 0 .7.2l1.9 4c.1.2 0 .5-.2.7-.1.1-.2.1-.2.1z"></path></g><g><path class="st105" d="M277.6 268.2h-155c-1.5 0-2.8-1.2-2.8-2.8v-55.9c0-5.2 4.2-9.3 9.3-9.3H271c5.2 0 9.3 4.2 9.3 9.3v55.9c.1 1.6-1.2 2.8-2.7 2.8z"></path><path class="st40" d="M277.6 269.2h-155c-2.1 0-3.8-1.7-3.8-3.8v-55.9c0-5.7 4.6-10.3 10.3-10.3H271c5.7 0 10.3 4.6 10.3 10.3v55.9c.1 2.1-1.6 3.8-3.7 3.8zm-148.4-68c-4.6 0-8.3 3.7-8.3 8.3v55.9c0 1 .8 1.8 1.8 1.8h155c1 0 1.8-.8 1.8-1.8v-55.9c0-4.6-3.7-8.3-8.3-8.3h-142z"></path><path class="st5" d="M265.1 261.2h-47c-.2 0-.4-.2-.4-.4V240c0-.2.2-.4.4-.4h47c.2 0 .4.2.4.4v20.8c0 .2-.2.4-.4.4z"></path><path class="st28" d="M225.4 247.8h32.2v6.2h-32.2z"></path><path class="st177" d="M223 256.6l-.3-1.8c8.5-1.3 14.3-3.1 19.9-4.8 5.8-1.8 11.3-3.5 19-4.3l.2 1.8c-7.5.8-12.9 2.4-18.6 4.2-5.6 1.8-11.5 3.6-20.2 4.9z"></path><path class="st27 st40" d="M119.8 219.7h160.5v11.7H119.8z"></path><path class="st121" d="M282.3 227.2H118c-.6 0-1-.5-1-1v-27.3c0-2.1 1.7-3.8 3.8-3.8h158.7c2.1 0 3.8 1.7 3.8 3.8v27.3c0 .6-.5 1-1 1z"></path><path class="st40" d="M282.3 228.2H118c-1.1 0-2-.9-2-2v-27.3c0-2.7 2.2-4.8 4.8-4.8h158.7c2.7 0 4.8 2.2 4.8 4.8v27.3c0 1.1-.9 2-2 2zm-161.5-32.1c-1.6 0-2.8 1.3-2.8 2.8v27.3h164.3v-27.4c0-1.6-1.3-2.8-2.8-2.8H120.8z"></path><g><path class="st105" d="M185 220.8s-.1 0 0 0c-.3-.1-.5-.3-.5-.5.1-2.2.7-4.3 1.7-6.3 0-.2.1-.3.1-.5l-.8 1.1c-.3.4-.9.9-1.4.9-.3 0-.6-.2-.8-.4-.2-.3-.2-.7-.1-.9.1-.6 0-1.2-.1-1.9 0-.3-.1-.7-.1-1-1.4 1.2-3 3.1-3.5 4.8-.1.2-.2.3-.5.4-.2 0-.4-.1-.5-.3-.1-.2-.6-.8-1.1-1.1-1-.9-1.9-1.8-1.5-2.6.1-.2.4-.3.7-.2.2.1.3.4.2.6.1.3.9 1.1 1.3 1.5.3.2.5.5.7.7.9-2 2.8-3.9 4.4-5.1.2-.1.4-.1.6 0 .2.1.3.3.2.5-.1.6 0 1.2.1 1.9s.2 1.4.1 2.2v.2s.3-.2.5-.5l2-3c.1-.2.4-.3.6-.2.2.1.3.3.3.5s0 .4-.1.5c.6-.8 1.3-1.4 2.1-2.1.1-.1.3-.1.5-.1.2.1.3.2.3.4.1.5.2 1 .3 1.4.1.6.3 1.3.3 2 .7-.7 1.3-1.4 1.9-2.1.3-.4.6-.7 1-1.2.1-.2.4-.2.6-.1s.3.3.3.5l-.2 1.8 3-3.8c.4-.7.9-1.3 1.5-1.9.2-.2.5-.2.7 0 .2.2.2.5.1.7l-1.4 1.8c-.8 1.3-1.3 2.8-1.3 4.3 1.3-.8 2.6-1.8 3.6-3 .1-.2.4-.2.6-.1.2.1.3.3.3.5.5-.5 1.2-.8 2-1 .1 0 .3 0 .4.1.1.1.2.2.2.3.1.4.1.9 0 1.3l1.8-1.1c.2-.1.4-.1.5 0 .2.1.2.3.2.4v1.4l3.2-3.2c.2-.2.4-.2.6-.1.2.1.3.3.3.5-.2.8-.2 1.6 0 2.4l2.8-1c.2-.1.4 0 .5.1.1.1.2.3.1.5l-1.3 4.1c3.3-2 7.1-3.3 11-3.7.2 0 .5.2.5.4 0 .3-.2.5-.4.5-4.2.4-8.3 1.9-11.7 4.3-.2.1-.4.1-.6 0-.2-.1-.2-.4-.2-.6l1.4-4.5-2.4.8h-.4c-.1 0-.2-.2-.3-.3-.2-.6-.3-1.3-.3-1.9l-3.1 3.1c-.1.1-.4.2-.6.1-.2-.1-.3-.3-.3-.5l.1-1.8-2.6 1.5c-.2.1-.5.1-.6-.1-.2-.2-.1-.5 0-.7.4-.4.6-.9.7-1.5-.8.3-1.4.9-1.9 1.7-.3 1.5-.9 3.2-1.5 4.7-.4 1-.7 2-.9 2.7-.1.2-.3.4-.5.4s-.4-.2-.4-.5c0-1.3 1-4.1 1.7-5.9.1-.4.3-.7.4-1 .1-.3.2-.5.4-.8-1 1-2.2 1.8-3.5 2.5-.2.1-.3.1-.5 0s-.2-.2-.2-.4c0-.8 0-1.7.2-2.5l-2 2.5c-.1.2-.4.2-.6.2-.2-.1-.3-.3-.3-.5l.2-1.9c-.9 1.1-1.6 2-3 3-.2.1-.4.1-.6 0-.2-.1-.2-.4-.2-.6.4-.9.2-1.8-.1-2.8 0-.2-.1-.4-.1-.6-1 .9-1.8 2-2.4 3.1-.4 2.1-1 4.1-1.7 6.1-.3.4-.5.5-.7.5z"></path><path class="st105" d="M189.4 218.6c-.4-.1-.5-.3-.5-.6l.9-4.3c.1-.3.3-.4.6-.4.3.1.4.3.4.6l-.9 4.3c0 .2-.2.4-.5.4z"></path></g></g><g><path class="st123" d="M334.8 123.4c5.8-4 13.6.7 12.7 7.6l-1 8.2h-10.2l-7.3-12.9 4-2.2"></path><path class="st40" d="M346.6 140.3h-10.2c-.4 0-.7-.2-.9-.5l-7.3-12.9c-.1-.2-.2-.5-.1-.8.1-.3.2-.5.5-.6l4-2.2c.4-.2.9-.1 1.2.2 0-.3.1-.7.4-.9 3-2.1 6.8-2.2 9.9-.4 3.1 1.9 4.8 5.3 4.4 8.9l-1 8.2c0 .6-.4 1-.9 1zm-9.6-2h8.7l.9-7.3c.3-2.8-1-5.5-3.4-7-2.5-1.5-5.4-1.4-7.8.3-.4.3-1 .2-1.3-.2 0 .4-.2.8-.5.9l-3.1 1.7 6.5 11.6z"></path><path class="st28" d="M333.5 123.1l-12.2 6.7-32.4 17.8-46.2 18.9c-3.2 1.2-5.7 3.6-7.1 6.8l-.6 1.4c.1.1.3.2.4.3l1.2.9c19 13.7 51.6 11 81.8 8.9l23-2.1 49.6-4.5-51.3-33.9-6.2-21.2z"></path><path class="st40" d="M282.7 188.1c-18.3 0-35.1-2.3-47-10.9l-1.2-.9-.5-.4c-.5-.4-.7-1.1-.4-1.7l.6-1.4c1.6-3.6 4.4-6.3 8-7.6l46.1-18.8 44.5-24.5c.4-.2.9-.2 1.3-.1.4.2.7.5.9 1l6 20.7 50.8 33.6c.5.4.8 1 .6 1.6s-.7 1.1-1.3 1.1l-72.5 6.6-2.1.2c-11.4.7-22.9 1.5-33.8 1.5zm-45.9-13.8c.2.1.4.3.6.5 18.1 13.1 48.9 10.9 78.8 8.7l2.1-.2 68.2-6.2-47.7-31.5c-.3-.2-.5-.5-.6-.8l-5.7-19.5-42.9 23.6c-.1 0-.1.1-.2.1l-46.2 18.9c-2.8 1-5.1 3.2-6.3 6l-.1.4z"></path><path class="st120" d="M345.1 182c0-8.6 4.2-19.8 8.7-26.1 0 0 25.4 3.5 37.2-13.3v35.9l-45.9 3.5z"></path><path class="st40" d="M345.1 183c-.3 0-.5-.1-.7-.3-.2-.2-.3-.5-.3-.7 0-9.2 4.6-20.6 8.9-26.7.2-.3.6-.5 1-.4.2 0 24.9 3.2 36.2-12.9.2-.4.7-.5 1.1-.4.4.1.7.5.7 1v35.9c0 .5-.4 1-.9 1l-46 3.5c.1 0 0 0 0 0zm9.2-26c-3.9 5.6-7.8 15.6-8.1 24l43.8-3.4v-32.1c-5.1 5.8-12.5 9.6-21.9 11-6.6 1.1-12 .7-13.8.5z"></path><path class="st5" d="M273.8 153.8l.4.4c8.1 1.9 16.6 1 24.1-2.7l28.4-14 3.6 4.2c7.4 8.4 17.8 13.8 29 14.3h1.8c11.7 0 23.4-4.5 29.7-12.1V131.3c-.3-2.3-2.9-1.2-4.6 1-2.6 3.5-5.8 6.4-9.4 8.5-4.4 2.6-9.5 4.1-15 4.1-8.7 0-16.5-4.8-22.1-11.4-2.1-2.5-3.9-5.2-5.3-8.1-.1-.1-.1-.3-.2-.4-.1-.3-.3-.5-.4-.8-.2-.3-.3-.7-.5-1.1l-12.2 6.7-32.4 17.8-14.9 6.2z"></path><path class="st40" d="M361.2 157h-1.9c-11.2-.5-22-5.8-29.7-14.6l-3.1-3.6-27.7 13.6c-7.7 3.8-16.5 4.8-24.8 2.8l-.3-.1-1.6-1.6 16.5-6.7 45.5-25.1.4 1c.1.3.3.7.5 1 .1.3.2.5.4.8.1.1.2.3.2.4 1.4 2.8 3.2 5.5 5.2 7.9 6 7.1 13.6 11 21.3 11 5.2 0 10-1.3 14.5-3.9 3.5-2.1 6.5-4.8 9.1-8.3 1.2-1.6 3.1-3 4.8-2.6.4.1 1.4.5 1.6 2v13.3l-.2.3c-6.7 7.6-18.3 12.4-30.7 12.4zM327 136.3l4.1 4.7c7.4 8.4 17.7 13.5 28.3 13.9h1.8c11.4 0 22.7-4.5 28.7-11.4v-12.1c0-.2-.1-.4-.2-.4-.4-.1-1.6.4-2.7 1.9-2.7 3.6-6 6.6-9.7 8.8-4.7 2.8-9.9 4.2-15.5 4.2-8.3 0-16.4-4.2-22.8-11.8-2.1-2.5-4-5.3-5.5-8.3-.1-.1-.1-.3-.2-.4-.2-.3-.3-.6-.4-.9v-.1l-43.7 24.1-12.5 5.1c7.2 1.2 14.6.1 21.1-3.1l29.2-14.2z"></path><circle class="st120" cx="324.4" cy="148.8" r="3.2"></circle><path class="st40" d="M324.4 152.9c-2.3 0-4.2-1.9-4.2-4.2s1.9-4.2 4.2-4.2c2.3 0 4.2 1.9 4.2 4.2s-1.9 4.2-4.2 4.2zm0-6.3c-1.2 0-2.2 1-2.2 2.2s1 2.2 2.2 2.2 2.2-1 2.2-2.2-1.1-2.2-2.2-2.2z"></path><circle class="st120" cx="303.2" cy="157.2" r="3.1"></circle><path class="st40" d="M303.2 161.4c-2.3 0-4.1-1.9-4.1-4.1s1.9-4.1 4.1-4.1 4.1 1.9 4.1 4.1-1.8 4.1-4.1 4.1zm0-6.3c-1.2 0-2.1 1-2.1 2.1s1 2.1 2.1 2.1 2.1-1 2.1-2.1-.9-2.1-2.1-2.1z"></path><circle class="st120" cx="313.8" cy="152.9" r="3.1"></circle><path class="st40" d="M313.8 157c-2.2 0-4.1-1.8-4.1-4.1s1.8-4.1 4.1-4.1 4.1 1.8 4.1 4.1-1.9 4.1-4.1 4.1zm0-6.1c-1.1 0-2.1.9-2.1 2.1s.9 2.1 2.1 2.1 2.1-.9 2.1-2.1-1-2.1-2.1-2.1z"></path><path class="st5" d="M233.7 175c.3.6.7 1.3 1.1 1.9 6.4 9.7 21.7 14.4 42.7 15.9 43.5 3.1 99.8 1.4 118.1.3 1.5-.1 2.5-1.9 2.1-3.8l-1.4-9.3c-.3-1.3-1.2-2.1-2.3-2.1l-3.1.3-49.6 4.5-23 2.1c-30.2 2.2-62.9 4.9-81.8-8.9l-1.2-.9c-.1-.1-.3-.2-.4-.3-.3-.2-.5-.4-.8-.7-.4-.3-.7.5-.4 1z"></path><path class="st40" d="M335.7 195.6c-19.3 0-40-.5-58.3-1.8-23.1-1.7-37.3-7-43.5-16.4-.4-.6-.8-1.3-1.2-2-.4-.8-.2-1.7.4-2.2.5-.4 1.2-.4 1.7.1.3.2.5.4.8.6l.5.4c.3.3.7.5 1.1.8 18.2 13.2 49.2 11 79.1 8.8l2.1-.2 75.6-6.9c1.6-.1 3 1.1 3.3 2.8l1.4 9.3c.3 1.3 0 2.7-.8 3.7-.6.8-1.4 1.2-2.3 1.3-11.5.9-34.4 1.7-59.9 1.7zm-99.9-18.9c5.9 8.5 19.9 13.6 41.7 15.1 42.4 3.1 97.8 1.5 117.9.3.4 0 .7-.3.8-.5.4-.5.6-1.3.4-2.1l-1.4-9.3c-.1-.7-.7-1.3-1.2-1.2l-77.8 7c-30.3 2.2-61.6 4.4-80.4-9.2.1-.1.1-.1 0-.1z"></path><g><path class="st40" d="M324.1 148.4c-.2 0-.3-.1-.4-.2L311 131.3c-.2-.2-.1-.5.1-.7.2-.2.5-.1.7.1l12.6 16.9c.2.2.1.5-.1.7 0 .1-.1.1-.2.1zm-10.5 4.2c-.1 0-.3-.1-.4-.2l-12.4-15.5c-.2-.2-.1-.5.1-.7.2-.2.5-.1.7.1l12.4 15.5c.2.2.1.5-.1.7-.1.1-.2.1-.3.1zm-10.5 4.3c-.1 0-.3-.1-.4-.2l-10.8-14c-.2-.2-.1-.5.1-.7.2-.2.5-.1.7.1l10.8 14c.2.2.1.5-.1.7-.1 0-.2.1-.3.1z"></path><path class="st40" d="M324.1 148.9c-.5 0-.9-.4-1-.9l-2.6-22.5c-.1-.5.3-1 .9-1.1.6-.1 1 .3 1.1.9l2.6 22.5c.1.5-.3 1-.9 1.1h-.1zm-10.5 4.2c-.5 0-.9-.4-1-.9l-2.1-21.1c-.1-.5.3-1 .9-1.1.6-.1 1 .3 1.1.9l2.1 21.1c.1.5-.3 1-.9 1.1h-.1zm-10.5 4.3c-.5 0-.9-.4-1-.9l-1.9-19.8c-.1-.5.4-1 .9-1.1.6-.1 1 .4 1.1.9l1.9 19.8c0 .5-.4 1-1 1.1.1 0 0 0 0 0zm54.3 33c-.3 0-.5-.2-.5-.5s.2-.5.5-.5c1.6 0 3.1-.1 4.6-.1.3 0 .5.2.5.5s-.2.5-.5.5c-1.5 0-3.1 0-4.6.1z"></path><path class="st40" d="M366.2 190.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5c9.2-.3 16.8-.6 20.5-.8.6 0 1.2-.3 1.6-.8.4-.5.6-1.1.5-1.8-.2-1.2-1.3-2-2.5-1.9-13.7 1.5-34.5 3.3-49.5 4.6-.3 0-.5-.2-.5-.5s.2-.5.5-.5c15-1.2 35.7-3.1 49.4-4.6 1.7-.2 3.3 1 3.5 2.7.1.9-.1 1.8-.7 2.5s-1.4 1.1-2.3 1.2c-3.7.3-11.3.7-20.5.9zm-48.7-.1c-.3 0-.5-.2-.5-.5s.2-.5.5-.5c0 0 6.1-.5 15.1-1.2.2 0 .5.2.5.5s-.2.5-.5.5c-8.9.7-15 1.2-15.1 1.2.1 0 0 0 0 0z"></path></g><g><path class="st5" d="M382.9 173.6l-9.8-10.1c-3-1-7.3-1-10.4 0l-9.8 10.1c-.9.7-1.3 1.7-1.3 2.8v39l32.6-10.8v-28.2c.1-1.1-.4-2.1-1.3-2.8zm-12.6 5.1c-.6.6-1.5 1-2.4 1-.9 0-1.7-.3-2.4-1-.6-.6-1-1.5-1-2.3 0-.9.4-1.7 1-2.4.7-.7 1.5-1 2.4-1 .9 0 1.7.3 2.4 1 1.3 1.3 1.3 3.4 0 4.7z"></path><path class="st40" d="M351.7 216.4c-.2 0-.4-.1-.6-.2-.3-.2-.4-.5-.4-.8v-39c0-1.4.6-2.7 1.7-3.5l9.7-10c.1-.1.2-.2.4-.3 3.2-1.1 7.7-1.1 11 0 .2.1.3.1.4.3l9.7 10c1 .9 1.7 2.2 1.7 3.5v28.2c0 .4-.3.8-.7.9L352 216.3c-.1.1-.2.1-.3.1zm11.6-52l-9.6 9.9-.1.1c-.6.5-1 1.2-1 2V214l30.6-10.1v-27.5c0-.8-.4-1.5-1-2l-.1-.1-9.6-9.9c-2.6-.8-6.4-.8-9.2 0z"></path><path class="st40" d="M371.2 173.1c-1.8-1.8-4.7-1.8-6.5 0-.9.9-1.3 2-1.3 3.2 0 1.2.5 2.4 1.3 3.2.9.9 2 1.3 3.2 1.3 1.2 0 2.4-.5 3.2-1.3 1.9-1.7 1.9-4.6.1-6.4zm-1.4 5.1c-.5.5-1.1.8-1.8.8s-1.3-.3-1.8-.8-.8-1.1-.8-1.8.3-1.3.8-1.8 1.2-.8 1.8-.8c.7 0 1.3.2 1.8.8 1 .9 1 2.6 0 3.6z"></path><path class="st5" d="M358.2 190.2h19"></path><path class="st28" d="M377.2 188.7h-19c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h19c.3 0 .5.2.5.5s-.3.5-.5.5z"></path><path class="st5" d="M363 195.3h14"></path><path class="st28" d="M377 194.8h-14c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h14c.3 0 .5.2.5.5s-.2.5-.5.5z"></path><circle class="st28" cx="358.7" cy="195.3" r="1.5"></circle></g><path class="st123" d="M368 175.3c-.8 0-1.5-.7-1.5-1.5v-34.3c0-.8.7-1.5 1.5-1.5s1.5.7 1.5 1.5v34.3c0 .9-.7 1.5-1.5 1.5z"></path></g></g>
                                    <g><path class="st25" d="M56.6 264c-1.1-.8-1.1-2.4-.1-3.2 3.5-3 8-4.7 12.6-4.8l59.9-1.4.3 12.2-59.9 1.4c-4.6.1-9.1-1.4-12.8-4.2z"></path><path class="st72" d="M69 269.2c-4.7 0-9.2-1.5-12.9-4.4-.7-.6-1.2-1.4-1.2-2.4 0-.9.4-1.8 1.1-2.4 3.7-3.1 8.4-4.9 13.2-5l59.9-1.4c.5 0 1 .4 1 1l.3 12.2c0 .3-.1.5-.3.7-.2.2-.4.3-.7.3l-59.9 1.4H69zm59.1-13.6L69.2 257c-4.4.1-8.6 1.7-12 4.5-.2.2-.4.5-.4.8 0 .3.2.6.4.8 3.5 2.7 7.8 4.1 12.2 4l58.9-1.4-.2-10.1z"></path><path class="st177" d="M129.4 268.2l-.4-15 30-.7c7.1-.2 14.1 1.5 20.4 4.9 1.7.9 1.8 3.3.1 4.3-6.1 3.6-13.1 5.6-20.2 5.8l-29.9.7z"></path><path class="st72" d="M129.4 269.2c-.5 0-1-.4-1-1l-.4-15c0-.3.1-.5.3-.7.2-.2.4-.3.7-.3l30-.7c7.3-.2 14.5 1.5 20.9 5 1.1.6 1.8 1.7 1.8 3s-.6 2.4-1.7 3.1c-6.2 3.7-13.4 5.8-20.7 6l-29.9.6zm.7-15l.3 13 29-.7c6.9-.2 13.7-2.1 19.7-5.7.5-.3.7-.8.7-1.3s-.3-1-.8-1.3c-6.1-3.3-13-4.9-19.9-4.7l-29 .7z"></path><path class="st72" d="M162.1 253.5c-.5 0-1-.4-1-1l-.1-3.5-30.2.7c-.6 0-1-.4-1-1s.4-1 1-1l31.2-.7c.3 0 .5.1.7.3.2.2.3.4.3.7l.1 4.5c0 .6-.4 1-1 1z"></path><path class="st72" d="M130.8 251.6c-.5 0-1-.4-1-1s.4-1 1-1l4.7-.1c.5 0 1 .4 1 1s-.4 1-1 1l-4.7.1z"></path></g>
                                    <g><path class="st123" d="M404.7 212.7v-3.8h26.5v3.8"></path><path class="st40" d="M432.2 212.7h-2v-2.8h-24.5v2.8h-2v-3.8c0-.6.4-1 1-1h26.5c.6 0 1 .4 1 1v3.8z"></path><path class="st25" d="M416.6 269h34.5v-53.3s-20.8 2.7-34.5-1V269z"></path><path class="st40" d="M451.1 270h-34.5c-.6 0-1-.4-1-1v-54.3c0-.3.1-.6.4-.8.2-.2.6-.3.9-.2 13.4 3.6 33.9 1 34.1 1 .3 0 .6.1.8.2.2.2.3.5.3.8V269c0 .6-.5 1-1 1zm-33.5-2h32.5v-51.2c-4.4.5-20.5 2-32.5-.8v52z"></path><path class="st1" d="M396.6 212.7h42.8V269h-42.8z"></path><path class="st28" d="M420.5 238.5c-.4 0-.8-.2-1.2-.5l-1-1.2c-.1-.1-.2-.2-.4-.2s-.3.1-.4.2l-1 1.2c-.4.5-1 .6-1.6.5-.6-.2-1-.7-1.1-1.3l-.1-1.6c0-.2-.1-.3-.2-.4-.1-.1-.3-.1-.4-.1l-1.5.3c-.6.1-1.2-.1-1.6-.6s-.4-1.2-.1-1.7l.8-1.3c.1-.1.1-.3 0-.4 0-.2-.2-.3-.3-.3l-1.4-.6c-.6-.2-.9-.8-.9-1.4s.4-1.2.9-1.4l1.4-.6c.1-.1.3-.2.3-.3 0-.2 0-.3-.1-.4l-.8-1.3c-.3-.5-.3-1.2.1-1.7s1-.7 1.6-.6l1.5.3c.2 0 .3 0 .4-.1.1-.1.2-.2.2-.4l.1-1.6c.1-.6.5-1.1 1.1-1.3.6-.2 1.2 0 1.6.5l1 1.2c.1.1.2.2.4.2s.3-.1.4-.2l1-1.2c.4-.5 1-.6 1.6-.5.6.2 1 .7 1.1 1.3l.1 1.6c0 .2.1.3.2.4.1.1.3.1.4.1l1.5-.3c.6-.1 1.2.1 1.6.6.4.5.4 1.2.1 1.7l-.8 1.3c-.1.1-.1.3-.1.4 0 .2.2.3.3.3l1.4.6c.6.2.9.8.9 1.4s-.4 1.2-.9 1.4l-1.4.6c-.1.1-.3.2-.3.3v.4l.8 1.3c.3.5.3 1.2-.1 1.7s-1 .7-1.6.6l-1.5-.3c-.2 0-.3 0-.4.1s-.2.2-.2.4l-.1 1.6c-.1.6-.5 1.1-1.1 1.3h-.2zm-2.6-2.9c.4 0 .9.2 1.2.5l1 1.2c.2.2.5.2.6.2.1 0 .3-.1.4-.5l.1-1.6c0-.4.3-.8.6-1.1.4-.3.8-.4 1.2-.3l1.5.3c.3.1.5-.1.6-.2 0-.1.2-.3 0-.6l-.8-1.3c-.2-.4-.3-.8-.1-1.3.1-.4.5-.8.9-.9l1.4-.6c.3-.1.3-.4.3-.5 0-.1 0-.4-.3-.5l-1.4-.6c-.4-.2-.7-.5-.9-.9-.1-.4-.1-.9.1-1.3l.8-1.3c.2-.3 0-.5 0-.6 0-.1-.2-.3-.6-.2l-1.5.3c-.4.1-.9 0-1.2-.3-.4-.3-.6-.7-.6-1.1l-.1-1.6c0-.3-.3-.4-.4-.5-.1 0-.4-.1-.6.2l-1 1.2c-.3.3-.7.5-1.2.5-.4 0-.9-.2-1.2-.5l-1-1.2c-.2-.3-.5-.2-.6-.2s-.3.1-.4.5l-.1 1.6c0 .4-.3.8-.6 1.1-.4.3-.8.4-1.2.3l-1.5-.3c-.3-.1-.5.1-.6.2 0 .1-.2.3 0 .6l.8 1.3c.2.4.3.8.1 1.3-.1.4-.5.8-.9.9l-1.4.6c-.3.1-.3.4-.3.5s0 .4.3.5l1.4.6c.4.2.7.5.9.9.1.4.1.9-.1 1.3l-.8 1.3c-.2.3 0 .5 0 .6 0 .1.2.3.6.2l1.5-.3c.4-.1.9 0 1.3.3s.6.7.6 1.1l.1 1.6c0 .3.3.4.4.5.1 0 .4.1.6-.2l1-1.2c.3-.3.7-.5 1.1-.5zm-16.2 24.8c-.6 0-1.1-.3-1.4-.9l-.6-1.4-.3-.3h-.4l-1.3.8c-.1.1-.3.1-.4.2-.2 0-.3 0-.4-.1-.1-.1-.2-.2-.2-.4v-14.9c0-.2.1-.3.2-.4.1-.1.3-.1.4-.1.2 0 .3.1.4.2l1.3.8c.1.1.3.1.4 0 .1 0 .2-.1.3-.3l.6-1.4c.2-.6.8-.9 1.4-.9.6 0 1.1.3 1.4.9l.6 1.4.3.3h.4l1.3-.8c.5-.3 1.2-.3 1.7.1s.7.9.6 1.5l-.3 1.4c0 .2 0 .3.1.4.1.1.2.2.4.2l1.5.1c.6.1 1.1.4 1.3 1 .2.6 0 1.2-.4 1.6l-1.1 1c-.1.1-.2.2-.2.4 0 .1.1.3.2.4l1.1 1c.5.4.6 1 .4 1.6-.2.6-.7 1-1.3 1l-1.7.2c-.1 0-.3.1-.4.2 0 .1-.1.2-.1.4l.3 1.4c.1.6-.1 1.2-.6 1.5-.5.4-1.1.4-1.6.1l-1.3-.8c-.1-.1-.3-.1-.4 0-.1 0-.3.1-.3.3l-.6 1.4c-.1.5-.7.9-1.3.9zm-2.4-3.6c.2 0 .3 0 .5.1.4.1.7.4.9.8l.6 1.4c.1.3.4.3.5.3.1 0 .3 0 .5-.3l.6-1.4c.2-.4.5-.7.9-.8.4-.1.8-.1 1.2.1l1.3.8c.3.2.5 0 .5 0 .1 0 .3-.2.2-.5l-.3-1.5c-.1-.4 0-.9.2-1.2.3-.4.6-.6 1.1-.6l1.5-.1c.3 0 .4-.3.4-.3 0-.1.1-.3-.1-.5l-1.1-1c-.3-.3-.5-.7-.5-1.1 0-.4.2-.8.5-1.1l1.1-1c.2-.2.2-.5.1-.5 0-.1-.1-.3-.4-.3l-1.5-.1c-.4 0-.8-.3-1.1-.6-.3-.4-.3-.8-.2-1.2l.3-1.4c.1-.3-.1-.5-.2-.5s-.3-.2-.6 0l-1.3.8c-.4.2-.8.3-1.2.1-.4-.1-.7-.4-.9-.8l-.6-1.4c-.1-.3-.4-.3-.5-.3-.1 0-.3 0-.5.3l-.6 1.4c-.2.4-.5.7-.9.8-.4.1-.9.1-1.2-.1l-.8-.5v13.2l.8-.5c.3-.4.5-.5.8-.5z"></path><path class="st124" d="M433.5 242.4l-.6 1.4c-.2.5-.9.8-1.4.5l-1.3-.8c-.8-.5-1.7.2-1.5 1.1l.3 1.4c.1.6-.3 1.2-.9 1.2l-1.5.1c-.9.1-1.2 1.2-.6 1.7l1.1 1c.5.4.5 1.1 0 1.5l-1.1 1c-.7.6-.3 1.7.6 1.7l1.5.1c.6.1 1 .6.9 1.2l-.3 1.4c-.2.9.7 1.5 1.5 1.1l1.3-.8c.5-.3 1.2-.1 1.4.5l.6 1.4c.3.8 1.5.8 1.8 0l.6-1.4c.2-.6.9-.8 1.4-.5l1.3.8c.1.1.2.1.3.1v-14.9c-.1 0-.2.1-.3.1l-1.3.8c-.5.3-1.2.1-1.4-.5l-.6-1.4c-.3-.6-1.4-.6-1.8.2z"></path><path class="st55" d="M439.3 217h4.6v52h-4.6z"></path><path class="st40" d="M439.3 270h-42.8c-.6 0-1-.4-1-1v-56.3c0-.6.4-1 1-1h42.8c.6 0 1 .4 1 1V269c0 .6-.4 1-1 1zm-41.7-2h40.8v-54.3h-40.8V268z"></path></g>
                                    <g><path class="st124" d="M206.7 243.7l1.4.6c.6.2.8 1 .5 1.5l-.8 1.3c-.5.8.2 1.8 1.1 1.6l1.5-.3c.6-.1 1.2.3 1.3.9l.1 1.6c.1.9 1.2 1.3 1.8.6l1-1.2c.4-.5 1.2-.5 1.6 0l1 1.2c.6.7 1.7.3 1.8-.6l.1-1.6c.1-.6.7-1.1 1.3-.9l1.5.3c.9.2 1.6-.8 1.1-1.6l-.8-1.3c-.3-.5-.1-1.2.5-1.5l1.4-.6c.8-.4.8-1.6 0-1.9l-1.4-.6c-.6-.2-.8-1-.5-1.5l.8-1.3c.5-.8-.2-1.8-1.1-1.6l-1.5.3c-.6.1-1.2-.3-1.3-.9l-.1-1.6c-.1-.9-1.2-1.3-1.8-.6l-1 1.2c-.4.5-1.2.5-1.6 0l-1-1.2c-.6-.7-1.7-.3-1.8.6l-.1 1.6c-.1.6-.7 1.1-1.3.9l-1.5-.3c-.9-.2-1.6.8-1.1 1.6l.8 1.3c.3.5.1 1.2-.5 1.5l-1.4.6c-.9.3-.9 1.5 0 1.9z"></path></g>
                                    <g><path class="st124" d="M355.6 199l1.2.5c.5.2.7.8.4 1.2l-.7 1.1c-.4.7.2 1.5.9 1.3l1.3-.3c.5-.1 1 .2 1.1.8l.1 1.3c.1.8 1 1.1 1.5.5l.9-1c.3-.4 1-.4 1.3 0l.9 1c.5.6 1.4.3 1.5-.5l.1-1.3c0-.5.5-.9 1.1-.8l1.3.3c.7.2 1.3-.6.9-1.3l-.7-1.1c-.3-.4-.1-1 .4-1.2l1.2-.5c.7-.3.7-1.3 0-1.6l-1.2-.5c-.5-.2-.7-.8-.4-1.2l.7-1.1c.4-.7-.2-1.5-.9-1.3l-1.3.3c-.5.1-1-.2-1.1-.8l-.1-1.3c-.1-.8-1-1.1-1.5-.5l-.9 1c-.3.4-1 .4-1.3 0l-.9-1c-.5-.6-1.4-.3-1.5.5l-.1 1.3c0 .5-.5.9-1.1.8l-1.3-.3c-.7-.2-1.3.6-.9 1.3l.7 1.1c.3.4.1 1-.4 1.2l-1.2.5c-.7.3-.7 1.3 0 1.6z"></path></g>
                                    <path class="st40" d="M509.8 270H2.7c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5h507.1c.8 0 1.5.7 1.5 1.5s-.6 1.5-1.5 1.5z"></path></svg>
                            </div>
                        </div>
                        <div class="empty-displayTmpl-btn">
                            <a class="btn btn-primary btn-lg" href="{{ route('discounts.create') }}">{{ trans('plugins/ecommerce::discount.intro.button_text') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
