# Wallet Challenge
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Task
I try to make online wallet application and the goal is simple: Receive and send money,
we will Receive money via webhooks from PayTech and Acme Banks and we will send money via sending xml file to bank apis.


## installation

As pre-requisities,you are expected to have [Git - Composer - nodejs] installed

Run the following commends:

```sh

# clone the repositry and navigate to the project 

git clone https://github.com/AhmedFawzy900/Wallet_Challenge.git
cd Wallet_Challenge

# install dependancies
composer install 
npm install

# build assets
npm run build

# create the envirnment file and generate an application key
cp .env.example .env
php artisan key:generate

# run the database migration
php artisan migrate


# (optional) seed the database with simple data
php artisan db:seed

# running the application
php artisan serve

```sh

```
