<?php

// Test script to reproduce the MCP configuration bug

require_once 'vendor/autoload.php';

// Create existing config with Linear MCP server (simulating user's setup)
$existingConfig = [
    'mcpServers' => [
        'linear' => [
            'command' => 'linear-mcp',
            'args' => ['--token', 'test-token-123'],
            'env' => []
        ]
    ]
];

echo "=== MCP Configuration Bug Test ===\n\n";

echo "1. Original config with Linear MCP:\n";
echo json_encode($existingConfig, JSON_PRETTY_PRINT) . "\n\n";

echo "2. Simulating data_set() behavior from installFileMcp():\n";

// This is exactly what happens in CodeEnvironment::installFileMcp()
$config = $existingConfig;
$mcpKey = 'mcpServers';
$key = 'laravel-boost';

$newServer = collect([
    'command' => 'php',
    'args' => ['./artisan', 'boost:mcp'],
    'env' => []
])->filter()->toArray();

echo "Before data_set():\n";
echo json_encode($config, JSON_PRETTY_PRINT) . "\n";

// This is the actual line from the bug (line 180 in CodeEnvironment.php)
data_set($config, "{$mcpKey}.{$key}", $newServer);

echo "After data_set(\$config, \"mcpServers.laravel-boost\", \$newServer):\n";
echo json_encode($config, JSON_PRETTY_PRINT) . "\n";

$hasLinear = isset($config['mcpServers']['linear']);
$hasBoost = isset($config['mcpServers']['laravel-boost']);

echo "Analysis:\n";
echo "- Linear MCP preserved: " . ($hasLinear ? 'YES ✓' : 'NO ✗') . "\n";
echo "- Boost MCP added: " . ($hasBoost ? 'YES ✓' : 'NO ✗') . "\n";

if (!$hasLinear) {
    echo "\n❌ BUG CONFIRMED: data_set() is destroying existing servers!\n";
} else {
    echo "\n✅ data_set() working correctly\n";
}