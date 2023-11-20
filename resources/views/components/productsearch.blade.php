
<select class="form-select contactSelect {{isset($className) ? $className : ''}}" name="{{isset($name) ? $name : ''}}"
        data-hide-search="false" data-placeholder="{{isset($placeholder) ? $placeholder : ''}}" data-allow-clear="true"
        data-kt-user-table-filter="role" data-hide-search="true" id='{{isset($id) ? $id : ''}}'>
    {{ $defaultOption ?? '' }}
</select>
@push('scripts')
<script>
    $('.contactSelect').select2({
        ajax: {
            url: '/products/get',
            dataType: 'json',
            delay: 250,
            processResults: function(results, params) {
                params.page = params.page || 1;
                resultsForSelect=[];
                let data=results.data;
                data.map(function(d) {
                    resultsForSelect.push({
                        id: d.id,
                        text: d.name
                    });
                })
                return {
                    results:resultsForSelect,
                    pagination: {
                        more: (params.page * 20) < results.total
                    }
                };
            },
            cache: true
        },
        placeholder: 'Search for an item',
        minimumInputLength: 0,
    })
</script>
@endpush
