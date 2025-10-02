<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
require_once "../includes/db.php";

// Fetch current token
$token_query = mysqli_query($conn, "SELECT token FROM wa_api LIMIT 1");
$current_token = '';
if ($token_query && mysqli_num_rows($token_query) > 0) {
    $current_token = mysqli_fetch_assoc($token_query)['token'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_token = trim($_POST['token']);

    if (!empty($new_token)) {
        $success = false;
        if (!empty($current_token)) {
            // Update existing token
            $update_query = mysqli_prepare($conn, "UPDATE wa_api SET token = ?");
            mysqli_stmt_bind_param($update_query, "s", $new_token);
            $success = mysqli_stmt_execute($update_query);
            mysqli_stmt_close($update_query);
        } else {
            // Insert new token if no row exists
            $insert_query = mysqli_prepare($conn, "INSERT INTO wa_api (token) VALUES (?)");
            mysqli_stmt_bind_param($insert_query, "s", $new_token);
            $success = mysqli_stmt_execute($insert_query);
            mysqli_stmt_close($insert_query);
        }

        if ($success) {
            $_SESSION['flash_message'] = 'Token WhatsApp berhasil diperbarui.';
        } else {
            $error = 'Gagal memperbarui token WhatsApp. Silakan coba lagi.';
        }
        header("Location: index_settings.php");
        exit;
    } else {
        $error = 'Token tidak boleh kosong.';
    }
}
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
                    <div class="d-flex justify-content-center gap-2">
                        <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include "../includes/footer.php"; ?>