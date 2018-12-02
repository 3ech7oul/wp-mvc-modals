<?php
namespace FormsPlugin\Models;

interface DataBaseModel
{
    /**
     * Имя таблицы в БД
     *
     * @return string
     */
    public function tableName();

    /**
     * Записывает модель в БД
     *
     * @return bool
     */
    public function save();
}