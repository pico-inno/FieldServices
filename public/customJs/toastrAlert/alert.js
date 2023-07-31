

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
        "positionClass": "toastr-top-center",
        "backgroundColor": "gray",
        "showDuration": "500",
        "hideDuration": "700",
        "timeOut": "700",
        "extendedTimeOut": "700",
      };
      toastr.success(message)
}

