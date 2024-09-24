## General information - Stack of technologies

### Server
The server utilizes NGINX with the latest package version, and it can be locally updated to one of the latest versions if needed.

Configuration (may be changed locally in ./_docker/nginx/conf.d/nginx.conf):
```
server {
    root /var/www/api/public;
    location / {
        if ($request_method = 'OPTIONS') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'Origin, Content-Type, Accept, Authorization';
            add_header 'Access-Control-Max-Age' 1728000;
            add_header 'Content-Type' 'text/plain; charset=utf-8';
            add_header 'Content-Length' 0;
            return 204;
        }

        add_header 'Access-Control-Allow-Origin' '*';
        add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS';
        add_header 'Access-Control-Allow-Headers' 'Origin, Content-Type, Accept, Authorization';
        try_files $uri /index.php?$query_string;
    }
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
    client_max_body_size 10M;
}
```

### PHP
PHP version 8.2.7-fpm is employed to cater to all the requirements for running Laravel.

Configuration (may be changen in ./_docker/php/php.ini):
```
cgi.fix_pathinfo=0
max_execution_time = 1000
max_input_time = 1000
memory_limit=4G
```

## First run
1. Clone the repository to any folder on your PC.
2. Run command ```cd PMS-Backend```
3. Start Docker Desktop.
4. Run command ```make up``` and wait until docker will download, extract and run all the containers. Make sure that all the containers are running well in Docker Desktop.
5. Run command ```make bash``` to get into /bin/bash of the PHP container where you may use Composer
6. When you are in the /bin/bash mode, change you directory to the working one by running ```cd api```
7. Now you have to install all the dependencies. Run ```composer update``` or ```composer install``` and wait until the installation will be finished.
8. Once you are done with composer, you may use standard Laravel commands in the container's cli, such as ```php artisan cache:clear``` and others.
9. If during testing API routes it complains about access or authorization run ```chmod -R 777 .``` in the /bin/bash mode.

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
3. ```nginx-reload``` - reloads the server's container without stopping the environment (use when server's configuration should be changed during working with the project).
4. ```make down``` - stops and removes all the containers from your Docker Desktop.

## Technical Documentation
Visit the [technical documentation](tech_doc/README.md) to learn more about project.
