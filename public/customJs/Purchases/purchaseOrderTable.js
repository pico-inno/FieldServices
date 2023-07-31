$table=$("#purchase_table").DataTable({
        "paginate": false,
        "info": false,
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Total over all pages
            var total = api
                .column( 4, { search: 'applied' } )
                .nodes()
                .to$()
                .find('.sum')
                .toArray()
                .reduce( function (a, b) {
                    return a + parseInt(b.value);
                }, 0 );

            // Update target element
            $('.rowSum').html( "$ "+total );
        }
    });
    $ro=$table.rows().count();
    $('.rowcount').html($ro );
