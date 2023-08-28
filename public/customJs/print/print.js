function ajaxPrint(url){
    $.ajax({
        url: url,
        success: function (response) {
            console.log(response,'helllllllllll');
            if (response.type == 'network') {
                success(response.success);
                return;
            }
            // Open a new window with the invoice HTML and styles
                    // Create a hidden iframe element and append it to the body
            var iframe = $('<iframe>', {
                'height': '0px',
                'width': '0px',
                'frameborder': '0',
                'css': {
                    'display': 'none'
                }
            }).appendTo('body')[0];
            // Write the invoice HTML and styles to the iframe document
            var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            iframeDoc.open();
            iframeDoc.write(response.html);
            iframeDoc.close();

            // Trigger the print dialog
            iframe.contentWindow.focus();
            loadingOff();
            setTimeout(() => {
                iframe.contentWindow.print();
            }, 500);
        }
    });
}
