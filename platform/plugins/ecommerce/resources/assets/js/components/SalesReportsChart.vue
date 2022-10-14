<template>
    <div>
        <div class="btn-group d-block text-end" v-if="filters.length">
            <a class="btn btn-sm btn-secondary" href="javascript:;" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-filter" aria-hidden="true"></i>
                <span>{{ filtering }}</span>
                <i class="fa fa-angle-down "></i>
            </a>
            <ul class="dropdown-menu float-end">
                <li v-for="(filter) in filters" v-bind:key="filter.key">
                    <a href="#" v-on:click="clickFilter(filter.key, $event)">
                        {{ filter.text }}
                    </a>
                </li>
            </ul>
        </div>
        <div class="sales-reports-chart"></div>
        <div class="row" v-if="earningSales.length">
            <div class="col-12">
                <ul>
                    <li v-for="earningSale in earningSales" v-bind:key="earningSale.text">
                        <i class="fas fa-circle" v-bind:style="{ color: earningSale.color }"></i> {{ earningSale.text }}
                    </li>
                </ul>
            </div>
        </div>
        <!-- loading in here -->
        <div class="loading"></div>
    </div>
</template>

<script>

export default {
    data: () => {
        return {
            isLoading: true,
            earningSales: [],
            colors: ['#fcb800', '#80bc00'],
            chart: null,
            filtering: '',
        };
    },
    props: {
        url: {
            type: String,
            default: null,
            required: true
        },
        format: {
            type: String,
            default: 'dd/MM/yy',
            required: false
        },
        filters: {
            type: Array,
            default: () => [],
            required: false
        },
        filterDefault: {
            type: String,
            default: '',
            required: false
        },
    },
    mounted: function () {
        this.setFiltering();

        if (this.url) {
            axios.get(this.url)
                .then(res => {
                    if (res.data.error) {
                        Botble.showError(res.data.message);
                    } else {
                        this.earningSales = res.data.data.earningSales;
                        this.chart = new ApexCharts(this.$el.querySelector('.sales-reports-chart'), {
                            series: res.data.data.series,
                            chart: {height: 350, type: 'area', toolbar: {show: false}},
                            dataLabels: {enabled: false},
                            stroke: {curve: 'smooth'},
                            colors: res.data.data.colors,
                            xaxis: {
                                type: 'datetime',
                                categories: res.data.data.dates
                            },
                            tooltip: {x: {format: this.format}},
                            noData: {
                                text: BotbleVariables.languages.tables.no_data,
                            }
                        });
                        this.chart.render();
                    }
                });
        }
    },
    methods: {
        setFiltering: function (f = '') {
            if (!f) {
                f = this.filterDefault
            }
            if (this.filters.length) {
                const filter = this.filters.find((x) => x.key == f);
                if (filter) {
                    this.filtering = filter.text;
                } else {
                    this.filtering = f;
                }
            }
        },
        clickFilter: function (filter, event) {
            event.preventDefault();
            this.setFiltering('...');

            let context = this;
            axios.get(context.url, {
                    params: {
                        filter
                    }
                })
                .then(res => {
                    if (res.data.error) {
                        Botble.showError(res.data.message);
                    } else {
                        context.earningSales = res.data.data.earningSales;
                        const options = {
                            xaxis: {
                                type: 'datetime',
                                categories: res.data.data.dates
                            },
                            series: res.data.data.series,
                        };
                        if (res.data.data.colors) {
                            options.colors = res.data.data.colors;
                        }
                        this.chart.updateOptions(options);
                    }
                    this.setFiltering(filter);
                });

        }
    }
}
</script>
