# support_system
Support System API

To run the project do the following:

1-run composer install --ignore-platform-reqs
1-Configure .env file as necessary (configure database credits and mail trap credits).
2-Run php artisan serve and php artisan schedule:work (in seperate terminals).


to make a request
1-first attemp to register a new user (customer or employee).
2-login using registered user credits and get the token key.
3-select bearer token (assuming you are using insomnia) and copy-paste the token key with (bearer) prefix.
