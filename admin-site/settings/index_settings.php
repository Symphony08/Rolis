<?php
session_start();
require_once "../includes/db.php";

// Fetch current token and send_wa setting
$token_query = mysqli_query($conn, "SELECT token, send_wa FROM wa_api LIMIT 1");
$current_token = '';
$current_send_wa = 0;
if ($token_query && mysqli_num_rows($token_query) > 0) {
    $row = mysqli_fetch_assoc($token_query);
    $current_token = $row['token'];
    $current_send_wa = $row['send_wa'] ?? 0;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_token = trim($_POST['token']);
    $send_wa = isset($_POST['send_wa']) ? 1 : 0;

    if (!empty($new_token)) {
        $success = false;
        if (!empty($current_token)) {
            // Update existing token and send_wa
            $update_query = mysqli_prepare($conn, "UPDATE wa_api SET token = ?, send_wa = ?");
            mysqli_stmt_bind_param($update_query, "si", $new_token, $send_wa);
            $success = mysqli_stmt_execute($update_query);
            mysqli_stmt_close($update_query);
        } else {
            // Insert new token and send_wa if no row exists
            $insert_query = mysqli_prepare($conn, "INSERT INTO wa_api (token, send_wa) VALUES (?, ?)");
            mysqli_stmt_bind_param($insert_query, "si", $new_token, $send_wa);
            $success = mysqli_stmt_execute($insert_query);
            mysqli_stmt_close($insert_query);
        }

        if ($success) {
            $_SESSION['flash_message'] = 'Pengaturan WhatsApp berhasil diperbarui.';
        } else {
            $error = 'Gagal memperbarui pengaturan WhatsApp. Silakan coba lagi.';
        }
        header("Location: index_settings.php");
        exit;
    } else {
        $error = 'Token tidak boleh kosong.';
    }
}

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<main class="container mt-5 pt-4">
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card rounded-4 shadow-sm p-4">
                <div class="mb-4">
                    <h3 class="fw-bold">Pengaturan WhatsApp Token</h3>
                    <p class="text-muted">Kelola token API WhatsApp untuk pengiriman pesan otomatis.</p>
                </div>
                <form method="POST" novalidate>
                    <div class="mb-3 row align-items-center">
                        <label for="token" class="col-sm-4 col-form-label fw-semibold">Token</label>
                        <div class="col-sm-8">
                            <input type="text" name="token" id="token" class="form-control rounded-3" value="<?= htmlspecialchars($current_token) ?>" required>
                            <div class="invalid-feedback">Token wajib diisi.</div>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label for="send_wa" class="col-sm-4 col-form-label fw-semibold">Kirim WA</label>
                        <div class="col-sm-8">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="send_wa" name="send_wa" value="1" <?= $current_send_wa ? 'checked' : '' ?>>
                                <label class="form-check-label" for="send_wa">
                                    Aktifkan pengiriman pesan WhatsApp otomatis
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include "../includes/footer.php"; ?>