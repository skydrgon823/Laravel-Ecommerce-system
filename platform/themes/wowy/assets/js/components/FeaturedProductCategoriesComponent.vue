<template>
    <div>
        <div v-if="isLoading">
            <div class="half-circle-spinner">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>
        </div>
        <div v-carousel class="carousel-6-columns-cover position-relative" v-if="!isLoading">
            <div class="slider-arrow slider-arrow-2 carousel-6-columns-arrow" id="carousel-6-columns-arrows"></div>
            <div class="carousel-slider-wrapper carousel-6-columns" id="carousel-6-columns">
                <div class="card-1 border-radius-10 hover-up p-20" v-for="item in data">
                    <figure class="mb-30 img-hover-scale overflow-hidden">
                        <a :href="item.url"><img :src="item.thumbnail" :alt="item.name" /></a>
                    </figure>
                    <h5><a :href="item.url">{{ item.name }}</a></h5>
                </div>
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
        props: {
            url: {
                type: String,
                default: () => null,
                required: true
            },
        },
        mounted() {
          this.getCategories();
        },
        methods: {
            getCategories() {
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
