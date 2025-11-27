<?php
session_start();
include "../includes/db.php";
require_once "../controllers/ProductController.php";

use Adminsite\Controllers\ProductController;

$productController = new ProductController();

$id = $_GET['id'];
$data = $productController->edit($id)->fetch_assoc();

// Ambil data untuk autocomplete
$merek_list = [];
$model_list = [];
$warna_list = [];

$result_merek = $conn->query("SELECT DISTINCT value FROM merek ORDER BY value ASC");
if ($result_merek) {
  while ($row = $result_merek->fetch_assoc()) {
    $merek_list[] = $row['value'];
  }
}

$result_model = $conn->query("SELECT DISTINCT value FROM model ORDER BY value ASC");
if ($result_model) {
  while ($row = $result_model->fetch_assoc()) {
    $model_list[] = $row['value'];
  }
}

$result_warna = $conn->query("SELECT DISTINCT value FROM warna ORDER BY value ASC");
if ($result_warna) {
  while ($row = $result_warna->fetch_assoc()) {
    $warna_list[] = $row['value'];
  }
}

// Ambil nilai merek, model, warna saat ini
$current_merek = '';
$current_model = '';
$current_warna = '';

$stmt_merek = $conn->prepare("SELECT value FROM merek WHERE id_merek = ?");
$stmt_merek->bind_param("i", $data['merek_id']);
$stmt_merek->execute();
$result = $stmt_merek->get_result();
if ($result->num_rows > 0) {
  $current_merek = $result->fetch_assoc()['value'];
}
$stmt_merek->close();

$stmt_model = $conn->prepare("SELECT value FROM model WHERE id_model = ?");
$stmt_model->bind_param("i", $data['model_id']);
$stmt_model->execute();
$result = $stmt_model->get_result();
if ($result->num_rows > 0) {
  $current_model = $result->fetch_assoc()['value'];
}
$stmt_model->close();

