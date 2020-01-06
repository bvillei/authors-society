# Authors Society

##### Simple app to store authors together with books and display the stored data in a table.
- The application is very simple. Now you are able to create a new author together with a new book. So if we want to store book with the same author then the author entry will be duplicated in the database.
    - Possibility to add new book to an already existing author: create a new form (or refactor the actual one) where we can add new book and have the option to select an author from the database or create a new author.
- To avoid double entries I made the book title unique. In a more realistic case an ISBN number would be a better solution, but I do not have that field.


## Technologies
- Backend: Laravel Framework 6 (PHP 7.2)
- Frontend: Blade
- Database: MySQL
- Database for Testing: sqlite

## Setup
- Clone the repository with git clone
- Copy .env.example file to .env and edit database credentials there
- Run composer install
- Run php artisan key:generate
- Run php artisan migrate --seed (it has some seeded data for your testing)
- Run php artisan serve (to run the application)
