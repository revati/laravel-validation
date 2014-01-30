# Exceptional Validation

This package throws ValidationException when validation error occur.
And handles it by runing fallowing closures:

For 'regular' request
```php
return \Redirect::back()->withInput()->withErrors($e->getErrors());
```

and for ajax request
```php
return $e->getErrors()->toJson();
```
  
## Customize

Those closures are defined in packages config file, so just by publishing config file you can modify what should be returned in witch case.
