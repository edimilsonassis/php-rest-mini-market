<?php

namespace core;

use core\validator\FieldErrors;

class Validator
{
    protected $data;
    protected $data_validada = [];
    protected ?FieldErrors $errors;
    protected $messages = [];
    protected $prefix;
    protected $sufix;
    protected $current_key;
    protected $current_description;

    /**
     * Instance of array validator
     * @param array $data data to be validated
     * @return void
     */
    public function __construct(array $data)
    {
        $this->errors = new FieldErrors();
        $this->setData($data);
        $this->setMessages();
    }

    /**
     * Returns the validated data or an error array
     * @return array|void
     */
    public function validated(bool $error = true)
    {
        if ($this->isValid())
            return $this->getData();

        $errors = $this->getErrors();

        if ($error != true)
            return $errors;

        $this->errors->toResponse();
    }

    /**
     * Define the key of the field current field
     * @return self
     */
    private function key($key_name)
    {
        $this->current_key = $key_name;
        return $this;
    }

    /**
     * Define the description of the field current field
     * @return self
     */
    private function description($description)
    {
        $this->current_description = $description;
        return $this;
    }

    /**
     * Defines the key and description of the field current field
     * @return self
     */
    public function field($key_name, $description)
    {
        $this->key($key_name);
        $this->description($description);
        return $this;
    }

    /**
     * Defines the data to be validated
     * @return self
     */
    public function setData(array $data = null)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Returns the invalid data messages
     * @param string $param [optional] A specific error
     * @return mixed One array with messages or a message of specific error
     */
    public function getErrors(string $key_name = null)
    {
        if ($key_name)
            return $this->errors->getErrors($this->prefix . $key_name . $this->sufix);

        return $this->errors->getErrors();
    }

    /**
     * Return if the data is valid
     * @access public
     * @return bool
     */
    public function isValid()
    {
        return !$this->errors->hasErrors();
    }

    /**
     * Define if the field is required
     * @access public
     * @return self
     */
    public function isRequired()
    {
        if (empty($this->getCurrentValue()))
            $this->addError('isRequired');
        return $this;
    }

    /**
     * Define the minimum and maximum length of the field
     * @access public
     * @param float $min  
     * @param float $max  
     * @return self
     */
    public function numericMinMax(
        float $min = null,
        float $max = null,
        int $decimals = null,
        $decimal_separator = '.',
        $thousands_separator = ','
    ) {
        // "1.000,50"
        // "1,000.50"
        // "1,001,000.50"
        // "1.001.000,50"
        // "1.50" 
        // "1,50"

        $string_number = $this->getCurrentValue() ?? '';

        $pos_dot = strrpos($string_number, '.');
        $pos_com = strrpos($string_number, ',');

        if ($pos_com > $pos_dot)
            $string_number = str_replace(',', '.', str_replace('.', '', $string_number));
        else
            $string_number = str_replace(',', '', $string_number);

        $string_number = floatval($string_number);

        if ($decimals != null)
            $string_number = number_format($string_number, $decimals, $decimal_separator, $thousands_separator);

        if ($min && $string_number < $min)
            $this->addError('minNumeric', "'$min'");

        if ($max && $string_number > $max)
            $this->addError('maxNumeric', "'$max'");

        $this->setCurrentValue($string_number);

        return $this;
    }

    public function money(
        float $min = null,
        float $max = null,
        int $decimals = null
    ) {
        return $this->numericMinMax($min, $max, $decimals, '.', '');
    }

    /**
     * Check that the current value length is lower or larger than the parameter
     * @access public
     * @param int $min  
     * @param int $max  
     * @return self
     */
    public function lengthMinMax(int $min = null, int $max = null)
    {
        if (!$strlen = strlen($this->getCurrentValue() ?? ''))
            return $this;

        if ($min and $strlen < $min)
            $this->addError('minLength', "'$min' characters. Currently has '$strlen'");

        if ($max and $strlen > $max)
            $this->addError('maxLength', "'$max' characters. Currently has '$strlen'");

        return $this;
    }

    /**
     * Returns the value current value
     * @access protected
     * @return mixed
     */
    protected function getCurrentValue()
    {
        return isset($this->data[$this->current_key])
            ? $this->data[$this->current_key]
            : null;
    }

    /**
     * Set the current value
     * @access protected
     * @return bool
     */
    protected function setCurrentValue($value)
    {
        return $this->data[$this->current_key] = $value;
    }

    /**
     * Returns the data without validation
     * @access public
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Add an error message
     * @access protected
     * @param string $error_type error type message
     * @param array $args arguments to be used in the message
     * @return object|string
     */
    protected function addError($error_type, ...$args)
    {
        $field = $this->prefix . $this->current_key . $this->sufix;

        $name = [$this->current_description] ?: $this->current_key;

        $error = sprintf($this->messages[$error_type], ...$args);

        $this->errors->addError($field, $name, $error);

        return $this;
    }

    /**
     * Defines error messages
     * @return void
     */
    protected function setMessages($prefix = '', $sufix = '')
    {
        $this->sufix  = $sufix;
        $this->prefix = $prefix;

        $this->messages = [
            'isRequired' => 'Is required',
            'minLength'  => 'Very short text. Write at least than %s',
            'maxLength'  => 'Very long text. Write at most %s',
            'minNumeric' => 'The value must be greater than %s',
            'maxNumeric' => 'The value must be less than %s',
        ];
    }
}