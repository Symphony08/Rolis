// Ambil elemen
const hamburger = document.querySelector('.hamburger');
const sidebar = document.querySelector('.sidebar');

// Event klik hamburger → toggle sidebar
hamburger.addEventListener('click', () => {
  sidebar.classList.toggle('active');
});

// Jika ingin sidebar tertutup otomatis saat klik link
const menuLinks = document.querySelectorAll('.sidebar a');
menuLinks.forEach(link => {
  link.addEventListener('click', () => {
    sidebar.classList.remove('active');
  });
});
