import SalesReportsChart from './components/SalesReportsChart';
import RevenueChart from './components/RevenueChart';

import Vue from 'vue';

Vue.component('sales-reports-chart', SalesReportsChart);
Vue.component('revenue-chart', RevenueChart);

$(() => {
    if (!window.moment || !jQuery().daterangepicker) {
        return;
    }

    moment.locale($('html').attr('lang'));

    let $dateRange = $('.date-range-picker');
    let dateFormat = $dateRange.data('format') || 'YYYY-MM-DD';
    let startDate = $dateRange.data('start-date') || moment().subtract(29, 'days');

    let today = moment();
    let endDate = moment().endOf('month');
    if (endDate > today) {
        endDate = today;
    }
    let rangesTrans = BotbleVariables.languages.reports;
    let ranges = {
        [rangesTrans.today]: [today, today],
        [rangesTrans.this_week]: [moment().startOf('week'), today],
        [rangesTrans.last_7_days]: [moment().subtract(6, 'days'), today],
        [rangesTrans.last_30_days]: [moment().subtract(29, 'days'), today],
        [rangesTrans.this_month]: [moment().startOf('month'), endDate],
        [rangesTrans.this_year]: [
            moment().startOf('year'),
            moment().endOf('year')
        ]
    };

    $dateRange.daterangepicker(
        {
            ranges: ranges,
            alwaysShowCalendars: true,
            startDate: startDate,
            endDate: endDate,
            maxDate: endDate,
            opens: 'left',
            drops: 'auto',
            locale: {
                format: dateFormat
            },
            autoUpdateInput: false,
        },
        function (start, end, label) {
            $.ajax({
                url: $dateRange.data('href'),
                data: {
                    date_from: start.format('YYYY-MM-DD'),
                    date_to: end.format('YYYY-MM-DD'),
                    predefined_range: label
                },
                type: 'GET',
                success: data => {
                    if (data.error) {
                        Botble.showError(data.message);
                    } else {
                        $('.report-chart-content').html(data.data.html);
                        new Vue({
                            el: '#report-chart'
                        });
                        if (window.LaravelDataTables) {
                            Object.keys(window.LaravelDataTables).map(
                                (key) => {
                                    let table = window.LaravelDataTables[key];
                                    let url = new URL(table.ajax.url());
                                    url.searchParams.set(
                                        'date_from',
                                        start.format('YYYY-MM-DD')
                                    );
                                    url.searchParams.set(
                                        'date_to',
                                        end.format('YYYY-MM-DD')
                                    );
                                    table.ajax.url(url.href).load();
                                }
                            );
                        }
                    }
                },
                error: data => {
                    Botble.handleError(data);
                }
            });
        }
    );

    $dateRange.on('apply.daterangepicker', function (ev, picker) {
        let $this = $(this);
        let formatValue = $this.data('format-value');
        if (!formatValue) {
            formatValue = '__from__ - __to__';
        }
        let value = formatValue
            .replace('__from__', picker.startDate.format(dateFormat))
            .replace('__to__', picker.endDate.format(dateFormat));
        $this.find('span').text(value);
    });

});

const app = new Vue({
    el: '#report-chart'
});
