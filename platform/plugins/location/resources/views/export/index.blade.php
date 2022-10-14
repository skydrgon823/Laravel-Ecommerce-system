@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-6 col-xl-8 col-lg-10 col-12">
            <div class="widget meta-boxes">
                <div class="widget-title ps-2">
                    <h4>{{ trans('plugins/location::location.export_location') }}</h4>
                </div>
                <div class="widget-body">
                    <div class="row text-center py-5">
                        <div class="col-sm-4">
                            <h5>{{ trans('plugins/location::location.total_country') }}</h5>
                            <h2 class="h1 text-primary font-bold">{{ number_format($countryCount) }}</h2>
                        </div>
                        <div class="col-sm-4">
                            <h5>{{ trans('plugins/location::location.total_state') }}</h5>
                            <h2 class="h1 text-info font-bold">{{ number_format($stateCount) }}</h2>
                        </div>
                        <div class="col-sm-4">
                            <h5>{{ trans('plugins/location::location.total_city') }}</h5>
                            <h2 class="h1 text-info font-bold">{{ number_format($cityCount) }}</h2>
                        </div>
                    </div>
                    <div class="form-group mb-3 d-grid">
                        <button type="button" href="{{ route('location.export.process') }}"
                            class="btn btn-info btn-export-data"
                            data-loading-text="{{ trans('plugins/location::location.exporting') }}"
                            data-filename="exported_location.csv">
                            {{ trans('plugins/location::location.start_export') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
