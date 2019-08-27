# shortenlink
create example simple shortenlink laravel

1.  create file .env then copy from configuration in file .env.example
2.  Load all dependency with composer install
3.  Give access read write in directory storage with chmod -R 777 storage
4.  run docker-compose up -d
5.  run php artisan config:clear
6.  Create all schema table with php artisan migrate
7.  Generate startup data register with run php artisan db:seed
8.  Finally access http://localhost:8080/

You can run unit testing basically 
1.  Run composer test

Have enjoy