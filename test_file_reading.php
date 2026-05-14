<?php

// Test script to debug file reading in installFileMcp

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\File;

echo "=== File Reading Debug Test ===\n\n";

// Create a test config file
$testConfigPath = 'test_mcp.json';
$existingConfig = [
    'mcpServers' => [
        'linear' => [
            'command' => 'linear-mcp',
            'args' => ['--token', 'test-token-123']
        ]
    ]
];

echo "1. Creating test config file:\n";
file_put_contents($testConfigPath, json_encode($existingConfig, JSON_PRETTY_PRINT));
echo file_get_contents($testConfigPath) . "\n\n";

echo "2. Testing File facade exists() check:\n";
$exists = File::exists($testConfigPath);
echo "File::exists() result: " . ($exists ? 'true' : 'false') . "\n\n";

if ($exists) {
    echo "3. Testing File facade get() method:\n";
    $content = File::get($testConfigPath);
    echo "Raw content: " . $content . "\n\n";
    
    echo "4. Testing json_decode():\n";
    $decoded = json_decode($content, true);
    echo "Decoded result: ";
    var_dump($decoded);
    echo "\n";
    
    echo "5. Testing json_decode() ?: [] fallback:\n";
    $config = json_decode($content, true) ?: [];
    echo "Final config: ";
    var_dump($config);
    echo "\n";
} else {
    echo "❌ File::exists() returned false - this could be the issue!\n";
}

// Cleanup
if (file_exists($testConfigPath)) {
    unlink($testConfigPath);
}