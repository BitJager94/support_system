# support_system
Support System API

To run the project do the following:

-run composer install --ignore-platform-reqs

-Configure .env file as necessary (configure database credits and mail trap credits).

-Run php artisan serve and php artisan schedule:work (in seperate terminals).



to make a request:

-first attemp to register a new user (customer or employee).

-login using registered user credits and get the token key.

-select bearer token (assuming you are using insomnia) and copy-paste the token key with (bearer) prefix.
