***Running the Project (Docker + Laravel Sail)

Prerequisites
Docker Desktop (Mac / Windows / Linux)

1. Clone the repository
    git clone https://github.com/Rondinelly32/starwars-laravel-vue
    cd starwars-laravel-vue
    
2. Create environment file
    cp .env.example .env
    
3. Install PHP dependencies (inside Docker)
    docker run --rm \
    -v "$PWD":/var/www/html \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install

4. Start the containers
    ./vendor/bin/sail up -d
    Now you should be able to access http://localhost:8080

5. Generate App Key
    ./vendor/bin/sail artisan key:generate

6. Run migrations
    ./vendor/bin/sail artisan migrate

7. Install frontend dependencies
    ./vendor/bin/sail npm install

8. Run and leave this running:
    ./vendor/bin/sail npm run dev

9. Run scheduler and queues
    ./vendor/bin/sail artisan queue:work
    ./vendor/bin/sail artisan schedule:work




