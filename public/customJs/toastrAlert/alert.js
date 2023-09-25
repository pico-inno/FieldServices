
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toastr-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  };
function success(message = "Need to add message") {
    toastr.success(message);
    var audio = new Audio("/customJs/toastrAlert/sound/success.mp3");

    sound=localStorage.getItem('alertSound');
    if(sound){
        if(sound=='on'){
            audio.play();
        }else if(sound=='off'){
            return;
        }
    }else{
        audio.play();
    }


}
function warning(message='need to add message') {
    toastr.warning(message);
}
function error(message='need to add message') {
    toastr.error(message);
}
function flotemessage(message=''){
    toastr.options = {
        "closeButton": true,
        "positionClass": "toastr-top-center",
        "backgroundColor": "gray",
        "showDuration": "500",
        "hideDuration": "700",
        "timeOut": "700",
        "extendedTimeOut": "700",
      };
      toastr.success(message)
}


