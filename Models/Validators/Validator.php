<?php
namespace FormsPlugin\Models\Validators;

class Validator
{
    /**
     * Правила валидации.
     *
     * @var array
     */
    private $rules = [];

    /**
     * Массив атрибутов на валидацию.
     *
     * @var array
     */
    private $attributes = [];

    /**
     * Имя валидируемой модели/
     *
     * @var
     */
    private $modelName;

    /**
     * Массив атрибутов с ошибками.
     *
     * @var array
     */
    private $errors = [];

    public function __construct($rules, $modelName, $attributes)
    {
        $this->rules = $rules;
        $this->modelName = $modelName;
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $result = [];
        foreach ($this->rules as $attribute => $rules) {
            foreach ($rules as $rule) {
                switch ($rule){
                    case 'unique':
                       // $result[$attribute] = $this->validatorUnique($attribute, $this->attributes[$attribute]);
                        break;
                }
            }
        }

        if (count($result)<=0) {
            return true;
        }

        $resultReverse = array_reverse($result);

        if (in_array(false, $resultReverse)) {
            $this->errors = $result;
        }

        return !in_array(false, $resultReverse);
    }

    /**
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    public function validatorUnique($attribute, $value)
    {
        return true;
    }

}