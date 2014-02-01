<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | ValidationException throwing
    |--------------------------------------------------------------------------
    |
    | (bool) Weather to throw exception or not.
    |
    */

    'throwException' => true,

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
    | Default:
    |
    |   return \Redirect::back()
    |       ->withInput()
    |       ->withErrors($e->getErrors());
    |
    */

    'response' => function(Revati\Validation\ValidationException $e)
    {
        return \Redirect::back()
            ->withInput()
            ->withErrors($e->getErrors());
    },

    /*
    |--------------------------------------------------------------------------
    | AJAX - ValidationException response closure
    |--------------------------------------------------------------------------
    |
    | It is the same as above but will be used for AJAX requests.
    |
    | Default:
    |
    |   return \Response::json(array(
    |       'success' => false,
    |       'errors' => $e->getErrors()->toJson()
    |   ), 400);
    |
    */

    'ajaxResponse' => function(Revati\Validation\ValidationException $e)
    {
        return \Response::json(array(
            'success' => false,
            'errors' => $e->getErrors()->toJson()
        ), 400);
    },

);
