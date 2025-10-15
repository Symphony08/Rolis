<?php

/**
 * Warranty Expiration Checker - Cron Job
 * 
 * This script checks for warranties that will expire in 7 days
 * and sends WhatsApp notifications to customers.
 * 
 * Usage: php cron_warranty_check.php
 * Cron: 0 9 * * * /usr/bin/php /path/to/cron_warranty_check.php
 */

// Load database configuration
require_once 'admin-site/includes/db.php';

// Load configuration
$config = require_once 'warranty_config.php';
$warrantyConfig = $config['warranty_config'];
$waConfig = $config['wa_config'];
$errorConfig = $config['error_config'];
$serverConfig = $config['server_config'];

// Configuration from config file
$DAYS_BEFORE_EXPIRY = $warrantyConfig['days_before_expiry'];
$LOG_FILE = $warrantyConfig['log_file'];
$TEST_MODE = $warrantyConfig['test_mode'];

// Make variables available globally
$GLOBALS['waConfig'] = $waConfig;
$GLOBALS['TEST_MODE'] = $TEST_MODE;
$GLOBALS['serverConfig'] = $serverConfig;

// Set timezone from server config
if (isset($serverConfig['timezone'])) {
    date_default_timezone_set($serverConfig['timezone']);
    logMessage("Timezone set to: " . $serverConfig['timezone']);
}

/**
 * Log messages to file
 */
function logMessage($message)
{
    global $LOG_FILE;

    // Ensure log file exists and is writable
    if (!file_exists($LOG_FILE)) {
        // Create the log file with initial header
        $initialContent = "# Warranty Check System Log File\n";
        $initialContent .= "# Created: " . date('Y-m-d H:i:s') . "\n";
        $initialContent .= "# This file contains warranty check activities and results\n";
        $initialContent .= "# ================================================\n\n";
        file_put_contents($LOG_FILE, $initialContent);
        chmod($LOG_FILE, 0644); // Set appropriate permissions
    }

    // Check if log file is writable
    if (!is_writable($LOG_FILE)) {
        echo "ERROR: Log file is not writable: $LOG_FILE\n";
        return;
    }

    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($LOG_FILE, $logEntry, FILE_APPEND | LOCK_EX);
    echo $logEntry;
}

/**
 * Send WhatsApp message using RuangWA API
 */
function sendWhatsAppMessage($phone, $message, $token)
{
    global $waConfig, $errorConfig;

    $retryCount = 0;
    $maxRetries = $errorConfig['max_retries'];

    while ($retryCount < $maxRetries) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $waConfig['api_url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $waConfig['timeout'],
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
            $retryCount++;
            if ($retryCount < $maxRetries) {
                logMessage("Retry $retryCount/$maxRetries for phone $phone: $curlError");
                sleep($errorConfig['retry_delay']);
                continue;
            }
            return ['success' => false, 'error' => $curlError];
        }

        $data = json_decode($response, true);
        if ($data && isset($data['result']) && $data['result'] === 'true') {
            return ['success' => true, 'response' => $data];
        } else {
            $retryCount++;
            if ($retryCount < $maxRetries) {
                logMessage("Retry $retryCount/$maxRetries for phone $phone: API response invalid");
                sleep($errorConfig['retry_delay']);
                continue;
            }
            return ['success' => false, 'error' => 'API response invalid: ' . $response];
        }
    }

    return ['success' => false, 'error' => 'Max retries exceeded'];
}

/**
 * Format date to Indonesian format
 */
function formatIndonesianDate($date)
{
    $bulan_indonesia = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];

    $bulan = date("m", strtotime($date));
    return date("d", strtotime($date)) . " " . $bulan_indonesia[$bulan] . " " . date("Y", strtotime($date));
}

/**
 * Display server configuration information
 */
function displayServerConfig()
{
    global $serverConfig;

    logMessage("=== SERVER CONFIGURATION ===");
    logMessage("Cron Path: " . $serverConfig['cron_path']);
    logMessage("Script Path: " . $serverConfig['script_path']);
    logMessage("Cron Schedule: " . $serverConfig['cron_schedule']);
    logMessage("Timezone: " . $serverConfig['timezone']);
    logMessage("Log Retention: " . $serverConfig['log_retention_days'] . " days");
    logMessage("=============================");
}

/**
 * Generate cron command using server configuration
 */
function generateCronCommand()
{
    global $serverConfig;

    $cronCommand = $serverConfig['cron_schedule'] . " " . $serverConfig['cron_path'] . " " . $serverConfig['script_path'];
    logMessage("Generated Cron Command: " . $cronCommand);
    logMessage("To set up cron job, add this to your crontab:");
    logMessage("crontab -e");
    logMessage("Then add: " . $cronCommand);

    return $cronCommand;
}

