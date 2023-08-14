tempusDominus.extend(tempusDominus.plugins.customDateFormat);

    new tempusDominus.TempusDominus(document.getElementById('dob'), {
        localization: {
            locale: "en",
            format: "dd/MM/yyyy",
        }
    });

    const individual = document.getElementById('individual');
    const individualdivs = document.querySelectorAll('.individual-div');
    const business = document.getElementById('business');
    const businessdiv = document.querySelector('.business-div');

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
        const moreInfo = document.querySelector("#more-info-fields");
        const moreInfoBtn = document.querySelector(".moreBtn")
        if (moreInfo.style.display === "none") {
            moreInfo.style.display = "block";
            moreInfoBtn.innerHTML = `Less Informations <i class="fa-solid fa-chevron-up text-white ms-4"></i>`;
        } else {
            moreInfo.style.display = "none";
            moreInfoBtn.innerHTML = `More Informations <i class="fa-solid fa-chevron-down text-white ms-4"></i>`;
        }
    }

    // Get references to the select elements and divs
    const contactTypeSelect = document.getElementById('contact-type');
    const customerGroupDiv = document.querySelectorAll('.customer-group');
    const creditLimitDiv = document.querySelector('.credit-limit');

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