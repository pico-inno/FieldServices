<select class="form-select locationsSelect {{isset($className) ? $className : ''}}" name="{{isset($name) ? $name : ''}}"
        data-hide-search="false" data-placeholder="{{isset($placeholder) ? $placeholder : ''}}" data-allow-clear="true"
        id='{{isset($id) ? $id : ''}}'>
    {{ $defaultOption ?? '' }}
</select>
@push('scripts')
    <script>
        $('.locationsSelect').select2({
            ajax: {
                url: '/location/get',
                dataType: 'json',
                delay: 250,
                processResults: function(results, params) {
                    params.page = params.page || 1;
                    resultsForSelect=[];
                    let data=results.data;
                    data.map(function(d) {
                        resultsForSelect.push({
                            id: d.id,
                            value: d.id,
                            text: d.name,
                        });
                    })
                    return {
                        results:resultsForSelect,
                        pagination: {
                            more: (params.page * 10) < results.total
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Search locations',
            minimumInputLength: 0,
        })
    </script>
@endpush
