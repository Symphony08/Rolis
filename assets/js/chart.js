// Pastikan file ini dipanggil SETELAH Chart.js library dan setelah variabel pieData & barData dari PHP

// ----- PIE CHART -----
const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: ['Pelanggan', 'Produk', 'Transaksi'],
        datasets: [{
            data: [pieData.pelanggan, pieData.produk, pieData.transaksi],
            backgroundColor: ['#4F6A9D', '#FF9F40', '#4BC0C0'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// ----- BAR CHART -----
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Transaksi per bulan',
            data: barData,
            backgroundColor: '#4F6A9D'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1 // supaya naik 1,2,3 bukan pecahan
                }
            }
        }
    }
});
