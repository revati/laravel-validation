# Exceptional Validation

This package throws `ValidationException` when validation error occur.
And handles it by runing fallowing closures:

```php
// For "regular" request
return \Redirect::back()->withInput()->withErrors($e->getErrors());

// For ajax request
return $e->getErrors()->toJson();
```

## Customize

Those closures are defined in packages config file, so just by publishing config file you can modify what should be returned in witch case.
