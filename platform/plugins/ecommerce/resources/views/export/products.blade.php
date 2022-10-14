@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-6 col-xl-8 col-lg-10 col-12">
            <div class="widget meta-boxes">
                <div class="widget-title ps-2">
                    <h4>{{ trans('plugins/ecommerce::export.products.title') }}</h4>
                </div>
                <div class="widget-body">
                    <div class="row text-center py-5">
                        <div class="col-6">
                            <h5>{{ trans('plugins/ecommerce::export.products.total_products') }}</h5>
                            <h2 class="h1 text-primary font-bold">{{ $totalProduct }}</h2>
                        </div>
                        <div class="col-6">
                            <h5>{{ trans('plugins/ecommerce::export.products.total_variations') }}</h5>
                            <h2 class="h1 text-info font-bold">{{ $totalVariation }}</h2>
                        </div>
                    </div>
                    <div class="form-group mb-3 d-grid">
                        <button type="button" href="{{ route('ecommerce.export.products.index') }}"
                            class="btn btn-info btn-export-data"
                            data-loading-text="{{ trans('plugins/ecommerce::export.exporting') }}"
                            data-filename="export_products.csv">
                            {{ trans('plugins/ecommerce::export.start_export') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
