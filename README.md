## Wallet Engine
After cloning the repository, and set up the environment. ie the env and serve the application
For the implementation proper, I used laravel passport for the authentication,
we will need to run "php artisan migrate" to set up the tables and "php artisan passport:install"
to set up mostly encryption keys for access tokens.

To be able to create a wallet,  the user will need to register during this process a wallet will be created on successful registration. the created wallet defaults to 0 balance and inactive.

To be able to able to access the endpoints, it is required to pass the token that is generated when the user registers or logs in, Click on Authorization and select Bearer token, and pass the generated token to the input field.

Endpoints<br>
Registration: http://127.0.0.1:8000/api/register <br>
Login: http://127.0.0.1:8000/api/login<br>
Credit: http://127.0.0.1:8000/api/user/{user_id}/credit-wallet <br>
Debit: http://127.0.0.1:8000/api/user/{user_id}/debit-wallet <br>
Activate: http://127.0.0.1:8000/api/user/{user_id}/activate<br>
Deactivate: http://127.0.0.1:8000/api/user/{user_id}/deactivate <br>
Wallet:  http://127.0.0.1:8000/api/user/{user_id}/wallet <br>
Logout: http://127.0.0.1:8000/api/logout <br>


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
