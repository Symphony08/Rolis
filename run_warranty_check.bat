@echo off
REM Warranty Check Batch File for Windows
REM This file can be used with Windows Task Scheduler

echo Starting Warranty Check...
echo Date: %date%
echo Time: %time%

REM Change to the project directory
cd /d "C:\laragon\www\Rolis"

REM Run the warranty check script
php cron_warranty_check.php

echo Warranty Check completed.
pause
