@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="widget meta-boxes">
        <div class="widget-title">
            <h4>&nbsp; {{ trans('plugins/translation::translation.locales') }}</h4>
        </div>
        <div class="widget-body box-translation">
            <div class="row">
                <div class="col-md-5">
                    <div class="main-form">
                        <div class="form-wrap">
                            <form class="add-locale-form" action="{{ route('translations.locales') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="locale_id" class="control-label">{{ trans('plugins/translation::translation.locale') }}</label>
                                    <div class="ui-select-wrapper form-group">
                                        <select id="locale_id" name="locale" class="form-control select-search-full">
                                            <option value="">{{ trans('plugins/translation::translation.select_locale') }}</option>
                                            @foreach ($locales as $key => $name)
                                                <option value="{{ $key }}"> {{ $name }} - {{ $key }}</option>
                                            @endforeach
                                        </select>
                                        <svg class="svg-next-icon svg-next-icon-size-16">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                                        </svg>
                                    </div>
                                </div>
                                <p class="submit">
                                    <button class="btn btn-primary" type="submit">{{ trans('plugins/translation::translation.add_new_locale') }}</button>
                                </p>
                            </form>
                        </div>

                        <br>
                        <div class="widget meta-boxes">
                            <div class="widget-title px-0">
                                <h4>{{ trans('plugins/translation::translation.import_available_locale') }}</h4>
                            </div>
                            <div class="widget-body px-0">
                                <div id="available-remote-locales" data-url="{{ route('translations.locales.available-remote-locales') }}">
                                    @include('core/base::elements.loading')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="table-responsive">
                        <table class="table table-hover table-language table-header-color" style="background: #f1f1f1;">
                            <thead>
                            <tr>
                                <th class="text-start"><span>{{ trans('plugins/translation::translation.name') }}</span></th>
                                <th class="text-center"><span>{{ trans('plugins/translation::translation.locale') }}</span></th>
                                <th class="text-center"><span>{{ trans('plugins/translation::translation.is_default') }}</span></th>
                                <th class="text-center"><span>{{ trans('plugins/translation::translation.actions') }}</span></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($existingLocales as $item)
                                    @include('plugins/translation::partials.locale-item', compact('item'))
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    @include('core/table::partials.modal-item', [
        'type'        => 'danger',
        'name'        => 'modal-confirm-delete',
        'title'       => trans('core/base::tables.confirm_delete'),
        'content'     => trans('plugins/translation::translation.confirm_delete_message', ['lang_path' => lang_path()]),
        'action_name' => trans('core/base::tables.delete'),
        'action_button_attributes' => [
            'class' => 'delete-crud-entry',
        ],
    ])

    <div class="modal fade modal-confirm-import-locale" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('plugins/translation::translation.import_available_locale_confirmation') }}</strong></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>

                <div class="modal-body with-padding">
                    <div>{!! BaseHelper::clean(trans('plugins/translation::translation.import_available_locale_confirmation_content', ['lang_path' => Html::tag('strong', lang_path())->toHtml()])) !!}</div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="float-start btn btn-secondary" data-bs-dismiss="modal">{{ trans('core/table::table.cancel') }}</button>
                    <button class="float-end btn btn-warning button-confirm-import-locale">{{ trans('plugins/translation::translation.download_locale') }}</button>
                </div>
            </div>
        </div>
    </div>
@stop
