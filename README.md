## General information - Stack of technologies

### Server
The server utilizes NGINX with the latest package version, and it can be locally updated to one of the latest versions if needed.

### PHP
PHP version 8.2.7-fpm is employed to cater to all the requirements for running Laravel.

## First run
1. Clone the repository to any folder on your PC.
2. Navigate to the cloned folder ```cd wizmeek-backend-L11```
3. Start Docker Desktop.
4. Run command ```make up``` and wait until docker will download, extract and run all the containers. Make sure that all the containers are running well in Docker Desktop.
5. Run command ```make bash``` to get into /bin/bash of the PHP container where you may use Composer
6. When you are in the /bin/bash mode, change you directory to the working one by running ```cd api```
7. Now you have to install all the dependencies. Run ```composer update``` or ```composer install``` and wait until the installation will be finished.
8. Once you are done with composer, you may use standard Laravel commands in the container's cli, such as ```php artisan cache:clear``` and others.

## Setting up database
Compose environment provides a MariaDB v 10.5.18 Server and a PhpAdmin Client. So, here is how you set it up:
1. 

## Second run
1. Open the PMS-Backend folder in terminal and run ```make up```. It will set all the environment in the Docker Desktop.
2. To get into a PHP's /bin/bash run ```make bash```.
3. You are ready to work.

## Finishing work
1. Press ```Ctrl/Cmd + D``` to exit /bin/bash mode.
2. Run ```make down```. It will stop and delete all the containers from Docker Desktop.

## List of Make commads
1. ```make up``` - starts all the containers in your environment.
2. ```make bash``` - enters the /bin/bash mode for using composer and standard laravel commands
3. ```make nginx-reload``` - reloads the server's container without stopping the environment (use when server's configuration should be changed during working with the project).
4. ```make down``` - stops and removes all the containers from your Docker Desktop.