<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>



## Laravel MiniBlog Application - API PART
* MiniBlog API application built with Laravel 8!

## Built With
* [Laravel 8 ](https://laravel.com/)
* [Laravel Passport](https://laravel.com/docs/8.x/passport)
* [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger)

## Installation
1. Clone the repository

### `git clone https://github.com/mohamedamine001/blog-api`

2. Move into the folder with this command

### `cd blog-api`

3. Install project dependencies

### `composer install`

4. Generate Key

### `php artisan key:generate`

5. Rename `.env.example` to `.env` 

6. edit `.env` file with appropriate credential for your database server. Just edit these two parameter(DB_USERNAME, DB_PASSWORD).   

7. Create `blog` Database and then do a database migration using this command : 

### `php artisan migrate`

8. Passport Install

### `php artisan passport:install`

9. Make Auth System

### `php artisan make:auth`

10. Run server : 

### `php artisan serve`

Then go to `http://127.0.0.1:8000/api/documentation` from your browser and see the api documentation.

## React project link (Frontend) : 

[React Repository](https://github.com/mohamedamine001/blog-front).
