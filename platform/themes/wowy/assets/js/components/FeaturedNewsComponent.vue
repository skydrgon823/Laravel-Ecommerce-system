<template>
    <div>
        <div class="half-circle-spinner" v-if="isLoading">
            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
        </div>
        <div class="post-list mb-4 mb-lg-0">
            <div class="row">
                <article class="wow fadeIn animated col-lg-6" v-for="item in data" :key="item.id" v-if="!isLoading && data.length">
                    <div class="d-md-flex d-block">
                        <div class="post-thumb d-flex mr-15 border-radius-10">
                            <a class="color-white" :href="item.url">
                                <img class="border-radius-10" :src="item.image" :alt="item.name">
                            </a>
                        </div>
                        <div class="post-content">
                            <div class="entry-meta mb-5 mt-10">
                                <a class="entry-meta meta-2" :href="item.category.url"><span class="post-in text-danger font-x-small text-uppercase">{{ item.category.name }}</span></a>
                            </div>
                            <h4 class="post-title mb-25 text-limit-2-row">
                                <a :href="item.url">{{ item.name }}</a>
                            </h4>
                            <div class="entry-meta meta-1 font-xs color-grey mt-10 pb-10">
                                <div>
                                    <span class="post-on"> <i class="far fa-clock"></i> {{ item.created_at }}</span>
                                    <span class="hit-count has-dot">{{ item.views }} {{ __('Views')}}</span>
                                </div>
                                <a :href="item.url">{{ __('Read more') }} <i class="fa fa-arrow-right font-xxs ml-5"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                isLoading: true,
                data: []
            };
        },

        mounted() {
            this.getData();
        },

        props: {
            url: {
                type: String,
                default: () => null,
                required: true
            },
        },

        methods: {
            getData() {
                this.data = [];
                this.isLoading = true;
                axios.get(this.url)
                    .then(res => {
                        this.data = res.data.data ? res.data.data : [];
                        this.isLoading = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                    });
            },
        }
    }
</script>
