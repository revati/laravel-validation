<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | ValidationException response closure
    |--------------------------------------------------------------------------
    |
    | Provided closure will be returned as response to browser if
    | ValidationException will occur.
    |
    | Closure accepts one parameter \Revati\Validation\ValidationException
    | With getErrors method you can return validation error messages.
    |
    */

    'response' => function(Revati\Validation\ValidationException $e)
    {
        return \Redirect::back()->withInput()->withErrors($e->getErrors());
    },

    /*
    |--------------------------------------------------------------------------
    | AJAX - ValidationException response closure
    |--------------------------------------------------------------------------
    |
    | It is the same as above but will be used for AJAX requests.
    |
    */

    'ajaxResponse' => function(Revati\Validation\ValidationException $e)
    {
        return $e->getErrors()->toJson();
    },

);
