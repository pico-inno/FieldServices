        $('form#add_contact_form').submit(function(event) {
            event.preventDefault();

            var formData = $(this).serialize();
            console.log($(this).attr('action'));
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                success: function(response){
                    if (response.success == true) {
                        $('#contact_add_modal').modal('hide');
                        success(response.msg);

                        // Clear the input fields in the modal form
                        $('#add_contact_form')[0].reset();
                        $('.contact_id').append($('<option>', {
                            value: response.new_contact_id,
                            text: response.new_contact_name
                        }));
                        $('.contact_id').val(response.new_contact_id).trigger("change");
                    }
                },
                error: function(result) {
                    error(result.responseJSON.errors, 'Something went wrong');
                }
            })
        })