$stmt_warna = $conn->prepare("SELECT value FROM warna WHERE id_warna = ?");
$stmt_warna->bind_param("i", $data['warna_id']);
$stmt_warna->execute();
$result = $stmt_warna->get_result();
if ($result->num_rows > 0) {
  $current_warna = $result->fetch_assoc()['value'];
}
$stmt_warna->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  try {
    $conn->begin_transaction();

    // Proses merek
    $merek_input = trim(strip_tags($_POST['merek']));
    $stmt = $conn->prepare("SELECT id_merek FROM merek WHERE LOWER(value) = LOWER(?)");
    $stmt->bind_param("s", $merek_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $merek_id = $result->fetch_assoc()['id_merek'];
    } else {
      $stmt_insert = $conn->prepare("INSERT INTO merek (value) VALUES (?)");
      $stmt_insert->bind_param("s", $merek_input);
      $stmt_insert->execute();
      $merek_id = $conn->insert_id;
      $stmt_insert->close();
    }
    $stmt->close();

    // Proses model
    $model_input = trim(strip_tags($_POST['model']));
    $stmt = $conn->prepare("SELECT id_model FROM model WHERE LOWER(value) = LOWER(?)");
    $stmt->bind_param("s", $model_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $model_id = $result->fetch_assoc()['id_model'];
    } else {
      $stmt_insert = $conn->prepare("INSERT INTO model (value) VALUES (?)");
      $stmt_insert->bind_param("s", $model_input);
      $stmt_insert->execute();
      $model_id = $conn->insert_id;
      $stmt_insert->close();
    }
    $stmt->close();

    // Proses warna
    $warna_input = trim(strip_tags($_POST['warna']));
    $stmt = $conn->prepare("SELECT id_warna FROM warna WHERE LOWER(value) = LOWER(?)");
    $stmt->bind_param("s", $warna_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $warna_id = $result->fetch_assoc()['id_warna'];
    } else {
      $stmt_insert = $conn->prepare("INSERT INTO warna (value) VALUES (?)");
      $stmt_insert->bind_param("s", $warna_input);
      $stmt_insert->execute();
      $warna_id = $conn->insert_id;
      $stmt_insert->close();
    }
    $stmt->close();

    // Ubah POST data untuk controller
    $_POST['merek_id'] = $merek_id;
    $_POST['model_id'] = $model_id;
    $_POST['warna_id'] = $warna_id;

    $productController->update($id, $_POST, $_FILES['foto']);
    
    $conn->commit();

    $_SESSION['flash_message'] = 'Produk berhasil diperbarui.';
    header("Location: index_products.php");
    exit;

  } catch (Exception $e) {
    $conn->rollback();
    $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
    header("Location: edit_products.php?id=" . $id);
    exit;
  }
}

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-6">
      <div class="card rounded-4 shadow-sm p-4">
        <div class="mb-4">
          <h3 class="fw-bold">✏ Edit Produk</h3>
          <p class="text-muted">Ubah informasi produk sepeda atau motor listrik.</p>
        </div>
        <form method="POST" enctype="multipart/form-data" novalidate onsubmit="prepareHarga()">
          
          <!-- Merek -->
          <div class="mb-3 row align-items-center">
            <label for="merek" class="col-sm-4 col-form-label fw-semibold">Merek <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <input type="text" name="merek" id="merek" class="form-control rounded-3 autocomplete-input" required placeholder="Pilih atau ketik merek baru" value="<?= htmlspecialchars($current_merek) ?>">
              <div class="invalid-feedback">Merek wajib diisi.</div>
            </div>
          </div>

          <!-- Model/Tipe -->
          <div class="mb-3 row align-items-center">
            <label for="model" class="col-sm-4 col-form-label fw-semibold">Model/Tipe <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <input type="text" name="model" id="model" class="form-control rounded-3 autocomplete-input" required placeholder="Pilih atau ketik model baru" value="<?= htmlspecialchars($current_model) ?>">
              <div class="invalid-feedback">Model/Tipe wajib diisi.</div>
            </div>
          </div>

          <!-- Warna -->
          <div class="mb-3 row align-items-center">
            <label for="warna" class="col-sm-4 col-form-label fw-semibold">Warna <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <input type="text" name="warna" id="warna" class="form-control rounded-3 autocomplete-input" required placeholder="Pilih atau ketik warna baru" value="<?= htmlspecialchars($current_warna) ?>">
              <div class="invalid-feedback">Warna wajib diisi.</div>
            </div>
          </div>

          <!-- Jenis -->
          <div class="mb-3 row align-items-center">
            <label for="jenis" class="col-sm-4 col-form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <select name="jenis" id="jenis" class="form-select rounded-3" required>
                <option value="MOTOR" <?= $data['jenis'] == 'MOTOR' ? 'selected' : '' ?>>MOTOR</option>
                <option value="SEPEDA" <?= $data['jenis'] == 'SEPEDA' ? 'selected' : '' ?>>SEPEDA</option>
              </select>
              <div class="invalid-feedback">Jenis wajib dipilih.</div>
            </div>
          </div>

          <!-- Harga -->
          <div class="mb-3 row align-items-center">
            <label for="harga" class="col-sm-4 col-form-label fw-semibold">Harga <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-text">Rp.</span>
                <input type="text" name="harga" id="harga" class="form-control rounded-3" value="<?= htmlspecialchars($data['harga']) ?>" required inputmode="numeric" pattern="[0-9,]*">
                <div class="invalid-feedback">Harga wajib diisi.</div>
              </div>
            </div>
          </div>

          <!-- Deskripsi -->
          <div class="mb-3 row align-items-center">
            <label for="deskripsi" class="col-sm-4 col-form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <textarea name="deskripsi" id="deskripsi" class="form-control rounded-3" rows="3" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
              <div class="invalid-feedback">Deskripsi wajib diisi.</div>
            </div>
          </div>

          <!-- Foto -->
          <div class="mb-3 row align-items-center">
            <label for="foto" class="col-sm-4 col-form-label fw-semibold">Foto</label>
            <div class="col-sm-8">
              <input type="file" name="foto" id="foto" class="form-control rounded-3" accept="image/*">
              <div id="image-preview" class="mt-2">
                <?php if (!empty($data['foto'])): ?>
                  <img src="<?= htmlspecialchars($data['foto']) ?>" class="img-fluid rounded-3" style="max-width: 200px; max-height: 200px;" id="current-image">
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Update</button>
            <a href="index_products.php" class="btn btn-danger rounded-3 px-4 py-2 flex-grow-1">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<style>
  /* Custom styling untuk autocomplete dropdown - Compact Version */
  .ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    overflow-x: hidden;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #ced4da;
    background: white;
    z-index: 1050 !important;
    font-family: 'Poppins', sans-serif;
    margin-top: 2px !important;
  }

  /* Scrollbar styling untuk dropdown */
  .ui-autocomplete::-webkit-scrollbar {
    width: 6px;
  }

  .ui-autocomplete::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 0 8px 8px 0;
  }

  .ui-autocomplete::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
  }

  .ui-autocomplete::-webkit-scrollbar-thumb:hover {
    background: #555;
  }

  .ui-menu-item {
    padding: 0;
    margin: 0;
    list-style: none;
  }

  .ui-menu-item-wrapper {
    padding: 10px 16px;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-size: 0.875rem;
    color: #495057;
    border: none;
    transition: all 0.15s ease;
    display: block;
  }

  .ui-menu-item-wrapper:hover {
    background: #007bff !important;
    color: white !important;
  }

  .ui-state-active,
  .ui-state-focus {
    background: #007bff !important;
    color: white !important;
    border: none !important;
    margin: 0 !important;
  }

  .ui-helper-hidden-accessible {
    display: none;
  }

  /* Remove default jQuery UI styling */
  .ui-widget-content {
    border: none;
  }

  .ui-menu .ui-menu-item {
    margin: 0;
    padding: 0;
  }
