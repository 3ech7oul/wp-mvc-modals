<?php


namespace FormsPlugin\Models;

use FormsPlugin\Plugin;
use FormsPlugin\Models\Traits\DataBaseModelStore;
use FormsPlugin\Models\Traits\LoadableModel;

class FormsRequest implements DataBaseModel
{
    use DataBaseModelStore, LoadableModel;

    public $senderName;

    public $senderEmail;

    public $senderPhone;

    public $serviceId;

    public $serviceName;

    public function tableName()
    {
        return Plugin::PLUGIN_ALIAS.'_'.'forms';
    }

    public function getAttributesName()
    {
        return [
            'id' => 'Id',
            'senderName' => 'senderName',
            'senderEmail' => 'senderEmail',
            'senderPhone' => 'senderPhone',
        ];
    }

    public function rules()
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @param mixed $senderName
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }

    /**
     * @return mixed
     */
    public function getSenderEmail()
    {
        return $this->senderEmail;
    }

    /**
     * @param mixed $senderEmail
     */
    public function setSenderEmail($senderEmail)
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @return mixed
     */
    public function getSenderPhone()
    {
        return $this->senderPhone;
    }

    /**
     * @param mixed $senderPhone
     */
    public function setSenderPhone($senderPhone)
    {
        $this->senderPhone = $senderPhone;
    }

    /**
     * @return mixed
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * @param mixed $serviceId
     */
    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;
    }

    /**
     * @return mixed
     */
    public function getServiceName()
    {
        $post = get_post($this->serviceId);
        if ($post!=null) {
            return $post->post_title;
        }

    }

    /**
     * @param mixed $serviceName
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }

}