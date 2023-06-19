<?php

namespace core;

class Validator
{
    protected $data;
    protected $data_validada = [];
    protected $errors = [];
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

        $responde = new \http\Response(
            400,
            [
                'message' => 'Some information needs attention!',
                'errors'  => $errors
            ]
        );

        $responde->display();
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
            return isset($this->errors[$this->prefix . $key_name . $this->sufix])
                ? $this->errors[$this->prefix . $key_name . $this->sufix]
                : null;

        return $this->errors;
    }

    /**
     * VALIDE OS DADOS
     * @access public
     * @return bool
     */
    public function isValid()
    {
        return count($this->errors) == 0;
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
     * VERIFIQUE SE O VALOR ATUAL É MENOR OU MAIOR QUE O PARÂMETRO
     * @access public
     * @param float $min comprimento mínimo
     * @param float $max comprimento máximo
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
     * VERIFIQUE SE O COMPRIMENTO DO VALOR ATUAL É MENOR OU MAIOR QUE O PARÂMETRO
     * @access public
     * @param int $min comprimento mínimo
     * @param int $max comprimento máximo
     * @return self
     */
    public function lengthMinMax(int $min = null, int $max = null)
    {
        if (!$strlen = strlen($this->getCurrentValue() ?? ''))
            return $this;

        if ($min and $strlen < $min)
            $this->addError('minLength', "'$min' caracteres. Atualmente tem '$strlen'");

        if ($max and $strlen > $max)
            $this->addError('maxLength', "'$max' caracteres. Atualmente tem '$strlen'");

        return $this;
    }

    /**
     * RETORNA O VALOR DENTRO DA CHAVE QUE ESTA ATUALMENTE SENDO VERIFICADA
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
     * DEFINE UM NOVO VALOR PARA O FIELD ATUAL
     * @access protected
     * @return bool
     */
    protected function setCurrentValue($value)
    {
        return $this->data[$this->current_key] = $value;
    }

    /**
     * RETORNA OS DADOS DO VALIDADOR
     * @access public
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * ADICIONA UM STRING AO ARRAY DE ERROS
     * @access protected
     * @param string $error The error message
     * @return object|string
     */
    protected function addError($error_type, ...$args)
    {
        $name = [$this->current_description] ?: $this->current_key;

        $error = sprintf($this->messages[$error_type], ...$args);

        $field = $this->prefix . $this->current_key . $this->sufix;

        $extant = array_search($field, array_column($this->errors, 'field'));

        if ($extant > -1)
            return $this->errors[$extant]->errors[] = $error;

        return $this->errors[] = (object) [
            'field'  => $field,
            'name'   => $name,
            'errors' => [
                $error
            ]
        ];
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