<select class="form-select productsSelect {{isset($className) ? $className : ''}}" name="{{isset($name) ? $name : ''}}"
        data-hide-search="false" data-placeholder="{{isset($placeholder) ? $placeholder : ''}}" data-allow-clear="true"
        id='{{isset($id) ? $id : ''}}'>
    {{ $defaultOption ?? '' }}
</select>
@push('scripts')
    <script>
        $('.productsSelect').select2({
            ajax: {
                url: '/products/get/variations',
                dataType: 'json',
                delay: 250,
                processResults: function(results, params) {
                    params.page = params.page || 1;
                    resultsForSelect = [];
                    let data = results.data;
                    data.map(function(d) {
                        resultsForSelect.push({
                            id: `${d.id}-${d.variation_id}`,
                            value: `${d.id}-${d.variation_id}`,
                            text: (d.variation_name !== null) ? `${d.name} - ${d.variation_name}` : d.name,
                            uom: d.uom, // Add the uom property to the option
                        });
                    })
                    return {
                        results: resultsForSelect,
                        pagination: {
                            more: (params.page * 20) < results.total
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Search for an item',
            minimumInputLength: 0,
        });
    </script>
@endpush
