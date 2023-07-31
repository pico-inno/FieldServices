// Bind the change event to the checkbox
$('#individual_ck').change(function() {
  // Get the checked value of the checkbox
    var isChecked = $(this).prop('checked');
     $('#bussiness_ck').prop('checked',false);

  // Check if the checkbox is checked or not
  if (isChecked) {
      $('#individual_input_gp').removeClass('d-none')
       $('#bussiness_id_div').addClass('d-none')
  }
});
$('#bussiness_ck').change(function() {
  // Get the checked value of the checkbox
    var isChecked = $(this).prop('checked');
    $('#individual_ck').prop('checked',false);

  // Check if the checkbox is checked or not
  if (isChecked) {
      $('#individual_input_gp').addClass('d-none')
      $('#bussiness_id_div').removeClass('d-none')
  }
});
