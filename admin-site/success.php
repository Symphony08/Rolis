<?php
session_start();

// Redirect jika tidak ada pesan sukses
if (!isset($_SESSION['success_message'])) {
  header("Location: index_user.php");
  exit;
}

$success_message = $_SESSION['success_message'];
unset($_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Berhasil - ROLIS</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Google Fonts - Poppins -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="icon" href="/Rolis/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="../public/assets/css/user_success.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #FFFDF2 0%, #F5F3E8 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .success-container {
      text-align: center;
      max-width: 600px;
      animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .success-icon {
      width: 120px;
      height: 120px;
      background: linear-gradient(135deg, #27AE60 0%, #229954 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 2rem;
      animation: scaleIn 0.5s ease-out 0.3s both;
      box-shadow: 0 10px 40px rgba(39, 174, 96, 0.3);
    }

    @keyframes scaleIn {
      from {
        transform: scale(0);
      }
      to {
        transform: scale(1);
      }
    }

    .success-icon i {
      font-size: 4rem;
      color: white;
    }

    .success-checkmark {
      animation: checkmark 0.8s ease-out 0.5s both;
    }

    @keyframes checkmark {
      0% {
        transform: scale(0) rotate(-45deg);
      }
      50% {
        transform: scale(1.2) rotate(-45deg);
      }
      100% {
        transform: scale(1) rotate(0deg);
      }
    }

    h1 {
      font-size: 2.5rem;
      font-weight: 700;
      color: #2C3E50;
      margin-bottom: 1rem;
      animation: fadeInUp 0.8s ease-out 0.6s both;
    }

    .success-message {
      font-size: 1.2rem;
      color: #555;
      margin-bottom: 2rem;
      animation: fadeInUp 0.8s ease-out 0.7s both;
    }

    .info-box {
      background: white;
      border-radius: 15px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      animation: fadeInUp 0.8s ease-out 0.8s both;
    }

    .info-item {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1rem;
      font-size: 1rem;
      color: #555;
    }

    .info-item:last-child {
      margin-bottom: 0;
    }

    .info-item i {
      font-size: 1.5rem;
      color: #2C3E50;
      margin-right: 1rem;
    }

    .btn-home {
      background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
      color: white;
      padding: 1rem 3rem;
      font-size: 1.1rem;
      font-weight: 600;
      border: none;
      border-radius: 50px;
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s ease;
      box-shadow: 0 8px 20px rgba(44, 62, 80, 0.3);
      animation: fadeInUp 0.8s ease-out 0.9s both;
    }

    .btn-home:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(44, 62, 80, 0.4);
      color: white;
    }

    .confetti {
      position: fixed;
      width: 10px;
      height: 10px;
      background: #27AE60;
      position: absolute;
      animation: confetti-fall 3s linear infinite;
    }

    @keyframes confetti-fall {
      to {
        transform: translateY(100vh) rotate(360deg);
        opacity: 0;
      }
    }

    @media (max-width: 768px) {
      h1 {
        font-size: 2rem;
      }

      .success-message {
        font-size: 1rem;
      }

      .success-icon {
        width: 100px;
        height: 100px;
      }

      .success-icon i {
        font-size: 3rem;
      }
    }
  </style>
</head>

<body>
  <div class="success-container">
    <div class="success-icon">
      <i class="bi bi-check-circle-fill success-checkmark"></i>
    </div>

    <h1>Registrasi Berhasil!</h1>
    <p class="success-message">
      <?= htmlspecialchars($success_message) ?>
    </p>

    <div class="info-box">
      <div class="info-item">
        <i class="bi bi-shield-check"></i>
        <span>Data Anda telah tersimpan dengan aman</span>
      </div>
      <div class="info-item">
        <i class="bi bi-person-check"></i>
        <span>Tim kami akan segera memproses pendaftaran Anda</span>
      </div>
      <div class="info-item">
        <i class="bi bi-telephone"></i>
        <span>Kami akan menghubungi Anda melalui WhatsApp</span>
      </div>
    </div>

    <a href="index_user.php" class="btn-home">
      <i class="bi bi-house-fill me-2"></i>Kembali ke Beranda
    </a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Create confetti effect
    function createConfetti() {
      const colors = ['#27AE60', '#2C3E50', '#E74C3C', '#F39C12', '#3498DB'];
      
      for (let i = 0; i < 50; i++) {
        setTimeout(() => {
          const confetti = document.createElement('div');
          confetti.className = 'confetti';
          confetti.style.left = Math.random() * window.innerWidth + 'px';
          confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
          confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
          confetti.style.animationDelay = Math.random() * 2 + 's';
          document.body.appendChild(confetti);

          setTimeout(() => {
            confetti.remove();
          }, 5000);
        }, i * 30);
      }
    }

    // Run confetti on load
    window.addEventListener('load', createConfetti);
  </script>
</body>

</html>