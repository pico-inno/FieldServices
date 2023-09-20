
function loadingOn() {
    var spinnerWrapper = document.getElementById("spinnerWrapper");
    spinnerWrapper.style.display = "block";
    spinnerWrapper.style.top ="20px";
}
function loadingOff() {
    var spinnerWrapper = document.getElementById("spinnerWrapper");
    spinnerWrapper.style.animation = "slide-up 0.5s ease";
    spinnerWrapper.style.top ="-40px";
    setTimeout(function(){
        spinnerWrapper.style.display = "none";
    },5000)
}

function noti(message='Greate ! You Successfully Imported. Check and Save the data.')
{

    var noti = document.getElementById("noti");
    noti.classList.remove('d-none');
    noti.classList.add('animated-content');
    setInterval(() => {
        $('#notiMessage').text(message);

    }, 300);
    setTimeout(function () {
        noti.classList.add('d-none');
    }, 2000);
}
