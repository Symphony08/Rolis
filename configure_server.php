<?php

/**
 * Server Configuration Helper
 * 
 * This script helps you configure the server settings for the warranty check system.
 * It will guide you through setting up the correct paths and schedules.
 */

echo "=== WARRANTY CHECK SERVER CONFIGURATION ===\n\n";

// Load current configuration
$config = require_once 'warranty_config.php';
$serverConfig = $config['server_config'];

echo "Current Server Configuration:\n";
echo "=============================\n";
echo "Cron Path: " . $serverConfig['cron_path'] . "\n";
echo "Script Path: " . $serverConfig['script_path'] . "\n";
echo "Cron Schedule: " . $serverConfig['cron_schedule'] . "\n";
echo "Timezone: " . $serverConfig['timezone'] . "\n";
echo "Log Retention: " . $serverConfig['log_retention_days'] . " days\n\n";

echo "Configuration Instructions:\n";
echo "==========================\n";
echo "1. Update warranty_config.php with your actual server paths\n";
echo "2. Set the correct PHP executable path\n";
echo "3. Set the full path to your cron_warranty_check.php script\n";
echo "4. Configure your preferred timezone\n";
echo "5. Set your preferred cron schedule\n\n";

echo "Example Configuration for Different Systems:\n";
echo "============================================\n";

echo "Linux/Unix (Ubuntu/Debian):\n";
echo "  cron_path: '/usr/bin/php'\n";
echo "  script_path: '/var/www/html/Rolis/cron_warranty_check.php'\n";
echo "  timezone: 'Asia/Jakarta'\n\n";

echo "Linux/Unix (CentOS/RHEL):\n";
echo "  cron_path: '/usr/bin/php'\n";
echo "  script_path: '/var/www/html/Rolis/cron_warranty_check.php'\n";
echo "  timezone: 'Asia/Jakarta'\n\n";

echo "Windows (XAMPP):\n";
echo "  cron_path: 'C:\\xampp\\php\\php.exe'\n";
echo "  script_path: 'C:\\xampp\\htdocs\\Rolis\\cron_warranty_check.php'\n";
echo "  timezone: 'Asia/Jakarta'\n\n";

echo "Windows (Laragon):\n";
echo "  cron_path: 'C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe'\n";
echo "  script_path: 'C:\\laragon\\www\\Rolis\\cron_warranty_check.php'\n";
echo "  timezone: 'Asia/Jakarta'\n\n";

echo "Cron Schedule Examples:\n";
echo "=======================\n";
echo "Daily at 9 AM:     '0 9 * * *'\n";
echo "Daily at 8:30 AM:  '30 8 * * *'\n";
echo "Twice daily:       '0 9,18 * * *'\n";
echo "Weekdays only:     '0 9 * * 1-5'\n";
echo "Every 6 hours:     '0 */6 * * *'\n\n";

echo "Timezone Examples:\n";
echo "==================\n";
echo "Jakarta:    'Asia/Jakarta'\n";
echo "Makassar:   'Asia/Makassar'\n";
echo "Bali:       'Asia/Makassar'\n";
echo "Singapore:  'Asia/Singapore'\n";
echo "UTC:        'UTC'\n\n";

echo "Generated Cron Command:\n";
echo "=======================\n";
$cronCommand = $serverConfig['cron_schedule'] . " " . $serverConfig['cron_path'] . " " . $serverConfig['script_path'];
echo "Current: " . $cronCommand . "\n\n";

echo "To set up the cron job:\n";
echo "=======================\n";
echo "1. Edit your crontab: crontab -e\n";
echo "2. Add this line: " . $cronCommand . "\n";
echo "3. Save and exit\n";
echo "4. Verify with: crontab -l\n\n";

echo "For Windows users:\n";
echo "==================\n";
echo "Use Windows Task Scheduler instead of cron\n";
echo "Use the provided run_warranty_check.bat file\n";
echo "Set trigger to run daily at your preferred time\n\n";

echo "Testing Your Configuration:\n";
echo "============================\n";
echo "1. Run: php test_warranty_check.php\n";
echo "2. Run: php cron_warranty_check.php\n";
echo "3. Check the log file: warranty_check.log\n\n";

echo "=== CONFIGURATION COMPLETE ===\n";
echo "Update warranty_config.php with your actual server settings!\n";
