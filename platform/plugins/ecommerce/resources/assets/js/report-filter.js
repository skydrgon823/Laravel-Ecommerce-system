$(() => {
  moment.locale($('html').attr('lang'));

  let $daterange = $('.daterange-picker');
  let today = moment();
  let endDate = moment().endOf('month');
  if (endDate > today) {
      endDate = today;
  }
  let rangesTrans = BotbleVariables.languages.reports;
  let ranges = {
      [rangesTrans.today]: [moment(), moment()],
      [rangesTrans.this_week]: [moment().startOf('week'), moment()],
      [rangesTrans.last_7_days]: [moment().subtract(6, 'days'), moment()],
      [rangesTrans.this_month]: [moment().startOf('month'), endDate],
      [rangesTrans.this_year]: [
          moment().startOf('year'),
          moment().endOf('year')
      ]
  };
  $daterange.daterangepicker(
      {
          ranges: ranges,
          alwaysShowCalendars: true,
          startDate: moment().startOf('month'),
          endDate: endDate,
          maxDate: endDate,
          opens: 'left',
          drops: 'auto',
          locale: {
              format: $daterange.data('format') || 'YYYY-MM-DD'
          }
      },
      function(start, end, label) {
          $.ajax({
              url: $daterange.data('href'),
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
});
