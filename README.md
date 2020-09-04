# VGreen Application - Vuser Module

## Installation
```bash
composer require willvrd/vuser-module
```

## Steps

    1. Run authentication:
```bash
php artisan ui vue --auth
```

    2. Run NPM to compile your fresh scaffolding
```bash
npm run dev
```

    3. Run Migrations
```bash
php artisan migrate
```

    4. Install Laravel Passport
```bash
php artisan passport:install
```

    5. Go to config/auth.php
        - Set the driver option of the api authentication guard to passport
        - Change the model from provider to: Modules\Vuser\Entities\User::class,

    6. Add the service provider in your config/app.php file:
```php
'providers' => [
// ...
Spatie\Permission\PermissionServiceProvider::class,
];
```
    7. Publish the migration and the config/permission.php config file 
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

    8. Go to config/permission.php
        - Change 'model_has_permissions' => 'user_has_permissions',  
        - Change 'model_has_roles' => 'user_has_roles', 
        - Change 'model_morph_key' => 'user_id',

    9. Run Migrations
```bash
php artisan migrate
```
        
    10. Run this command: 
```bash
php artisan user:role-permission:init
```

## Frontend Configuration

    1. Go to App\Providers and change this:
        public const HOME = '/backend';


## Assets Module

    1. cd Modules/Vuser
    2. npm install
    3. npm run dev
    4. In your view add:
    
```html   
@section('scripts-modules')
    <script src="{{ mix('js/vuser.js') }}"></script>
@stop
 ```
## Laravel Passport

    - Consuming Your API With JavaScript:
https://laravel.com/docs/7.x/passport#consuming-your-api-with-javascript

## Laravel Permissions

    1. Installation:    
https://docs.spatie.be/laravel-permission/v3/installation-laravel/
   
## End Points

Route Base: `https://yourhost.com/api/user/v1/`

* #### User

* #### Auth

* #### Role

* #### Permission

## Backend

    ### Pages
    
        Index:  http://mysite/en/backend/user/users
        name:   locale.admin.user.users.index

        Index:  http://mysite/en/backend/user/roles
        name:   locale.admin.user.roles.index
