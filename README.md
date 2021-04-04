# Social Media/blog API

A simple blog api allowing users to preform basic CRUD operations on a blog post.
Users can add images to the blog post which are stored on Google Cloud Storage.

Posts can be liked and commented on.

## Setup

Software required:

- PHP >= 7.3
- PHP Extensions (probably already installed except mbstring)
  - OpenSSL PHP
  - PDO (mysql) PHP
  - Mbstring PHP
    - Windows: Edit your php.ini file and uncomment the line that reads: extension = php_mbstring.dll
    - Linux (ubuntu) apt install php-mbstring
- Composer (<https://getcomposer.org/download/>)
  - php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  - php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  - php composer-setup.php
  - php composer-setup.php --install-dir=bin

## Running the Application

### Install the dependencies using composer

```shell
composer install
```

### Run the migrations

```shell
php artisan migrate
```

### Run the database seeder for sample data

```shell
php db:seed
```

### Start the php server in the root of the project

```shell
php -S localhost:8080 -t public
```

## Testing the Application

To test the application, from the root of the project, run /vendor/bin/phpunit. All tests in the test folder will run. It should take about a minute.

## API Routes

API routes and their documentation found on this page created via postman: <https://documenter.getpostman.com/view/7315089/TzCQaRr3>
