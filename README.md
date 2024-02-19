Installation
    
    docker-compose up --build (use -d to deatach)
    docker exec -it php82-container bash
    composer install
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load
    

Testing

    php bin/phpunit

PHPStan

    vendor/bin/phpstan analyse src

PHP CS-Fixer install

    mkdir -p tools/php-cs-fixer
    composer require --working-dir=tools/php-cs-fixer friendsofphp/php-cs-fixer

Run
    
    tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src

PHP-CS-Fixer:

    vendor/bin/php-cs-fixer fix src

API Documentation:

    http://localhost:10100/api - available for anon users.

Credentials to DB

    DATABASE_URL="mysql://root:root@mysql57-service:3306/test?serverVersion=5.7.32&;charset=utf8mb4"