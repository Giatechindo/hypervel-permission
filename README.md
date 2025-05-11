Giatechindo Hypervel Permission
A role and permission management package for Hypervel, built by the Giatechindo Community. Supports both ID and UUID identifiers.
Installation

Install via Composer:
composer require giatechindo/hypervel-permission


Add the service provider to config/app.php:
'providers' => [
    Giatechindo\HypervelPermission\HypervelPermissionServiceProvider::class,
],


Publish configuration and migrations:
php artisan vendor:publish --tag=config
php artisan vendor:publish --tag=migrations


Run migrations:
php artisan migrate


Add the HasRoles trait to your User model:
use Giatechindo\HypervelPermission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}



Usage
Create Roles and Permissions
use Giatechindo\HypervelPermission\Models\Role;
use Giatechindo\HypervelPermission\Models\Permission;

Role::create(['name' => 'admin']);
Permission::create(['name' => 'edit artikel']);

Assign Roles
$user = User::find(1);
$user->assignRole('admin');

Check Permissions
if (auth()->user()->hasPermissionTo('edit artikel')) {
    echo "User can edit articles!";
}

Configuration
Edit config/hypervel-permission.php to change table names or switch between ID and UUID:
'identifier_type' => 'uuid', // or 'id'

Contributing
Contributions are welcome! Please submit a pull request or open an issue on GitHub.
License
This package is open-sourced under the MIT License.
