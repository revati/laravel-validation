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
     * @param  array  $customAttributes
     * @return Validator
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

    /**
     * Validate softly (without exception throwing)
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return Validator
     */
    public function softMake(array $data, array $rules, array $messages = array(), array $customAttributes = array())
    {
        return parent::make($data, $rules, $messages, $customAttributes);
    }

    /**
     * Enable exception throwing at runtime;
     *
     * @return void
     */
    public function enable()
    {
        Config::set('validation::throwException', false);
    }

    /**
     * Disable exception throwing at runtime
     *
     * @return void
     */
    public function disable()
    {
        Config::set('validation::throwException', false);
    }

    /**
     * Change response closuers at runtime
     * @param closuer $response
     * @param closure $ajaxResponse
     */
    public function setResponse( $response = null , $ajaxResponse = null )
    {
        if( !is_null($response) )
        {
            Config::set('validation::response', $response);
        }

        if( !is_null($ajaxResponse) )
        {
            Config::set('validation::ajaxResponse', $ajaxResponse);
        }
    }
}
