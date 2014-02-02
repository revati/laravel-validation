# Exceptional Validation

Validation support in two ways.

- ValidationException throwing and configurable responses
- BaseValidator abstract class with handy functionality

## Possibilities

- No need to worry about what happens if validation fails (ValidationException).
- Can dynamicly assign modifiers to validation rules:
```php
// In ResourceValidator that extends Revati\Validation\BaseValidator
$rules = [
    'title' => 'required'
    'endDate'  => 'after:[afterDate]'
];

$resourceValidator->addModifier('date', 'afterDate', Input::get('startDate'));

// So [afterDate] will be replaced with 3th parameter
```

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

## BaseValidator

- Create seperate Validator file for each Resource (should extend `Revati\Validation\BaseValidator`)
- Set all rules that should be applied to Resource

### Get specific rules

- `getRules` method accepts one parameter (array) with list of all rules that should extracted from global $rules.

```php
// Will get only title rules
$validator->getRules(array('title'));

// Will get title rules and overwrite description rules
$validator->getRules(array('title', 'description' => 'min:200'));
```

### Dynamic rules modifiers

In url specify modifier hook like so: `[modifier]`.

```php
// Change hook value
// Modifier should bee passed without square brackets
$validator->addModifier('field', 'min', 20);

// As field name can be passed `*` to search in all field rules.
$validator->addModifier('*', 'max', 100);

// Modifiers can be be grouped
$validator->addModifier('field', 'min|max', 10);
```

## ValidationException

Caution: By default exception throwing is enabled. So if you dont need it it can be disaled via packages config.

    php artisan config:publish revati/validation

Whatever where you want to validate something
```php
// ...

// Validate $input against $rules like usual.
// No need to catch response because if errors will occur, exception
// will be thrown and necessary response returned.
Validator::make($input, $rules);

// If got to this point validation passed
$user = User::create($input);

// For validation only (without throwing exception)
$validator = Validator::sotfMake($input, $rules);

// To disable or enable exception throwing at runtime use fallowing methods:
Validation::disable();
Validation::enable();

// To change response at runtime use fallowing method
Validation::setResponse($responseClosure, $ajaxResponseClosure);

// Change only one response
Validation::setResponse($responseClosure);
Validation::setResponse(null, $ajaxResponseClosure);

// ...
```

If error occur, fallowing responses will be returned:

```php
// For "regular" request
return \Redirect::back()
    ->withInput()
    ->withErrors($e->getErrors());

// For ajax request
return \Response::json(array(
    'success' => false,
    'errors' => $e->getErrors()->toJson()
), 400);

// $e stands for ValidationException witch (for now) only has one method
// to return errors -> getErrors.
```

## Customize

Those responses are defined as closures in package config file, so just by publishing package config you can modify what your app should be doing in witch case.

    php artisan config:publish revati/validation

## TODO

It is probably out of scope for this package, but planing to add simple js file for
automating form submit + error showing on error response.
