<?php


namespace FormsPlugin\Models;

use FormsPlugin\Plugin;
use FormsPlugin\Models\Traits\DataBaseModelStore;
use FormsPlugin\Models\Traits\LoadableModel;
use FormsPlugin\Services\DataBaseService;

class FormsRequest implements DataBaseModel
{
    use DataBaseModelStore, LoadableModel;

    public $senderName;

    public $senderEmail;

    public $senderPhone;

    public $serviceId;

    public $serviceName;

    public $formTopicId;

    public $formTopicName;

    public $formSubject;

    public $formMessage;

    public function tableName()
    {
        return Plugin::PLUGIN_ALIAS.'_'.'forms';
    }

    public function getAttributesName()
    {
        return [
            'id' => 'Id',
            'formTopicName' => 'Тема обращения',
            'senderName' => 'Имя',
            'senderEmail' => 'Email',
            'senderPhone' => 'Номер телефона',
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

    /**
     * @return mixed
     */
    public function getFormTopicId()
    {
        return $this->formTopicId;
    }

    /**
     * @param mixed $formTopicId
     */
    public function setFormTopicId($formTopicId)
    {
        $this->formTopicId = $formTopicId;
    }

    /**
     * @return null
     */
    public function getFormTopicName()
    {
        $dataBaseService = new DataBaseService();
        $rawData = $dataBaseService->findAll($this->tableName(), ['id' => $this->formTopicId]);
        if (!isset($rawData[0])) {
            return null;
        }
        $data = $dataBaseService->loadStoredModel($rawData[0]);
        return $data->getName();
    }

    /**
     * @return mixed
     */
    public function getFormSubject()
    {
        return $this->formSubject;
    }

    /**
     * @param mixed $formSubject
     */
    public function setFormSubject($formSubject)
    {
        $this->formSubject = $formSubject;
    }

    /**
     * @return mixed
     */
    public function getFormMessage()
    {
        return $this->formMessage;
    }

    /**
     * @param mixed $formMessage
     */
    public function setFormMessage($formMessage)
    {
        $this->formMessage = $formMessage;
    }

}