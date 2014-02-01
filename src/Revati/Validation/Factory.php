<?php namespace Revati\Validation;

use Illuminate\Validation\Factory as BaseFactory;
use Config;

class Factory extends BaseFactory
{
    /**
     * Create a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @return Boolean
     */
    public function make(array $data, array $rules, array $messages = array(), array $customAttributes = array())
    {
        $validator = parent::make($data, $rules, $messages, $customAttributes);

        if($validator->fails() && Config::get('validation::throwException'))
        {
            throw new ValidationException( $validator->messages() );
        }

        return $validator;
    }

}
