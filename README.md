To run the project do the following:

-run composer install --ignoreplatform-reqs.
-run php artisan jwt:secret (to generate JWT key).
-configure .env according to your resources (database and mail trap).
-run php artisan migrate
-run php artisan serve
-run php artisan schedule:work (in seperate terminal).


To use the API:

-register a new user and then login to the system using the same credites to get the token.
-select the athorization type to bearer token (supposing you are usign insomnia) and set the key to the token and rhe prefix to (bearer).

