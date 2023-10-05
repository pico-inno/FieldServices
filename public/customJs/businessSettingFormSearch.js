$(document).ready(function(){

$('#search-bar').on('change', function () { scrollToLabel() })
function scrollToLabel() {
  const searchBar = $('#search-bar');
  const tabLinks = $('.setting-link');
  const tabPanes = $('.setting-tab');
  const labelToSearch = searchBar.val().toLowerCase();
  const matchingLabel = $(`label[for="${labelToSearch}"]`);

  if (matchingLabel.length) {
    // Activate the tab containing the matching label
    const matchingTabPane = matchingLabel.closest('.tab-pane');
    const matchingTabLink = $(`[data-bs-target="#${matchingTabPane.attr('id')}"]`);
    tabLinks.removeClass('active');
    tabPanes.removeClass('active show');
    matchingTabLink.addClass('active');
    matchingTabPane.addClass('active show');
    let title = $(matchingTabLink.closest('.nav-item')).data('title');
    $('#tab-title').text(title);
    // Highlight the matching label for 5 seconds
    matchingLabel.addClass('highlighted');
    setTimeout(() => {
      matchingLabel.removeClass('highlighted');
    }, 3000);

    // Scroll to the input element associated with the matching label
    const matchingInput = $(`#${matchingLabel.attr('for')}`);
    $('html, body').animate({
      scrollTop: matchingInput.offset().top - ($(window).height() - matchingInput.outerHeight()) / 2
    }, 1000);
  }
}



// function scrollToLabel() {

//     const searchBar = document.getElementById('search-bar');
//     const tabLinks = document.querySelectorAll('.setting-link');
//     const tabPanes = document.querySelectorAll('.setting-tab');
//     const labelToSearch = searchBar.value.toLowerCase();
//     const matchingLabel = document.querySelector(`label[for*=${labelToSearch}]`);
//   if (matchingLabel) {
//     // Activate the tab containing the matching label
//     const matchingTabPane = matchingLabel.closest('.tab-pane');
//     const matchingTabLink = document.querySelector(`[data-bs-target="#${matchingTabPane.id}"]`);
//     tabLinks.forEach((tabLink) => tabLink.classList.remove('active'));
//     tabPanes.forEach((tabPane) => tabPane.classList.remove('active', 'show'));
//     matchingTabLink.classList.add('active');
//     matchingTabPane.classList.add('active', 'show');
//     // Highlight the matching label for 5 seconds
//     matchingLabel.classList.add('highlighted');
//     setTimeout(() => {
//       matchingLabel.classList.remove('highlighted');
//     }, 5000);

//     // Scroll to the input element associated with the matching label
//     const matchingInput = document.querySelector(`#${matchingLabel.getAttribute('for')}`);
//     matchingInput.scrollIntoView({ behavior: "smooth",block: 'center'}); // scrolls with smooth animation
//   }
// }

// searchBar.addEventListener('change', () => {
//   const labelToSearch = searchBar.value.toLowerCase();
//   const matchingLabel = document.querySelector(`label[for^=input][for*=${labelToSearch}]`);
//   if (matchingLabel) {
//     // Activate the tab containing the matching label
//     const matchingTabPane = matchingLabel.closest('.tab-pane');
//     const matchingTabLink = document.querySelector(`[data-bs-target="#${matchingTabPane.id}"]`);
//     tabLinks.forEach((tabLink) => tabLink.classList.remove('active'));
//     tabPanes.forEach((tabPane) => tabPane.classList.remove('active', 'show'));
//     matchingTabLink.classList.add('active');
//     matchingTabPane.classList.add('active', 'show');
// console.log('whh');
//     // Highlight the matching label for 5 seconds
//     matchingLabel.classList.add('highlighted');
//     setTimeout(() => {
//       matchingLabel.classList.remove('highlighted');
//     }, 5000);

//     // Scroll to the input element associated with the matching label
//     const matchingInput = document.querySelector(`#${matchingLabel.getAttribute('for')}`);
//     matchingInput.scrollIntoView({ behavior: 'smooth' });
//   }
// });

})
