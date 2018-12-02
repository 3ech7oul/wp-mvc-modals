<?php


namespace FormsPlugin\Models;


class FormsRequestRepresentation
{

    /**
     * @var  \FormsPlugin\Models\FormsRequest
     */
    private $formsRequest;

    /**
     * @return  \FormsPlugin\Models\FormsRequest
     */
    public function getFormsRequest()
    {
        return $this->formsRequest;
    }

    /**
     * @param FormsRequest $formsRequest
     */
    public function setFormsRequest($formsRequest)
    {
        $this->formsRequest = $formsRequest;
    }

    /**
     * @return string
     */
    public function getTelegramFormat()
    {
        $text = '';
        $text .= ($this->formsRequest->getSenderName())?'<b>Имя:</b> '.$this->formsRequest->getSenderName().PHP_EOL:'';
        $text .= ($this->formsRequest->getSenderPhone())?'<b>Номер телефона:</b> <a href="tel:'.$this->formsRequest->getSenderPhone().'">'.$this->formsRequest->getSenderPhone().'</a>'.PHP_EOL:'';
        $text .= ($this->formsRequest->getSenderEmail())?'<b>Email:</b> <a href="mailto:'.$this->formsRequest->getSenderEmail().'">'.$this->formsRequest->getSenderEmail().'</a>'.PHP_EOL:'';
        $text .= ($this->formsRequest->getFormTopicName())?'<b>Тема обращения:</b> '.$this->formsRequest->getFormTopicName().PHP_EOL:'';
        $text .= ($this->formsRequest->getServiceName())?'<b>Название услуги:</b> '.$this->formsRequest->getServiceName().PHP_EOL:'';
        $text .= ($this->formsRequest->getFormSubject())?'<b>Тема сообщеия:</b> '.$this->formsRequest->getFormSubject().PHP_EOL:'';
        $text .= ($this->formsRequest->getFormMessage())?'<b>Сообщение:</b> '.$this->formsRequest->getFormMessage().PHP_EOL:'';

        return $text;
    }
}