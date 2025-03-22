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
5. Run command ```make bash``` to get into /bin/bash of the PHP container where you may use Composer.
6. Now you have to install all the dependencies. Run ```composer update``` or ```composer install``` and wait until the installation will be finished.
7. Once you are done with composer, you may use standard Laravel commands in the container's cli, such as ```php artisan cache:clear``` and others.

## Setting up database
Compose environment provides a MariaDB v 10.5.18 Server and a PhpAdmin Client. So, here is how you set it up:
1. Open a PhpAdmin in browser by accessing http://localhost:8102/ in the browser.
2. Enter credentials:
    - host: mariadb
    - user: root
    - password: root
3. Create a new data base with any name (e.x. ```homestead```).
4. Now you need to update your ```.env``` file with DB connection:
```
DB_CONNECTION=mysql
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=homestead // depending on a name provided in previous step
DB_USERNAME=root
DB_PASSWORD=root
```
5. Laravel cahces environmental variables fro better performance. You may want to run ```php artisan config:clear``` from the php bash mode before next steps.
6. The entire DB development and any changes are described in migrations. Run ```php artisan:migrate``` to recreate empty database.

## Log and Cache permissions
If you try to access ```localhost:8876``` in the browser it may complain on the lack of permissions. This is because docker needs a write access for the log files and cache files. If it happened, give full rights to the ```storage``` and ```bootstrap``` folders:
1. Enter bash mode ```make bash```
2. Run this command ```chmod -R 777 /var/www/api/storage /var/www/api/bootstrap/cache```

## Generating APP Key
Laravel APP key is not provided in the ```.env.example``` file, so you may want to generate it. Use this command:
```php artisan key:generate```
You may also need to clean cache of the project and cache new config:
```
php artisan config:clear
php artisan cache:clear
php artisan config:cashe
```
## Vite manifest
1. Enter bash mode ```make bash```
2. Composer environment provide you with installation of ```NodeJS v20.18.3``` and ```npm v11.1.0```
3. Install npm dependencies ```npm install```
4. Run ```npm run build```

## Creating Admin User
1. Enter a bash mode ```make bash```.
2. Run Tinker ```php artisan tinker```.
3. Just paste this inside the tinker and press enter:
```
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('root')
]);
```
4. However this will create a user, you would still need to give the user a right to access admin panel. You need to manually change ```role``` column in the DB to ```admin```, as it is protected from changes in the User model.
5. Now you may login to an admin account!

## Watching SASS/JS/BLADE changes
1. Enter bash mode ```make up```
2. Run ```npm run dev``` - this will automatically watch for any updates in ```*.scss``` files, ```*.js``` files and ```*.blade.php```

## Second run
1. Open the ```wizmeek-backend-L11``` folder in terminal and run ```make up```. It will set all the cached environment in the Docker Desktop.
2. To get into a PHP's /bin/bash run ```make bash```.
3. You are ready to work!

## Finishing work
1. Press ```Ctrl/Cmd + D``` to exit /bin/bash mode.
2. Run ```make down```. It will stop and delete all the containers from Docker Desktop.

## List of Make commads
1. ```make up``` - starts all the containers in your environment.
2. ```make bash``` - enters the /bin/bash mode for using composer and standard laravel commands
3. ```make nginx-reload``` - reloads the server's container without stopping the environment (use when server's configuration should be changed during working with the project).
4. ```make down``` - stops and removes all the containers from your Docker Desktop.