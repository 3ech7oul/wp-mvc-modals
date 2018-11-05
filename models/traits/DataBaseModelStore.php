<?php

namespace FormsPlugin\Models\Traits;

use FormsPlugin\Services\DataBaseService;

trait DataBaseModelStore
{
    public $id;

    public $sort;

    private $validationErrors;

    private $dataBaseService;

    /**
     * Возвращает список атрибутов модели с их описанием.
     *
     * @return mixed
     */
    abstract public function getAttributesName();

    /**
     * Правила валидации модели
     *
     * @return array
     */
    abstract public function rules();

    public function __construct()
    {
        $this->dataBaseService = new DataBaseService();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id =$id;
    }

    /**
     * @return mixed
     */
    public function getSort() {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort( $sort ) {
        $this->sort = $sort;
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * @param mixed $validationErrors
     */
    public function setValidationErrors( $validationErrors ) {
        $this->validationErrors = $validationErrors;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $result = [];
        foreach ($this->attributes() as $attribute => $value) {
            $method = 'get'.ucfirst($attribute);
            if (method_exists ($this, $method)) {
                $result[$attribute] = $this->$method();
            }
        }

        return $result;
    }

    /**
     * Записывает модель в БД
     *
     * @return bool
     */
    public function save()
    {
        $result = $this->dataBaseService->saveDataBaseModel($this);


        if ($result!= false)
        {
            $this->setId($result);
            return true;
        }

        return false;
    }

    /**
     * Валидация атрибутов модели.
     *
     * @return bool
     */
    public function validate()
    {
        if (count($this->rules()) <= 0) {
            return true;
        }

        $validator = new Validator($this->rules(), get_class($this), $this->getAttributes());
        $result = $validator->validate();
        $this->validationErrors = $validator->getErrors();

        return $result;
    }

    /**
     * Список ошибок.
     *
     * @return mixed
     */
    public function showErrors()
    {
        return $this->validationErrors;
    }
}

