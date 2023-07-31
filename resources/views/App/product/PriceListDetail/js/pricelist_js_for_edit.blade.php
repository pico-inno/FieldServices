<script>
    $(document).ready(function() {
        let price_list_details = @json($price_list_details ?? null);
        
        let isRow = false;
        if(!isRow){
            $('#price_list_body tr').each(function(index) {
                let current_row = $(this).closest('tr');
                let applied_type = current_row.find('select[name="apply_type[]"] option:selected').val();
                let selectedElement = current_row.find('select[name="apply_value[]"]');
                
                if(applied_type === 'All'){
                    let option = document.createElement('option');
                    option.value = '0';
                    option.text = 'All Products';
                    current_row.find('select[name="apply_value[]"]').append(option);
    
                    return;
                } 
                
                let old_applied_val = price_list_details[index].applied_value;
                $.ajax({
                    url: `/price-list-detail/search`,
                    type: 'GET',
                    data: {
                        applied_type
                    },
                    success: function(results){
                        $.each(results, function(index, item) {
                            let option = document.createElement('option');
                            option.value = item.id;
                            if(applied_type === 'Variation'){
                                option.text = item.product_variation_name;
                            }else{
                                option.text = item.name;
                            }
                            if(results[index].id === old_applied_val){
                                option.selected = true;
                            }
                            selectedElement.append(option);
                        });
                        $('[data-control="select2"]').select2();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                })
            })
            isRow = true;
        }
    })
</script>