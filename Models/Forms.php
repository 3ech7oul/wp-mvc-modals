<?php
namespace FormsPlugin\Models;

use FormsPlugin\Plugin;
use FormsPlugin\Models\Traits\DataBaseModelStore;
use FormsPlugin\Models\Traits\LoadableModel;

class Forms implements DataBaseModel
{
    use DataBaseModelStore, LoadableModel;

    public $name;

    public function tableName()
    {
       return Plugin::PLUGIN_ALIAS.'_'.'forms';
    }

    public function getAttributesName()
    {
        return [
            'id' => 'Id',
           // 'code' => 'Code',
            'name' => 'Имя',
            //'subject' => 'Subject',
        ];
    }

    public function rules()
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}