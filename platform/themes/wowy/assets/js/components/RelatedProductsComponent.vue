<template>
    <div class="row">
        <div class="fl_center">
            <div class="half-circle-spinner" v-if="isLoading">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>
        </div>
        <div :class="'col-lg-' + (12 / limit) + ' col-md-4 col-12 col-sm-6'" v-for="item in data" :key="item.id" v-if="data.length" v-html="item">
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
            limit: {
                type: Number,
                default: () => 3,
            },
        },
        mounted() {
          this.getData();
        },
        methods: {
            getData() {
                this.data = [];
                this.isLoading = true;
                axios.get(this.url + '?limit=' + this.limit)
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
