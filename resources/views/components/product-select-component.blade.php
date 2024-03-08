
<select class="form-select contactSelect {{isset($className) ? $className : ''}}" name="{{isset($name) ? $name : ''}}"
        data-hide-search="false" data-placeholder="{{isset($placeholder) ? $placeholder : ''}}" data-allow-clear="true"
        data-kt-user-table-filter="role" data-hide-search="true" id='{{isset($id) ? $id : ''}}'>
    {{-- {{ $defaultOption ?? '' }} --}}
    {{$slot}}
</select>
@push('scripts')
<script>
    var img="{{asset('assets/media/svg/files/blank-image.svg')}}";
            // Format options
        const optionFormatTest = (item) => {
            console.log(item.image);
            if(item.image){
                img=`/storage/product-image/${item.image}`
            }
            if (!item.id) {
                return item.text;
            }

            var span = document.createElement('span');
            var template = '';

            template += '<div class="d-flex align-items-center">';
            template += '<img src="' + img + '" class="rounded-1 h-30px me-3" alt=""/>';
            template += '<div class="d-flex flex-column">'
            template += '<span class="fs-5 fw-bold lh-1">' + item.text + '</span>';
            template += '<span class="text-muted fs-9 mt-1">' + item.sku + '</span>';
            template += '</div>';
            template += '</div>';

            span.innerHTML = template;

            return $(span);
        }
    $('.contactSelect').select2({
        ajax: {
            url: '/variations/get',
            dataType: 'json',
            delay: 250,
            processResults: function(results, params) {
                params.page = params.page || 1;
                resultsForSelect=[];
                let data=results.data;
                data.map(function(d) {
                    resultsForSelect.push({
                        id: d.id,
                        text: d.name,
                        sku:d.sku,
                        image:d.image
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
        templateSelection: optionFormatTest,
        templateResult: optionFormatTest,
    })
</script>
@endpush
