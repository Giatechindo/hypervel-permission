# Hypervel Permission

[![Latest Version](https://img.shields.io/packagist/v/giatechindo/hypervel-permission.svg?style=flat-square)](https://packagist.org/packages/giatechindo/hypervel-permission)
[![Total Downloads](https://img.shields.io/packagist/dt/giatechindo/hypervel-permission.svg?style-flat-square)](https://packagist.org/packages/giatechindo/hypervel-permission)
[![License](https://img.shields.io/packagist/l/giatechindo/hypervel-permission.svg?style-flat-square)](https://packagist.org/packages/giatechindo/hypervel-permission)

Role-based permission handling for Hypervel framework with ID and UUID support.

## Struktur

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

```bash
composer require giatechindo/hypervel-permission
```

