<?php

namespace FormsPlugin\Services;

use FormsPlugin\Models\DataBaseModel;

class DataBaseService
{
    /**
     * @param DataBaseModel $model
     * @return false|int
     */
    public function saveDataBaseModel(DataBaseModel $model)
    {
        global $wpdb;
        $modelName = get_class($model);
        $attributes= $model->attributes();

        if (empty($attributes)){
            return;
        }

        $sort = ($attributes['sort'])?$attributes['sort']:100;
        if (strlen($attributes['id'])>0) {
            $id = $attributes['id'];
            unset($attributes['id']);
            unset($attributes['sort']);
            $data = json_encode($attributes);
            $updateResult =  $this->updateBy($model->tableName(), ['id' => $id], ['model' => $modelName, 'sort'=>$sort, 'settings_obj' =>  $data]);
            return ($updateResult!=false) ? $id:false;
        }

        unset($attributes['id']);
        unset($attributes['sort']);
        $data = json_encode($attributes);
        $wpdb->insert($wpdb->prefix . $model->tableName(),
            ['model' => $modelName, 'sort'=>$sort, 'settings_obj' =>  $data]);
        return $wpdb->insert_id;
    }

    /**
     * @param $table
     * @param array $updateBy
     * @param array $params
     * @return false|int
     */
    public function updateBy($table, array $updateBy, array $params)
    {
        global $wpdb;

        $db_members = $wpdb->prefix . $table;
        return $wpdb->update($db_members,
            $params,
            $updateBy
        );
    }

    /**
     * @param $table
     * @param array $condition
     * @param array $orderBy
     * @return array|null|object
     */
    public function findAll($table, array $condition, array $orderBy = [])
    {
        global $wpdb;
        $dbTable = $wpdb->prefix . $table;

        $whereString = $this->constructWhereQueryString($dbTable, $condition);
        $orderString= $this->constructOrderQueryString($orderBy);

        $query = "SELECT * FROM $dbTable $whereString $orderString";
        $result = $wpdb->get_results($query, ARRAY_A);

        return $result;
    }

    /**
     * @param $table
     * @param array $condition
     *
     * @return false|int
     */
    public function delete($table, array $condition)
    {
        global $wpdb;

        $dbTable = $wpdb->prefix . $table;
        return $wpdb->delete($dbTable, $condition);
    }

    /**
     * @param $dbTable
     * @param array $condition
     * @return string
     */
    protected function constructWhereQueryString($dbTable, array $condition)
    {
        $whereString = '';

        if (!empty($condition)){
            $whereString .= 'WHERE ';
        }

        foreach ( $condition as $key => $value ) {
            $operator    = ( end( $condition ) !== $value ) ? ' AND ' : '';
            $whereString .= $dbTable . "." . $key . " = " . "'" . $value . "'" . $operator;
        }

        return $whereString;
    }

    /**
     * @param array $condition
     * @return string
     */
    protected function constructOrderQueryString(array $condition)
    {
        $orderString = '';

        if (!empty($condition)){
            $orderString .= 'ORDER BY ';
        }

        foreach ( $condition as $field => $direction ) {
            $orderString .= $field . " " . $direction;
        }

        return $orderString;
    }

    /**
     * @param $rawData
     * @param string $getMethod
     * @return mixed
     */
    public function convertModelToArray($rawData, $getMethod = 'getAttributes')
    {
        $modelName = $rawData['model'];
        $array = json_decode($rawData['settings_obj'], true);
        $modelInstance = new $modelName();
        $settingsId = [
            'id' => $rawData['id'],
            'sort' => $rawData['sort']
        ];
        $load = array_merge($settingsId,$array);
        $modelInstance->load($load);
        $settingsData = $modelInstance->$getMethod();
        unset($modelInstance);
        return $settingsData;
    }

    /**
     * @param $storedModel
     * @return null
     */
    public function loadStoredModel($storedModel)
    {
        if (strlen($storedModel['model'])<=0){
            return null;
        }

        $modelName = $storedModel['model'];
        $array = json_decode($storedModel['settings_obj'], true);
        $modelInstance = new $modelName();
        $settingsId = [
            'id' => $storedModel['id'],
            'sort' => $storedModel['sort']
        ];
        $load = array_merge($settingsId,$array);
        $modelInstance->load($load);
        return $modelInstance;
    }
}