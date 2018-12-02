<?php


namespace FormsPlugin\Models\Traits;


trait LoadableModel
{
    /**
     * @param array $data
     */
    public function load($data)
    {
        foreach ($this->attributes() as $attribute => $value) {
            $method = 'set'.ucfirst($attribute);
            if ((isset($data[$attribute])) && (method_exists ($this, $method))){
                $this->$method($data[$attribute]);
            }
        }
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return get_object_vars($this);
    }
}