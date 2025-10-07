<?php

/**
 * Test Script for Warranty Check System
 * 
 * This script allows you to test the warranty check functionality
 * without setting up a full cron job.
 */

require_once 'admin-site/includes/db.php';

// Test configuration
$TEST_CONFIG = [
    'test_mode' => true,
    'test_phone' => '08123456789', // Replace with your test phone number
    'test_days' => 7, // Test for warranties expiring in 7 days
    'dry_run' => true, // Set to false to actually send messages
];

echo "=== WARRANTY CHECK TEST SCRIPT ===\n";
echo "Test Mode: " . ($TEST_CONFIG['test_mode'] ? 'ON' : 'OFF') . "\n";
echo "Dry Run: " . ($TEST_CONFIG['dry_run'] ? 'ON' : 'OFF') . "\n";
echo "Test Phone: " . $TEST_CONFIG['test_phone'] . "\n\n";

// Test database connection
if (!$conn) {
    echo "❌ Database connection failed\n";
    exit(1);
}
echo "✅ Database connection successful\n";

// Test WhatsApp API token
$tokenQuery = $conn->query("SELECT token FROM wa_api LIMIT 1");
if (!$tokenQuery || $tokenQuery->num_rows == 0) {
    echo "❌ WhatsApp API token not found\n";
    exit(1);
}

$tokenData = $tokenQuery->fetch_assoc();
$token = $tokenData['token'];

if (empty($token)) {
    echo "❌ WhatsApp API token is empty\n";
    exit(1);
}
echo "✅ WhatsApp API token found\n";

// Test query for warranties
$targetDate = date('Y-m-d', strtotime("+{$TEST_CONFIG['test_days']} days"));
echo "🔍 Checking for warranties expiring on: $targetDate\n";

$query = "
    SELECT 
        t.id_transaksi,
        t.tanggal_garansi,
        t.nomor_mesin,
        t.nomor_body,
        t.warna,
        p.nama as nama_pelanggan,
        p.no_hp,
        pr.nama as nama_produk,
        pr.harga
    FROM transaksi t
    JOIN pelanggan p ON t.pelanggan_id = p.id_pelanggan
    JOIN produk pr ON t.produk_id = pr.id_produk
    WHERE t.tanggal_garansi = ?
    ORDER BY t.tanggal_garansi ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $targetDate);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$expiringWarranties = $result->fetch_all(MYSQLI_ASSOC);
$count = count($expiringWarranties);

echo "📊 Found $count warranties expiring on $targetDate\n\n";

if ($count == 0) {
    echo "ℹ️  No warranties found expiring in {$TEST_CONFIG['test_days']} days\n";
    echo "💡 You can create a test transaction with warranty date: $targetDate\n";
    exit(0);
}

// Display found warranties
echo "📋 WARRANTIES FOUND:\n";
echo str_repeat("-", 80) . "\n";

foreach ($expiringWarranties as $index => $warranty) {
    echo "Warranty #" . ($index + 1) . ":\n";
    echo "  Customer: " . $warranty['nama_pelanggan'] . "\n";
    echo "  Phone: " . $warranty['no_hp'] . "\n";
    echo "  Product: " . $warranty['nama_produk'] . "\n";
    echo "  Price: Rp " . number_format($warranty['harga'], 0, ',', '.') . "\n";
    echo "  Warranty Date: " . $warranty['tanggal_garansi'] . "\n";
    echo "  Engine: " . $warranty['nomor_mesin'] . "\n";
    echo "  Body: " . $warranty['nomor_body'] . "\n";
    echo "  Color: " . $warranty['warna'] . "\n";
    echo str_repeat("-", 80) . "\n";
}

// Test message creation
echo "📱 TESTING MESSAGE CREATION:\n";
$warranty = $expiringWarranties[0]; // Use first warranty for testing

$phone = $TEST_CONFIG['test_phone']; // Use test phone
$customerName = $warranty['nama_pelanggan'];
$productName = $warranty['nama_produk'];
$productPrice = number_format($warranty['harga'], 0, ',', '.');
$warrantyDate = date('d F Y', strtotime($warranty['tanggal_garansi']));
$engineNumber = $warranty['nomor_mesin'];
$bodyNumber = $warranty['nomor_body'];
$color = $warranty['warna'];

$message = "*ROLIS - Roda Listrik*
Jl. KH. Samanhudi No.42, Sungai Pinang Dalam, Kec. Sungai Pinang, Kota Samarinda, Kalimantan Timur 75117, Indonesia

Yth. $customerName,

*PEMBERITAHUAN PENTING - GARANSI AKAN BERAKHIR*

Kami ingin mengingatkan bahwa garansi produk Anda akan berakhir dalam {$TEST_CONFIG['test_days']} hari.

Detail Produk:
- Produk: $productName
- Harga: Rp $productPrice
- Garansi Berakhir: $warrantyDate

Spesifikasi:
- Warna: $color
- Nomor Mesin: $engineNumber
- Nomor Body: $bodyNumber

*Tindakan yang Disarankan:*
1. Periksa kondisi produk Anda
2. Jika ada kerusakan, segera hubungi kami untuk klaim garansi
3. Setelah masa garansi berakhir, biaya perbaikan akan ditanggung sendiri

Hubungi kami segera jika memerlukan bantuan atau ingin melakukan klaim garansi.

Terima kasih atas kepercayaan Anda.

*ROLIS - Roda Listrik*
📞 Hubungi kami untuk informasi lebih lanjut";

echo "Message preview (first 200 characters):\n";
echo substr($message, 0, 200) . "...\n\n";

if ($TEST_CONFIG['dry_run']) {
    echo "🔒 DRY RUN MODE - No actual messages will be sent\n";
    echo "✅ Test completed successfully\n";
} else {
    echo "📤 SENDING TEST MESSAGE...\n";

    // Send test message
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $phone . '&message=' . urlencode($message),
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    if ($curlError) {
        echo "❌ cURL Error: $curlError\n";
    } else {
        echo "📡 HTTP Code: $httpCode\n";
        echo "📨 Response: $response\n";

        $data = json_decode($response, true);
        if ($data && isset($data['result']) && $data['result'] === 'true') {
            echo "✅ Test message sent successfully!\n";
        } else {
            echo "❌ Failed to send test message\n";
        }
    }
}

echo "\n=== TEST COMPLETED ===\n";
echo "💡 To run the actual cron job, use: php cron_warranty_check.php\n";
echo "💡 Server configuration is loaded from warranty_config.php\n";
echo "💡 Update server_config in warranty_config.php with your actual paths\n";
echo "💡 The cron command will be generated automatically when you run the main script\n";

$conn->close();
