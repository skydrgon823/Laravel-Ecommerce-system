<style>
    .change-date-range {
        position: absolute;
        top: -52px;
        right: 80px;
    }
    .change-date-range .btn {
        padding: 5px 10px;
        border-radius: 0 !important;
    }
</style>
<div class="col-12">
    <div class="btn-group change-date-range">
        <a class="btn btn-sm btn-secondary" href="javascript:;" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-filter" aria-hidden="true"></i>
            <span>{{ $defaultRange }}</span>
            <i class="fa fa-angle-down "></i>
        </a>
        <ul class="dropdown-menu float-end">
            <li>
                <a href="{{ route('ecommerce.report.revenue', ['filter' => 'date']) }}">
                    {{ trans('plugins/ecommerce::reports.today') }}
                </a>
            </li>
            <li>
                <a href="{{ route('ecommerce.report.revenue', ['filter' => 'week']) }}">
                    {{ trans('plugins/ecommerce::reports.this_week') }}
                </a>
            </li>
            <li>
                <a href="{{ route('ecommerce.report.revenue', ['filter' => 'month']) }}">
                    {{ trans('plugins/ecommerce::reports.this_month') }}
                </a>
            </li>
            <li>
                <a href="{{ route('ecommerce.report.revenue', ['filter' => 'year']) }}">
                    {{ trans('plugins/ecommerce::reports.this_year') }}
                </a>
            </li>
        </ul>
    </div>
    @if (!empty($chartTime))
        {!! $chartTime->renderChart() !!}
    @else
        @include('core/dashboard::partials.no-data')
    @endif
</div>

