# Artisan User Management

![Travis (.org)](https://img.shields.io/travis/kusmayadi/artisan-user-management?style=flat-square)

Laravel package for managing users via artisan console.

## Installation 

You can install the package via composer: 

`composer require kusmayadi\artisan-user`

## Usage

*   **Add User**

    `php artisan user:add` 

*   **List User**

    `php artisan user:list`

*   **Edit User**

    `php artisan user:edit {userID}`

    or 

    `php artisan user:edit {userEmail}`

    or 

    `php artisan user:edit`

*   **Delete User**

    `php artisan user:delete {userID}`

    or 

    `php artisan user:delete {userEmail}`

    or 

    `php artisan user:delete`

*   **Reset Password**

    `php artisan user:reset-password {userID}`

    or 

    `php artisan user:reset-password {userEmail}`

    or 

    `php artisan user:reset-password`

## License

The MIT License (MIT). Please see [LICENSE file](LICENSE) for more information.
