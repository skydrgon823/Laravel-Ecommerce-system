<template>
    <div class="container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation" v-for="item in productCollections" :key="item.id">
                <button :class="productCollection.id === item.id ? 'nav-link active': 'nav-link'" data-bs-toggle="tab" :data-bs-target="'#' + item.slug" type="button" role="tab" :aria-controls="item.slug" aria-selected="true" @click="getData(item)">{{ item.name }}</button>
            </li>
        </ul>
        <!--End nav-tabs-->
        <div class="tab-content wow fadeIn animated">
            <div class="half-circle-spinner" v-if="isLoading">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>

            <div class="tab-pane fade show active" v-if="!isLoading" :id="productCollection.slug" role="tabpanel" :aria-labelledby="productCollection.slug + '-tab'" :key="productCollection.id">
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
                productCollections: [],
                productCollection: {}
            };
        },

        mounted() {
            if (this.product_collections.length) {
                this.productCollections = this.product_collections;
                this.productCollection = this.productCollections[0];
                this.getData(this.productCollection);
            } else {
                this.isLoading = false;
            }
        },

        props: {
            product_collections: {
                type: Array,
                default: () => [],
            },
            url: {
                type: String,
                default: () => null,
                required: true
            },
        },

        methods: {
            getData(productCollection) {
                this.productCollection = productCollection;
                this.data = [];
                this.isLoading = true;
                axios.get(this.url + '?collection_id=' + productCollection.id)
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
