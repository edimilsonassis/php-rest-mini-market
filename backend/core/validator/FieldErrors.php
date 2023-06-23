<?php

namespace core\validator;

class FieldErrors
{
    protected $errors = [];

    static public function create(string $field, string $name, string $error)
    {
        $errors = new static;
        $errors->addError($field, $name, $error);
        return $errors;
    }

    public function toResponse()
    {
        $responde = new \http\Response(
            400,
            [
                'message' => 'Some information needs attention!',
                'errors'  => $this->getErrors()
            ]
        );

        $responde->display();
    }

    /**
     * Add an error message
     * @access protected
     * @param string $field  
     * @param string $args 
     * @param string $error 
     * @return object|string
     */
    public function addError(string $field, string $name, string $error)
    {
        $exists = array_search($field, array_column($this->errors, 'field'));

        if ($exists > -1)
            return $this->errors[$exists]->errors[] = $error;

        return $this->errors[] = (object) [
            'field'  => $field,
            'name'   => $name,
            'errors' => [
                $error
            ]
        ];
    }

    /**
     * Returns the invalid data messages
     * @param string $param [optional] A specific error
     * @return mixed One array with messages or a message of specific error
     */
    public function getErrors(?string $key_name = null)
    {
        if ($key_name)
            return $this->errors[$key_name] ?? null;

        return $this->errors;
    }

    /**
     * Returns the invalid data messages
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }
}