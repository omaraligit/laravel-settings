# Laravel-settings

## A solution to manage your applications settings using a database

 * Simple key-value storage
 * Support multi-level array (dot delimited keys) structure.


## Installation

1. Install package

    ```bash
    composer require omaraligit/laravel-settings
    ```

1. Edit config/app.php (Skip this step if you are using laravel 5.5+)

    service provider:

    ```php
    OmarAliGit\Settings\LaravelSettingsServiceProvider::class,
    ```


1. Create settings table

    ```bash
    php artisan vendor:publish
    // you can change the database table name from the laravel-settings.php config file
    php artisan migrate
    ```

## Usage
1. saveing a array (make sure the array is a key value array)
    ```php
    $array = [
        "paypal"=>[
            "real"=>[
                "client_id"=>"paypal_real_client_id",
                "secret_id"=>"paypal_real_secret_id",
            ],
            "sandbox"=>[
                "client_id"=>"paypal_sandbox_client_id",
                "secret_id"=>"paypal_sandbox_secret_id",
            ]
        ]
    ];
    \OmarAliGit\Settings\Facades\Settings::save("payment",$array);
    /**
     * will save like this in the database 
     * payment.paypal.real.client_id=paypal_real_client_id
     * payment.paypal.real.secret_id=paypal_real_secret_id ...
     */
    ```

1. saveing a string
    ```php
    \OmarAliGit\Settings\Facades\Settings::save("foo","bar");
    /**
     * will save like this in the database 
     * foo=bar
     */
    ```
1. deleting a key from the database secound option is false by default (make it true to delete sub keys) e.g. if you have foo.bar and foo.tar and the key you want to delete is foo the the 2 keys will be deleted other ways if falsee you need to enter the key.subkey.othersubkey if you want a specifice key deletion 
    ```php
    \OmarAliGit\Settings\Facades\Settings::delete($key, false);
    ```
1. getting a key from the database
    ```php
    \OmarAliGit\Settings\Facades\Settings::get($key)
    /**
     * will return a array like this if key has sub keys e.g. payment from erlier
     *   [
     *      "payment"=>[
     *          "paypal"=>[
     *              "real"=>[
     *                  "client_id"=>"paypal_real_client_id",
     *                  "secret_id"=>"paypal_real_secret_id",
     *              ],
     *              "sandbox"=>[
     *                  "client_id"=>"paypal_sandbox_client_id",
     *                  "secret_id"=>"paypal_sandbox_secret_id",
     *              ]
     *          ]
     *      ]
     *  ]
     * or will return a string if the key is specifice
     */
    ```
    1.updating a key or keys if array is given (it'sthe same as saving with a third parameter that will create a key if not found by default it's false so a NotFoundException will be thrown if key is not created befour)
    ```php
    \OmarAliGit\Settings\Facades\Settings::update($key,$array,true);
    ```
    
    