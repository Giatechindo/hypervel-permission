# Hypervel Permission

[![Latest Version](https://img.shields.io/packagist/v/giatechindo/hypervel-permission.svg?style=flat-square)](https://packagist.org/packages/giatechindo/hypervel-permission)
[![Total Downloads](https://img.shields.io/packagist/dt/giatechindo/hypervel-permission.svg?style-flat-square)](https://packagist.org/packages/giatechindo/hypervel-permission)
[![License](https://img.shields.io/packagist/l/giatechindo/hypervel-permission.svg?style-flat-square)](https://packagist.org/packages/giatechindo/hypervel-permission)

Role-based permission handling for Hypervel framework with ID and UUID support.

## Structure

```php
hypervel-permission/
├── composer.json
├── config/
│   └── permission.php
├── database/
│   └── migrations/
│       ├── 2025_05_12_000000_create_permission_tables.php
│       └── 2025_05_12_000001_add_uuid_support.php
├── src/
│   ├── Contracts/
│   │   ├── Permission.php
│   │   └── Role.php
│   ├── Exceptions/
│   │   ├── PermissionAlreadyExists.php
│   │   ├── PermissionDoesNotExist.php
│   │   ├── RoleAlreadyExists.php
│   │   └── RoleDoesNotExist.php
│   ├── Models/
│   │   ├── Permission.php
│   │   └── Role.php
│   ├── PermissionRegistrar.php
│   ├── PermissionServiceProvider.php
│   └── Traits/
│       ├── HasPermissions.php
│       └── HasRoles.php
├── tests/
│   ├── bootstrap.php
│   ├── PermissionTest.php
│   └── RoleTest.php
├── LICENSE
├── README.md
└── phpunit.xml.dist
```

## Features

- Role and permission management
- Support for both ID and UUID
- Morph relationships for flexible model associations
- Clean, maintainable code with comprehensive tests
- Inspired by Spatie's Laravel Permission

## Installation

1. Install the package via Composer:

```bash
composer require giatechindo/hypervel-permission
```

2. Copy the configuration file to your project:

```bash
cp vendor/giatechindo/hypervel-permission/config/permission.php config/permission.php
```

3. Copy the migration files to your project's migration directory:

```bash
cp vendor/giatechindo/hypervel-permission/database/migrations/*.php database/migrations/
```

4. (Optional) If you want to use UUID instead of auto-incrementing IDs, add the following to your `.env` file:

```bash
PERMISSION_USE_UUID=true
```

5. Run the migrations to create the necessary tables:

```bash
php artisan migrate
```

If you want to start fresh, you can drop all tables and re-run migrations:

```bash
php artisan migrate:fresh
```

## Configuration

The configuration file `config/permission.php` allows you to customize the package behavior. Key options include:

- **`use_uuid`**: Set to `true` to use UUIDs instead of auto-incrementing IDs.
- **`models`**: Define custom model classes for `Role` and `Permission`.
- **`table_names`**: Customize the database table names used by the package.

Example configuration:

```php
return [
    'use_uuid' => env('PERMISSION_USE_UUID', false),
    'models' => [
        'permission' => Giatechindo\HypervelPermission\Models\Permission::class,
        'role' => Giatechindo\HypervelPermission\Models\Role::class,
    ],
    'table_names' => [
        'permissions' => 'permissions',
        'roles' => 'roles',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],
];
```

## Usage

### Adding the Traits

To enable role and permission management on your models (e.g., `User`), add the `HasRoles` and `HasPermissions` traits:

```php
use Giatechindo\HypervelPermission\Traits\HasRoles;
use Giatechindo\HypervelPermission\Traits\HasPermissions;

class User extends Model
{
    use HasRoles, HasPermissions;
}
```

### Creating Roles and Permissions

You can create roles and permissions programmatically:

```php
use Giatechindo\HypervelPermission\Models\Role;
use Giatechindo\HypervelPermission\Models\Permission;

// Create a role
$adminRole = Role::create(['name' => 'admin']);

// Create a permission
$editPostPermission = Permission::create(['name' => 'edit-post']);
```

### Assigning Roles and Permissions

Assign roles or permissions to a user:

```php
$user = User::find(1);

// Assign a role
$user->assignRole('admin');

// Assign a permission
$user->givePermissionTo('edit-post');
```

### Checking Roles and Permissions

Check if a user has a specific role or permission:

```php
// Check if user has a role
if ($user->hasRole('admin')) {
    echo "User is an admin!";
}

// Check if user has a permission
if ($user->hasPermissionTo('edit-post')) {
    echo "User can edit posts!";
}
```

### Using Middleware

You can protect routes using the package's middleware to restrict access based on roles or permissions:

```php
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin', [AdminController::class, 'index']);
});

Route::group(['middleware' => ['permission:edit-post']], function () {
    Route::get('/edit-post', [PostController::class, 'edit']);
});
```

### Morph Relationships

The package supports morph relationships, allowing you to assign roles and permissions to any model, not just `User`. For example:

```php
use Giatechindo\HypervelPermission\Models\Permission;

$team = Team::find(1);
$team->givePermissionTo('manage-team');
```

## Testing

The package includes PHPUnit tests. To run the tests:

```bash
composer test
```

To generate a test coverage report:

```bash
composer test-coverage
```

## Requirements

- PHP >= 8.2
- Hypervel Framework ^0.1
- Ramsey UUID ^4.7
- Symfony Filesystem ^7.2
- Hyperf Database ^3.1
- PHP Dotenv ^5.6

For development and testing:
- Hyperf Testing ~3.1.0
- PHPUnit ^10.5
- PHPUnit Code Coverage ^10.1
- Mockery ^1.6

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Credits

This package is inspired by [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) and adapted for the Hypervel framework by [Giatechindo](https://github.com/giatechindo).