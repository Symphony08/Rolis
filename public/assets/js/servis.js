document.addEventListener('DOMContentLoaded', function () {
  const checkbox = document.getElementById('manualProdukCheckbox');
  const dropdown = document.getElementById('produk_id');
  const manualFields = document.getElementById('manualProdukFields');

  const manualInputs = manualFields.querySelectorAll('input, select');

  checkbox.addEventListener('change', function () {
    if (this.checked) {
      dropdown.disabled = true;
      dropdown.removeAttribute('required');
      dropdown.value = "";

      manualFields.classList.remove('d-none');
      manualInputs.forEach(input => input.setAttribute('required', true));
    } else {
      dropdown.disabled = false;
      dropdown.setAttribute('required', true);

      manualFields.classList.add('d-none');
      manualInputs.forEach(input => input.removeAttribute('required'));
    }
  });
});