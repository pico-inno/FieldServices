$(document).ready(function() {
    var demoResults = [
        { title: 'Keyboard',id:'1', description: 'This is the description of Result 1.' },
        { title: 'Mouse',id:'2', description: 'This is the description of Result 2.' },
        { title: 'Cubic',id:'3', description: 'This is the description of Result 3.' },
        { title: 'Mic',id:'4', description: 'This is the description of Result 4.' },
        { title: 'USB Stick', id:'5',description: 'This is the description of Result 5.' },

        { title: 'JBL Headphone',id:'6', description: 'This is the description of Result 33.' },
        { title: 'Router',id:'7', description: 'This is the description of Result 45.' },
        { title: 'Mouse Pad',id:'8', description: 'This is the description of Result 59.' },
    ];

    $('.quick-search-form input').on('input', function() {
        var query = $(this).val().trim();
        if (query.length >= 1) {
            var results = demoResults.filter(function(result) {
                return result.title.toLowerCase().includes(query.toLowerCase()) || result.description.toLowerCase().includes(query.toLowerCase());
            });
            var html = '';
            if (results.length > 0) {
                results.forEach(function(result) {
                    html += `<div class="quick-search-result result cursor-pointer" data-id=${result.id} data-name=${result.title}`;
                    html += '<h4>' + result.title + '</h4>';
                    html += '<p>' + result.description + '</p>';
                    html += '</div>';
                    html += '<div class="separator mb-3"></div>';
                });
                if (results.length == 1) {
                    $(`.result[data-id|='${results[0].id}']`).click();
                    $('.quick-search-results').hide();
                } else {
                    $('.quick-search-results').show();
                }
            } else {
                html = '<p>No results found.</p>';
            }
            $('.quick-search-results').removeClass('d-none')
            $('.quick-search-results').html(html);
        } else {

            $('.quick-search-results').addClass('d-none');
            $('.quick-search-results').empty();

        }
    });
        // Attach click event listener to the 'Add Row' button
    $('#autocomplete').on('click', '.result', function() {
        // Get the ID and name data from the clicked autocomplete div
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');

        // Create a new row with the ID and name data
        var newRow = `<tr data-id='1'>
            <td>
                <a href='' class='text-gray-800 text-hover-primary mb-1'>${id}</a>
            </td>
            <!--end::Name=-->
            <!--begin::Email=-->
            <td>
                <a href="#" class="text-gray-600 text-hover-primary mb-1">${name}</a>
            </td>
             <td>
                <input type="text" class="form-control mb-3">
                <select name="" class="form-select" data-kt-repeater="select2" data-hide-search="false">
                    <option value="">box</option>
                    <option value="">Pieces</option>
                </select>
            </td>
             <td>
                <select name=""  class="form-select" data-kt-repeater="select2" data-hide-search="false" placeholder="Select UOM SET">
                    <option>Select Uom Set</option>
                    <option value="">box-strip-tab</option>
                    <option value="">Pieces</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control sum" value="100">
            </td>
            <td>
                <input type="text" class="form-control sum" value="100">
            </td>
            <td>
                <input type="text" class="form-control sum" value="100">
            </td>
            <td>
                <input type="text" class="form-control sum" value="100">
            </td>
            <td>
                <input type="text" class="form-control sum" value="100">
            </td>
            <th><i class="fa-solid fa-trash text-danger deleteRow btn" ></i></th>
        </tr>`;

        // Append the new row to the table body
        $('#purchase_table tbody').append(newRow);
        $('.dataTables_empty').addClass('d-none');
        $('.quick-search-results').addClass('d-none');
        $('.quick-search-results').empty();
        $('#searchInput').val('');
         $('[data-kt-repeater="select2"]').select2();

    });

  // Attach click event listener to all delete buttons in the table
    $(document).on('click', '#purchase_table .deleteRow', function (e) {
         e.preventDefault();
                // let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to remove it!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#f1416c',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        // Get the parent row (tr) of the clicked button
                        var row = $(this).closest('tr');
                        // Get the data-id attribute value of the row
                        var id = row.attr('data-id');
                        // Get the data in the row
                        var name = row.find('td[data-id="' + id + '"]').text();

                        // Do something with the data, e.g. display in console
                        console.log('Deleted row with ID ' + id + ', name: ' + name);

                        // Remove the row from the table
                        var rowCount = $('#purchase_table tbody tr').length;
                        console.log(rowCount);
                        if (rowCount == 2) {
                            $('.dataTables_empty').removeClass('d-none');
                        }
                        row.remove();
                    }
                });
    });
});




