<?php

namespace Botble\Ecommerce\Charts;

use Botble\Chart\LineChart;

class TimeChart extends LineChart
{
    /**
     * @return LineChart
     */
    public function init()
    {
        return $this
            ->setElementId('ecommerce-time-chart')
            ->xkey(['date'])
            ->ykeys(['revenue'])
            ->pointFillColors(['green'])
            ->pointStrokeColors(['black'])
            ->lineColors(['blue', 'pink'])
            ->hoverCallback('function(index, options, content, row) {return "<strong>" + row.formatted_date + "</strong>: " + row.formatted_revenue;}')
            ->xLabels('day');
    }
}