</style>

<script>
  // Autocomplete data from PHP
  const merekList = <?= json_encode($merek_list) ?>;
  const modelList = <?= json_encode($model_list) ?>;
  const warnaList = <?= json_encode($warna_list) ?>;

  // Setup autocomplete untuk Merek
  $("#merek").autocomplete({
    source: merekList,
    minLength: 0
  }).focus(function() {
    $(this).autocomplete("search", "");
  });

  // Setup autocomplete untuk Model
  $("#model").autocomplete({
    source: modelList,
    minLength: 0
  }).focus(function() {
    $(this).autocomplete("search", "");
  });

  // Setup autocomplete untuk Warna
  $("#warna").autocomplete({
    source: warnaList,
    minLength: 0
  }).focus(function() {
    $(this).autocomplete("search", "");
  });

  // Preview gambar
  document.getElementById('foto').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.style.maxWidth = '200px';
        img.style.maxHeight = '200px';
        img.classList.add('rounded-3');
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        preview.appendChild(img);
      };
      reader.readAsDataURL(file);
    } else {
      const preview = document.getElementById('image-preview');
      preview.innerHTML = '';
      <?php if (!empty($data['foto'])): ?>
        const img = document.createElement('img');
        img.src = '<?= htmlspecialchars($data['foto']) ?>';
        img.style.maxWidth = '200px';
        img.style.maxHeight = '200px';
        img.classList.add('rounded-3');
        preview.appendChild(img);
      <?php endif; ?>
    }
  });

  // Format harga dengan koma
  const hargaInput = document.getElementById('harga');

  function formatNumberWithCommas(value) {
    return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function unformatNumber(value) {
    return value.replace(/,/g, "");
  }

  // Format initial value
  hargaInput.value = formatNumberWithCommas(unformatNumber(hargaInput.value));

  hargaInput.addEventListener('input', function(e) {
    const cursorPosition = this.selectionStart;
    const originalLength = this.value.length;

    let rawValue = unformatNumber(this.value.replace(/[^\d,]/g, ''));
    const formattedValue = formatNumberWithCommas(rawValue);

    this.value = formattedValue;

    const newLength = formattedValue.length;
    const diff = newLength - originalLength;
    this.selectionStart = this.selectionEnd = cursorPosition + diff;
  });

  function prepareHarga() {
    hargaInput.value = unformatNumber(hargaInput.value);
  }
</script>

<?php include "../includes/footer.php"; ?>