
## Package for encrypting and decrypting model attributes for Laravel using openssl

## Key Features

* Encrypt, Decrypt database fields easily
* Minimal configuration
* Include searching encrypted data using the following:
    `whereEncrypted` and `orWhereEncrypted`
* uses openssl for encrypting and decrypting fields

## Requirements

* Laravel: >= 7
* PHP: >= 8.0

## Schema Requirements

Encrypted values are usually longer than plain text values, sometimes much longer.
You may find that the column widths in your database tables need to be altered to
store the encrypted values generated by this package.

We highly recommend to alter your column types to `TEXT` or `LONGTEXT`

## Installation

### Step 1: Composer

Via Composer command line:

```bash
$ composer require sandbox-dev/laravel-database-encryption
```

### Step 2: Add ServiceProvider to your app/config.php file (Laravel 5.4 or below)
Add the service provider to the providers array in the config/app.php config file as follows:
```php
    'providers' => [
        Sandbox\DBEncryption\Providers\DBEncryptionServiceProvider::class,
    ],
```

## Usage

Allow to use encryption on your application by adding the `APP_ENCRYPTION` key on your `.env` file
```dotenv
	APP_ENCRYPTION=true
```

Use the `EncryptedAttribute` trait in any Eloquent model that you wish to apply encryption
to and define a `protected $encryption` array containing a list of the attributes to encrypt.

For example:

```php
    
    use Sandbox\DBEncryption\Traits\EncryptedAttribute;

    class User extends Eloquent {
        use EncryptedAttribute;
       
        /**
         * The attributes that should be encrypted on save.
         *
         * @var array
         */
        protected $encryption = [
            'first_name', 'last_name'
        ];
    }
```

By including the `EncryptedAttribute` trait, the `setAttribute()`, `getAttribute()` and `getAttributeFromArray()`
methods provided by Eloquent are overridden to include an additional step.

### Searching Encrypted Fields Example:
Searching encrypted field can be done by calling the `whereEncrypted` and `orWhereEncrypted` functions
similar to laravel eloquent `where` and `orWhere`.


```php
    namespace App\Http\Controllers;

    use App\User;
    class UsersController extends Controller {
        public function index(Request $request)
        {
            $user = User::whereEncrypted('first_name','john')
                        ->orWhereEncrypted('last_name','!=','Doe')->firstOrFail();
            
            return $user;
        }
    }
```

### Encrypt your current data
 If you have current data in your database you can encrypt it with the: 
    `php artisan encryption:encryptModel 'App\User'` command.
    
 Additionally you can decrypt it using the:
    `php artisan encryption:decryptModel 'App\User'` command.

 Note: You must implement first the `Encryptable` trait and set `$encryption` attributes

### Exists and Unique Validation Rules
 If you are using exists and unique rules with encrypted values replace it with exists_encrypted and unique_encrypted 
    ```php      
      $validator = validator(['email'=>'foo@bar.com'], ['email'=>'exists_encrypted:users,email']);
      $validator = validator(['email'=>'foo@bar.com'], ['email'=>'unique_encrypted:users,email']);
    ```

## Frequently Asked Question
#### Can I search encrypted data?
YES! You will able to search on attributes which are encrypted by this package because.
If you need to search on data then use the `whereEncrypted` and `orWhereEncrypted` function:
```
    User::whereEncrypted('email','test@gmail.com')->orWhereEncrypted('email','test2@gmail.com')->firstOrFail();
```
It will automatically added on the eloquent once the model uses `EncryptedAttribute`

#### Can I encrypt all my `User` model data?
Aside from IDs you can encrypt everything you wan't

For example:
Logging-in on encrypted email
```
$user = User::whereEncrypted('email','test@gmail.com')->filter(function ($item) use ($request) {
        return Hash::check($password, $item->password);
    })->where('active',1)->first();
```