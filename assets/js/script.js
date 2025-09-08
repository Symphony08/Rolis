// Ambil elemen
const hamburger = document.querySelector('.hamburger');
const sidebar = document.querySelector('.sidebar');

// Event klik hamburger → toggle sidebar
hamburger.addEventListener('click', () => {
  sidebar.classList.toggle('active');
});

// Dropdown toggle (submenu mobile)
document.querySelectorAll('.mobile-dropdown > .dropdown-toggle').forEach(toggle => {
  toggle.addEventListener('click', (e) => {
    e.preventDefault(); // cegah link "#" langsung reload halaman
    const parent = toggle.parentElement;
    // Tutup dropdown lain kalau perlu
    document.querySelectorAll('.mobile-dropdown.open').forEach(drop => {
      if (drop !== parent) drop.classList.remove('open');
    });
    // Toggle dropdown ini
    parent.classList.toggle('open');
  });
});
// Khusus tombol dropdown
document.querySelectorAll(".dropdown-toggle").forEach(toggle => {
  toggle.addEventListener("click", function (e) {
    e.preventDefault(); // cegah reload #
    this.parentElement.classList.toggle("open");
  });
});

// Dropdown toggle (submenu mobile)
document.querySelectorAll(".dropdown-toggle").forEach(toggle => {
  toggle.addEventListener("click", function (e) {
    e.preventDefault(); // mencegah reload #
    this.parentElement.classList.toggle("open");
  });
});