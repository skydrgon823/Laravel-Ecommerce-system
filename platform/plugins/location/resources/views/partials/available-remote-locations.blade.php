@if (!empty($locations))
    <div class="table-responsive">
        <table class="table text-start table-striped table-bordered">
            <tbody>
                @foreach($locations as $countryCode => $countryName)
                    <tr>
                        <td>{{ $countryName }}</td>
                        <td class="text-end">
                            <button class="btn btn-info btn-import-location-data"
                                    data-url="{{ route('location.bulk-import.import-location-data', strtolower($countryCode)) }}"
                                    type="button"><i
                                    class="fas fa-download"></i> {{ trans('plugins/location::bulk-import.import') }}</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <span class="d-inline-block">{{ trans('core/base::tables.no_data') }}</span>
@endif
