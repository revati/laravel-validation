<?php namespace Revati\Validation;

use Validator;

/**
 * This class set of some commonly used things (for me)
 * Create individual Validation service for each repository.
 */
abstract class BaseValidator
{
    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Validation errors
     *
     * @var
     */
    protected $errors;

    /**
     * Unique id to exclude from unique validation rule
     *
     * @var int
     */
    protected $unique;

    /**
     * Store dynamic values that should be changed before validation
     *
     * @var array
     */
    protected $modifiers = [];

    /**
     * Validate data against rules
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @return \Illuminate\Validation\Validator
     */
    public function validate(array $data, array $rules, array $messages = array(), array $customAttributes = array())
    {
        $rules = $this->updateDynamicRules($rules);

        // Validate data
        $validator = Validator::make($data, $rules, $messages, $customAttributes);

        // If validation exception throwing is disabled
        if($validator->passes())
        {
            return true;
        }

        $this->errors = $validator->messages();

        return false;
    }

    /**
     * Add modifier for validation rules
     * @param string $field    Name dot modifier
     * @param string $modifier Modifier name to search for
     * @param string $value    Modifier value to replace with
     */
    public function addModifier($field, $modifier, $value = '')
    {
        array_set($this->modifiers, $field . '.' . $modifier, $value);
    }

    /**
     * Update dynamic validation rules
     *
     * @param  array $rules Validation rules
     * @return array        Updated validation rules
     */
    protected function updateDynamicRules($rules)
    {
        foreach($this->modifiers as $field => $modifiers)
        {
            $patterns = [];
            $replaces = [];

            foreach ($modifiers as $key => $value)
            {
                $patterns[] = '/\[('.$key.')\]/';
                $replaces[] = $value;
            }

            $rules[$field] = preg_replace($patterns, $replaces, $rules[$field]);

        }

        return $rules;
    }

    /**
     * Get validation rules
     * @return type?
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get rules for validation
     * @param  array  $rules Filter and overwrite routes
     * @return array         Filtered and overwrited routes
     */
    public function getRules(array $rules = null)
    {
        if(is_array($rules))
        {
            $newRules = [];

            foreach ($rules as $key => $rule)
            {
                // If trying to use prefefined field rules
                if(array_key_exists($rule, $this->rules))
                {
                    $newRules[$rule] = $this->rules[$rule];
                }

                // If using custom validation rules
                else
                {
                    $newRules[$key] = $rule;
                }
            }

            return $newRules;
        }

        return $this->rules;
    }

}
