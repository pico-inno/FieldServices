
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
