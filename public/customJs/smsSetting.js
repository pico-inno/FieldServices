
const sms_service = document.getElementById('sms_service');
const other_tab = document.getElementById('other-tab');
const nexmo_tab = document.getElementById('nexmo-tab');
const twilio_tab = document.getElementById('twilio-tab');
const sms_poh_tab = document.getElementById('smsPOH-tab');
$('#sms_service').on('change', selectForm)



function selectForm() {
    if (sms_service.value == 'other') {

        other_tab.classList.remove('d-none');
        nexmo_tab.classList.add('d-none');
        twilio_tab.classList.add('d-none');
        sms_poh_tab.classList.add('d-none');
    } else if(sms_service.value=='nexmo'){
        nexmo_tab.classList.remove('d-none');
        other_tab.classList.add('d-none');
        twilio_tab.classList.add('d-none');
        sms_poh_tab.classList.add('d-none');
    } else if (sms_service.value == 'twilio') {
        twilio_tab.classList.remove('d-none');
        other_tab.classList.add('d-none');
        nexmo_tab.classList.add('d-none');
        sms_poh_tab.classList.add('d-none');
    }
    else if (sms_service.value == 'smsPOH') {
        sms_poh_tab.classList.remove('d-none');
        twilio_tab.classList.add('d-none');
        other_tab.classList.add('d-none');
        nexmo_tab.classList.add('d-none');
    }
}


function classRemove() {
    other_tab.classList.remove('d-none');

}
