$(document).ready(function() {
    // Get the state of the aside from Local Storage or default to hidden
    var asideVisible = localStorage.getItem('asideVisible') || 'false';
  
    // Set the initial state of the aside
    if (asideVisible === 'true') {
        $('#kt_body').removeClass('aside-secondary-disabled');
        $('#kt_body').addClass('aside-secondary-enabled');
    } else {
        $('#kt_body').removeClass('aside-secondary-enabled');
        $('#kt_body').addClass('aside-secondary-disabled');
    }
  
    // Toggle the state of the aside when the primary aside link is clicked
    $('#pos_link').click(function() {
        var $ktBody = $('#kt_body');
        if ($ktBody.hasClass('aside-secondary-enabled')) {
            $ktBody.removeClass('aside-secondary-enabled');
            $ktBody.addClass('aside-secondary-disabled');
            localStorage.setItem('asideVisible', 'false');
        } else {
            $ktBody.removeClass('aside-secondary-disabled');
            $ktBody.addClass('aside-secondary-enabled');
            localStorage.setItem('asideVisible', 'true');
        }
    });
    
    // Show the secondary aside when a link other than #pos_link is clicked
    $('a').not('#pos_link').click(function() {
        $('#kt_body').removeClass('aside-secondary-disabled');
        $('#kt_body').addClass('aside-secondary-enabled');
        localStorage.setItem('asideVisible', 'true');
    });
});



