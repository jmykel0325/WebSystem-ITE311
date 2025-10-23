<?php
// Check timezone BEFORE and AFTER setting it
echo "<!DOCTYPE html><html><head><title>Timezone Check</title></head><body>";
echo "<h1>Web Server Timezone Check</h1>";

echo "<h2>BEFORE Setting:</h2>";
echo "<p><strong>Timezone:</strong> " . date_default_timezone_get() . "</p>";
echo "<p><strong>Time:</strong> " . date('Y-m-d H:i:s') . "</p>";

// Force set timezone
date_default_timezone_set('Asia/Manila');

echo "<h2>AFTER Setting:</h2>";
echo "<p><strong>Timezone:</strong> " . date_default_timezone_get() . "</p>";
echo "<p><strong>Time:</strong> " . date('Y-m-d H:i:s') . "</p>";

echo "<h2>Check php.ini:</h2>";
echo "<p><strong>php.ini timezone:</strong> " . ini_get('date.timezone') . "</p>";

if (date_default_timezone_get() === 'Asia/Manila') {
    echo "<p style='color: green; font-weight: bold;'>✅ Can be set to Asia/Manila!</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Cannot set timezone!</p>";
}
echo "</body></html>";
