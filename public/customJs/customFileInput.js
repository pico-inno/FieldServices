const fileInput = document.getElementById('update_logo');
const removeFileBtn = document.getElementById('removeFileBtn');

fileInput.addEventListener('change', () => {
  if (fileInput.files.length > 0) {
    removeFileBtn.classList.remove('d-none');
  } else {
    removeFileBtn.classList.add('d-none');
  }
});

removeFileBtn.addEventListener('click', () => {
  fileInput.value = '';
  removeFileBtn.classList.add('d-none');
});
