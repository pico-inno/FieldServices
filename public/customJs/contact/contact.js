    tempusDominus.extend(tempusDominus.plugins.customDateFormat);
    new tempusDominus.TempusDominus(document.getElementById('dob'), {
        localization: {
            locale: "en",
            format: "dd/MM/yyyy",
        }
    });
    $(document).on('change', '#dob', function () {
        let dobString = $('#dob').val();
        let dobParts = dobString.split('/');
        let day = parseInt(dobParts[0], 10);
        let month = parseInt(dobParts[1], 10) - 1;
        let year = parseInt(dobParts[2], 10);

        let dob = new Date(year, month, day);
        if (dob) {
            let today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            let monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            $('#age').val(age);
        }
    })

    let individual = document.getElementById('individual');
    let individualdivs = document.querySelectorAll('.individual-div');
    let business = document.getElementById('business');
    let businessdiv = document.querySelector('.business-div');

    // if(individual.checked){
    //     for(let i = 0; i < individualdivs.length; i++){
    //         individualdivs[i].classList.remove('hide');
    //     }
    // }

    if (business.checked) {
        businessdiv.classList.remove('hide');
    }

    individual.addEventListener('change', () => {
        for (let i = 0; i < individualdivs.length; i++) {
            business.checked = false;
            individualdivs[i].classList.remove('hide');
            businessdiv.classList.add('hide');
        }
    });

    business.addEventListener('change', () => {
        for (let i = 0; i < individualdivs.length; i++) {
            individual.checked = false;
            individualdivs[i].classList.add('hide');
            businessdiv.classList.remove('hide');
        }
    });

    const showMoreInfo = () => {
        let moreInfo = document.querySelector("#more-info-fields");
        let moreInfoBtn = document.querySelector(".moreBtn")
        if (moreInfo.style.display === "none") {
            moreInfo.style.display = "block";
            moreInfoBtn.innerHTML = `Less Informations <i class="fa-solid fa-chevron-up text-white ms-4"></i>`;
        } else {
            moreInfo.style.display = "none";
            moreInfoBtn.innerHTML = `More Informations <i class="fa-solid fa-chevron-down text-white ms-4"></i>`;
        }
    }

    // Get references to the select elements and divs
    let contactTypeSelect = document.getElementById('contact-type');
    let customerGroupDiv = document.querySelectorAll('.customer-group');
    let creditLimitDiv = document.querySelector('.credit-limit');

    const showCG = () => {
        if (contactTypeSelect.value === "Customer") {
            customerGroupDiv.forEach(function(customer_group) {
                customer_group.classList.remove('hide');
            });
            creditLimitDiv.classList.remove('hide');
        } else if (contactTypeSelect.value === "Supplier") {
            customerGroupDiv.forEach(function(customer_group) {
                customer_group.classList.add('hide');
            });
            creditLimitDiv.classList.add('hide');
        } else if (contactTypeSelect.value === "Both") {
            customerGroupDiv.forEach(function(customer_group) {
                customer_group.classList.remove('hide');
            });
            creditLimitDiv.classList.remove('hide');
        }
    }

    const showCustomerGroup = () => {
        if (contactTypeSelect.value === "Customer") {
            customerGroupDiv.forEach(function(customer_group) {
                customer_group.classList.remove('hide');
                customer_group.style.display = "block";
            });
            creditLimitDiv.classList.remove('hide');
            creditLimitDiv.style.display = "block";
        } else if (contactTypeSelect.value === "Supplier") {
            customerGroupDiv.forEach(function(customer_group) {
                customer_group.classList.add('hide');
                customer_group.style.display = "none";
            });
            creditLimitDiv.classList.add('hide');
            creditLimitDiv.style.display = "none";
        } else if (contactTypeSelect.value === "Both") {
            customerGroupDiv.forEach(function(customer_group) {
                customer_group.classList.remove('hide');
                customer_group.style.display = "block";
            });
            creditLimitDiv.classList.remove('hide');
            creditLimitDiv.style.display = "block";
        }
    }
