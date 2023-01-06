Hello ! I've used Symfony 6.2 to create this "product and review" app.

Follow these steps to run and try the application :

1.Run "composer require" to install dependencies.

2.Create a database named "fizzup"

3.Create a ".env.local" file to set up your connection to a database.
I'm using MySQL, so my .env.local has this line :

DATABASE_URL="mysql://MyDatabaseUsername:MyPassword@localhost:3306/fizzup?serverVersion=8&charset=utf8mb4"

Replace MyDatabaseUsername and MyPassword with appropriate values.

4.Use Doctrine to generate and fill the database with Fixtures :
Run symfony console make:migration
Run symfony console doctrine:migrations:migrate
Run symfony console doctrine:fixtures:load

5.Run the server with the command symfony server:start

6.Checkout the app in your browser, following the link https://127.0.0.1:8000 or localhost:8000

7.I still need a lot of practice but I took great pleasure (and time) to create this app.

Thank you for trying it out and feel free to give me your feedback :)
