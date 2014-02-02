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
     * Store modifiers for validation rules
     *
     * @var array
     */
    protected $modifiers = [];

    /**
     * Store global modifiers for validation rules
     *
     * @var array
     */
    protected $globalModifiers = [];

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
        $modifiers = explode('|', $modifier);

        if($field === '*')
        {
            foreach($modifiers as $modifier)
            {
                array_set($this->globalModifiers, $modifier, $value);
            }
        }
        else
        {
            foreach($modifiers as $mod)
            {
                array_set($this->modifiers, $field . '.' . $modifier, $value);
            }
        }
    }

    /**
     * Update dynamic validation rules
     *
     * @param  array $rules Validation rules
     * @return array        Updated validation rules
     */
    protected function updateDynamicRules($rules)
    {
        // Get global modifiers
        list($globalPatterns, $globalReplaces) = $this->prepareModifiers($this->globalModifiers);

        // Set normal modifiers
        foreach($rules as $field => $rule)
        {
            // Get field modifiers
            $modifiers = array_get($this->modifiers, $field, array());
            list($patterns, $replaces) = $this->prepareModifiers($modifiers);

            // Merge global with local modifiers
            $patterns = array_merge($globalPatterns, $patterns);
            $replaces = array_merge($globalReplaces, $replaces);

            // Should modifiers that will left over be removed or not?
            // TODO: Answer to this important question.

            // Replace modifiers
            $rules[$field] = preg_replace($patterns, $replaces, $rules[$field]);
        }

        return $rules;
    }

    /**
     * Prepare modifiers for preg replace
     * @param  array $modifiers Unprepared modifiers
     * @return array            Prepared modifiers
     */
    protected function prepareModifiers($modifiers)
    {
        // Flip array (keys connot be used as reference)
        $modifiers = array_flip($modifiers);

        foreach($modifiers as $value => &$key)
        {
            $key = '/\[('.$key.')\]/';
        }

        // Flip agains
        $modifiers = array_flip($modifiers);

        return array_divide($modifiers);
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

    /**
     * Get validation errors
     * @return type?
     */
    public function getErrors()
    {
        return $this->errors;
    }

}
