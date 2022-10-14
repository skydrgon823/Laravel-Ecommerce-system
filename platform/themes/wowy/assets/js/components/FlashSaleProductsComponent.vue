<template>
    <div class="row">
        <div v-if="isLoading">
            <div class="half-circle-spinner">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>
        </div>
        <div class="col-lg-6 deal-co" v-countdown v-for="item in data" :key="item.id" v-if="!isLoading && data.length" v-html="item"></div>
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
        props: {
            url: {
                type: String,
                default: () => null,
                required: true
            },
        },
        mounted() {
          this.getProducts();
        },
        methods: {
            getProducts() {
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
        },
        directives: {
            countdown: {
                // directive definition
                inserted: function (el) {
                    var $this = $(el).find('.deals-countdown');
                    var finalDate = $this.data('countdown');
                    $this.countdown(finalDate, function (event) {
                        let trans = key => {
                            window.trans = window.trans || {};

                            return window.trans[key] !== 'undefined' && window.trans[key] ? window.trans[key] : key;
                        }

                        $this.html(
                            event.strftime(''
                                + '<span class="countdown-section"><span class="countdown-amount hover-up">%D</span><span class="countdown-period"> ' + trans('days') + ' </span></span>'
                                + '<span class="countdown-section"><span class="countdown-amount hover-up">%H</span><span class="countdown-period"> ' + trans('hours') + ' </span></span>'
                                + '<span class="countdown-section"><span class="countdown-amount hover-up">%M</span><span class="countdown-period"> ' + trans('mins') + ' </span></span>'
                                + '<span class="countdown-section"><span class="countdown-amount hover-up">%S</span><span class="countdown-period"> ' + trans('sec') + ' </span></span>'
                            )
                        );
                    });
                }
            }
        }
    }
</script>
