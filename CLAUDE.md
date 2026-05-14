# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

### Development Commands
- `composer test` - Run all tests using Pest
- `composer lint` - Run Pint code formatter and PHPStan static analysis
- `composer check` - Run both lint and test commands
- `vendor/bin/pest` - Run tests directly
- `vendor/bin/pint` - Run code formatter only
- `vendor/bin/phpstan` - Run static analysis only

### Laravel Boost Commands
- `php artisan boost:install` - Install Laravel Boost MCP server and AI guidelines
- `php artisan boost:mcp` - Start the MCP server (used by AI editors)
- `php artisan boost:start` - Start Laravel Boost services

### Test Structure
- Unit tests: `tests/Unit/`
- Feature tests: `tests/Feature/`
- Architecture tests: `tests/ArchTest.php`
- Run specific test: `vendor/bin/pest tests/Feature/ExampleTest.php`

## Architecture

Laravel Boost is an MCP (Model Context Protocol) server that provides AI tools for Laravel development. The codebase follows Laravel package conventions.

### Core Components

1. **MCP Server (`src/Mcp/Boost.php`)**
   - Main MCP server class extending Laravel\Mcp\Server
   - Auto-discovers tools, resources, and prompts from their respective directories
   - Provides 15+ specialized tools for Laravel development

2. **Tools (`src/Mcp/Tools/`)**
   - Individual MCP tools like DatabaseQuery, ListRoutes, Tinker, etc.
   - Each tool is auto-discovered and registered with the MCP server
   - Tools can be excluded/included via config

3. **Installation System (`src/Install/`)**
   - Detects code environments (VSCode, Cursor, Claude Code, etc.)
   - Installs MCP server configuration and AI guidelines
   - Handles both system and project-level installations

4. **Guidelines (`src/Install/GuidelineComposer.php`)**
   - Composes AI guidelines for various Laravel ecosystem packages
   - Includes guidelines for Laravel, Livewire, Filament, Inertia, etc.
   - Guidelines are versioned and customizable

5. **Browser Logging (`src/Services/BrowserLogger.php`)**
   - Captures JavaScript errors and logs from the browser
   - Provides `@boostJs` Blade directive for frontend logging
   - Logs to `storage/logs/browser.log`

### Key Patterns

- **Auto-discovery**: Tools, resources, and prompts are automatically discovered using DirectoryIterator
- **Contract-based**: Uses contracts (`Agent`, `McpClient`) for different code environment integrations
- **Configuration-driven**: MCP tools and features can be enabled/disabled via config
- **Laravel integration**: Deeply integrated with Laravel's service container and configuration system

## Configuration

- Main config: `config/boost.php`
- Pint (code style): `pint.json` - Uses strict Laravel coding standards
- PHPUnit: `phpunit.xml.dist` - Defines test suites (Unit, Feature, Arch)
- Custom guidelines: Place `.blade.php` files in `.ai/guidelines/` directory

## Dependencies

- PHP 8.1+ required
- Laravel 10.x, 11.x, or 12.x support
- Uses `laravel/mcp` for MCP server functionality
- Uses `laravel/roster` for package detection
- Pest for testing framework