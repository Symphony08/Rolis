<?php

/**
 * Warranty Check Configuration
 * 
 * This file contains configuration settings for the warranty check system.
 * Update these values according to your server setup.
 */

// WhatsApp API Configuration
// These fields can be configured later when setting up the server
$WA_CONFIG = [
    'api_url' => 'https://app.ruangwa.id/api/send_message',
    'timeout' => 30,
    'retry_attempts' => 3,
    'delay_between_messages' => 2, // seconds
];

// Warranty Check Configuration
$WARRANTY_CONFIG = [
    'days_before_expiry' => 7,
    'log_file' => 'warranty_check.log',
    'enable_notifications' => true,
    'test_mode' => false, // Set to true for testing without sending actual messages
];

// Database Configuration (inherited from admin-site/includes/db.php)
// Make sure your .env file is properly configured with:
// DB_HOST=your_host
// DB_USER=your_username  
// DB_PASS=your_password
// DB_NAME=rolis2
// DB_PORT=3306

// Server Configuration Fields (to be filled later)
$SERVER_CONFIG = [
    'cron_path' => 'C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe', // Path to PHP executable
    'script_path' => 'C:\laragon\www\Rolis\cron_warranty_check.php', // Full path to the cron script
    'cron_schedule' => '0 9 * * *', // Daily at 9 AM
    'timezone' => 'Asia/Makassar', // Adjust according to your timezone
    'log_retention_days' => 30, // How long to keep log files
];

// Notification Templates
$NOTIFICATION_TEMPLATES = [
    'warranty_reminder' => [
        'subject' => 'PEMBERITAHUAN PENTING - GARANSI AKAN BERAKHIR',
        'days_before' => 7,
        'template' => 'warranty_reminder_template'
    ],
    'warranty_expired' => [
        'subject' => 'GARANSI TELAH BERAKHIR',
        'days_after' => 0,
        'template' => 'warranty_expired_template'
    ]
];

// Error Handling Configuration
$ERROR_CONFIG = [
    'max_retries' => 3,
    'retry_delay' => 5, // seconds
    'log_errors' => true,
    'send_error_notifications' => false, // Set to true to send error notifications to admin
    'admin_phone' => '', // Admin phone number for error notifications
];

// Return configuration array
return [
    'wa_config' => $WA_CONFIG,
    'warranty_config' => $WARRANTY_CONFIG,
    'server_config' => $SERVER_CONFIG,
    'notification_templates' => $NOTIFICATION_TEMPLATES,
    'error_config' => $ERROR_CONFIG
];