/**
 * Clean up old log files based on retention policy
 */
function cleanupOldLogs()
{
    global $serverConfig, $LOG_FILE;

    $retentionDays = $serverConfig['log_retention_days'];
    $cutoffDate = date('Y-m-d', strtotime("-$retentionDays days"));

    // Get log file directory
    $logDir = dirname($LOG_FILE);
    $logPattern = $logDir . '/*.log';

    $logFiles = glob($logPattern);
    $deletedCount = 0;

    foreach ($logFiles as $logFile) {
        if (file_exists($logFile)) {
            $fileDate = date('Y-m-d', filemtime($logFile));
            if ($fileDate < $cutoffDate) {
                if (unlink($logFile)) {
                    $deletedCount++;
                    logMessage("Cleaned up old log file: " . basename($logFile));
                }
            }
        }
    }

    if ($deletedCount > 0) {
        logMessage("Log cleanup completed. Deleted $deletedCount old log files (older than $retentionDays days)");
    }
}

/**
 * Main warranty check function
 */
function checkWarrantyExpiry()
{
    global $conn, $DAYS_BEFORE_EXPIRY;

    logMessage("Starting warranty expiry check...");

    // Calculate the target date (7 days from now)
    $targetDate = date('Y-m-d', strtotime("+$DAYS_BEFORE_EXPIRY days"));

    // Query to get warranties expiring in 7 days
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

    logMessage("Found $count warranties expiring on $targetDate");

    if ($count == 0) {
        logMessage("No warranties expiring in $DAYS_BEFORE_EXPIRY days. Check completed.");
        return;
    }

    // Check if test mode is enabled
    if ($GLOBALS['TEST_MODE']) {
        logMessage("TEST MODE ENABLED - No actual messages will be sent");
        logMessage("Would send notifications to $count customers");
        return;
    }

    // Get WhatsApp API token
    $tokenQuery = $conn->query("SELECT token FROM wa_api LIMIT 1");
    if (!$tokenQuery || $tokenQuery->num_rows == 0) {
        logMessage("ERROR: WhatsApp API token not found in database");
        return;
    }

    $tokenData = $tokenQuery->fetch_assoc();
    $token = $tokenData['token'];

    if (empty($token)) {
        logMessage("ERROR: WhatsApp API token is empty");
        return;
    }

    $successCount = 0;
    $errorCount = 0;

    // Process each expiring warranty
    foreach ($expiringWarranties as $warranty) {
        $phone = $warranty['no_hp'];
        $customerName = $warranty['nama_pelanggan'];
        $productName = $warranty['nama_produk'];
        $productPrice = number_format($warranty['harga'], 0, ',', '.');
        $warrantyDate = formatIndonesianDate($warranty['tanggal_garansi']);
        $engineNumber = $warranty['nomor_mesin'];
        $bodyNumber = $warranty['nomor_body'];
        $color = $warranty['warna'];

        // Create WhatsApp message
        $message = "*ROLIS - Roda Listrik*
Jl. KH. Samanhudi No.42, Sungai Pinang Dalam, Kec. Sungai Pinang, Kota Samarinda, Kalimantan Timur 75117, Indonesia

Yth. $customerName,

*PEMBERITAHUAN PENTING - GARANSI AKAN BERAKHIR*

Kami ingin mengingatkan bahwa garansi produk Anda akan berakhir dalam $DAYS_BEFORE_EXPIRY hari.

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

        logMessage("Sending notification to $customerName ($phone) for product: $productName");

        $result = sendWhatsAppMessage($phone, $message, $token);

        if ($result['success']) {
            $successCount++;
            logMessage("✅ WhatsApp sent successfully to $customerName ($phone)");
        } else {
            $errorCount++;
            logMessage("❌ Failed to send WhatsApp to $customerName ($phone): " . $result['error']);
        }

        // Add small delay between messages to avoid rate limiting
        sleep($GLOBALS['waConfig']['delay_between_messages']);
    }

    logMessage("Warranty check completed. Success: $successCount, Errors: $errorCount");
}

// Main execution
try {
    logMessage("=== WARRANTY EXPIRY CHECK STARTED ===");

    // Display server configuration
    displayServerConfig();

    // Generate cron command for reference
    generateCronCommand();

    // Clean up old log files
    cleanupOldLogs();

    if (!$conn) {
        logMessage("ERROR: Database connection failed");
        exit(1);
    }

    checkWarrantyExpiry();

    logMessage("=== WARRANTY EXPIRY CHECK COMPLETED ===");
} catch (Exception $e) {
    logMessage("ERROR: " . $e->getMessage());
    exit(1);
} finally {
    if ($conn) {
        $conn->close();
    }
}
