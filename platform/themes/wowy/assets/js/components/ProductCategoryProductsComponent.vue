<template>
    <div class="container">
        <div class="heading-tab d-flex">
            <div class="heading-tab-left wow fadeIn animated">
                <h3 class="section-title mb-35">{{ category.name }}</h3>
            </div>
            <div class="heading-tab-right wow fadeIn animated">
                <ul class="nav nav-tabs right no-border" role="tablist">
                    <li class="nav-item" role="presentation" v-for="item in productCategories" :key="item.id">
                        <button :class="productCategory.id === item.id ? 'nav-link active': 'nav-link'" data-bs-toggle="tab" :data-bs-target="'#' + item.slug" type="button" role="tab" :aria-controls="item.slug" aria-selected="true" @click="getData(item)">{{ item.name }}</button>
                    </li>
                </ul>
            </div>
        </div>
        <!--End nav-tabs-->
        <div class="tab-content wow fadeIn animated">
            <div class="half-circle-spinner" v-if="isLoading">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>

            <div class="tab-pane fade show active" v-if="!isLoading" :id="productCategory.slug" role="tabpanel" :aria-labelledby="productCategory.slug + '-tab'" :key="productCategory.id">
                <div class="row product-grid-4">
                    <div class="col-lg-3 col-md-4 col-12 col-sm-6" v-for="item in data" :key="item.id" v-if="data.length" v-html="item">
                    </div>
                </div>
                <!--End product-grid-4-->
            </div>
        </div>
        <!--End tab-content-->
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                isLoading: true,
                data: [],
                productCategory: {},
                productCategories: []
            };
        },

        mounted() {
            if (this.category) {
                this.productCategory = this.category;
                this.productCategories = this.children;
                this.getData(this.productCategory);
            }
        },

        props: {
            category: {
                type: Object,
                default: () => {},
                required: true
            },
            children: {
                type: Array,
                default: () => [],
            },
            url: {
                type: String,
                default: () => null,
                required: true
            }
        },

        methods: {
            getData(category) {
                this.productCategory = category;
                this.data = [];
                this.isLoading = true;
                axios.get(this.url + '?category_id=' + category.id)
                    .then(res => {
                        this.data = res.data.data ? res.data.data : [];
                        this.isLoading = false;
                    })
                    .catch(res => {
                        this.isLoading = false;
                        console.log(res);
                    });
            },
        },
    }
</script>
