<template>
    <div class="block__content">
        <div class="block--review" v-for="item in data" :key="item.id">
            <div class="block__header">
                <div class="block__image"><img :src="item.user_avatar" :alt="item.user_name" width="60" /></div>
                <div class="block__info">
                    <div class="rating_wrap">
                        <div class="rating">
                            <div class="product_rate" :style="{width: item.star * 20 + '%'}"></div>
                        </div>
                    </div>

                    <div class="my-2">
                        <span class="d-block lh-1">
                            <strong>{{ item.user_name }}</strong>
                            <span v-if="item.ordered_at" class="ml-2">{{ item.ordered_at }}</span>
                        </span>
                        <small class="text-secondary lh-1">{{ item.created_at }}</small>
                    </div>

                    <div class="block__content">
                        <p>{{ item.comment }}</p>
                    </div>
                    <div class="block__images" v-if="item.images && item.images.length">
                        <a :href="image.full_url" v-for="(image, index) in item.images" v-bind:key="index">
                            <img :src="image.thumbnail" :alt="image.thumbnail" class="img-responsive rounded h-100">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isLoading" class="review__loading">
            <div class="half-circle-spinner">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>
        </div>

        <div v-if="!isLoading && !data.length" class="text-center">
            <p>{{ __('No reviews!') }}</p>
        </div>

        <div class="pagination-area mt-15 mb-md-5 mb-lg-0">
            <pagination :data="meta" @on-click-paging="onClickPaginate" />
        </div>
    </div>
</template>

<script>
import Pagination from './utils/Pagination.vue';

export default {
    data: function() {
        return {
            isLoading: true,
            data: [],
            meta: {},
            star: 0,
        };
    },
    props: {
        url: {
            type: String,
            default: () => null,
            required: true
        },
    },
    mounted() {
        this.getData(this.url, false);
        let that = this;

        $(document).on('change', '.ps-review__filter-select select', event => {
            event.preventDefault();
            let $select = $(event.currentTarget);
            if (that.star != $select.val()) {
                that.filterByStar($select.val());
            }
        });

        $(document).on('click', '.ps-block--average-rating .ps-block__star', event => {
            event.preventDefault();
            let $block = $(event.currentTarget);
            let hasActive = $block.hasClass('active');
            if (!hasActive) {
                that.filterByStar($block.data('star'));
            } else {
                that.filterByStar();
            }
        });
    },
    methods: {
        filterByStar(star = 0) {
            let url = this.url;
            this.star = star;
            $('.ps-block--average-rating .ps-block__star').removeClass('active');
            if (star && star != 0) {
                url = this.getUriWithParam(url, {'star': star});
                $('.ps-block--average-rating .ps-block__star[data-star=' + star + ']').addClass('active');
            }
            $('.ps-review__filter-select select').val(star).trigger('change');
            this.getData(url);
        },
        getUriWithParam(baseUrl, params) {
            const url = new URL(baseUrl);
            const urlParams = new URLSearchParams(url.search);
            for (const key in params) {
                if (params[key] !== undefined) {
                    urlParams.set(key, params[key]);
                }
            }
            url.search = urlParams.toString();
            return url.toString();
        },
        getData(link, animation = true) {
            this.isLoading = true;

            if (animation) {
                $('html, body').animate({
                    scrollTop: ($('.block--product-reviews').offset().top - $('.header-area').height() - 165) + 'px',
                }, 1500);
            }

            axios.get(link)
                .then(res => {
                    this.data = res.data.data || [];
                    this.meta = res.data.meta;
                    this.isLoading = false;

                    $('.block--product-reviews .block__header h2').text(res.data.message);
                })
                .catch(res => {
                    this.isLoading = false;
                    console.log(res);
                });
        },
        onClickPaginate({element}) {
            if (!element.active) {
                this.getData(element.url);
            }
        }
    },
    updated: function () {
        let $galleries = $('.block__images');
        if ($galleries.length) {
            $galleries.map((index, value) => {
                if (!$(value).data('lightGallery')) {
                    $(value).lightGallery({
                        selector: 'a',
                        thumbnail: true,
                        share: false,
                        fullScreen: false,
                        autoplay: false,
                        autoplayControls: false,
                        actualSize: false,
                    });
                }
            });
        }
    },

    components: {
        Pagination
    }
}
</script>
