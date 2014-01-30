# Exceptional Validation

This package throws `ValidationException` when validation error occur.

## Installation

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `revati/routing`.

    "require": {
        "laravel/framework": "4.1.*",
        "revati/validation": "dev-master"
    }

Next, update Composer from the Terminal:

    composer update

Once this operation completes, the final step is to add the service provider. Open `app/config/app.php`, and add a new item to the providers array.

    // 'Illuminate\Validation\ValidationServiceProvider',
    'Revati\Validation\ValidationServiceProvider'

That's it! You're all set to go.

## Usage

Whatever where you want to validate something
```php
// ...

// Validate $input against $rules like usual.
// No need to catch response because if errors will occur, exception
// will be thrown and necessary response returned.
Validator::make($input, $rules);

// If got to this point validation passed
$user = User::create($input);

// ...
```

If error occur, fallowing responses will be returned:

```php
// For "regular" request
return \Redirect::back()->withInput()->withErrors($e->getErrors());

// For ajax request
return $e->getErrors()->toJson();

// $e stands for ValidationException witch (for now) only has one method
// to return errors -> getErrors.
```

## Customize

Those responses are defined as closures in packages config file, so just by publishing config file you can modify what your app should be doing in witch case.

    php artisan config:publish revati/validation
