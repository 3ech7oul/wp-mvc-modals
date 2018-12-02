<?php


namespace FormsPlugin\FrontEnd\Controller;

use FormsPlugin\Models\FormsRequest;
use FormsPlugin\Models\FormsRequestRepresentation;
use FormsPlugin\Services\TelegramService;


class Ajax
{
    /**
     * @param $formData
     * @return bool
     */
    public function actionSaveFormsRequest($formData)
    {
        $data = $this->prepareFormData($formData);
        if (count($data) <= 0 ) {
            return false;
        }

        $formRequest = new FormsRequest();
        $formRequest->load($data);
        $formRequest->save();

        $represFormsRequest = new FormsRequestRepresentation();
        $represFormsRequest->setFormsRequest($formRequest);
        $this->sendTelegram($represFormsRequest->getTelegramFormat());
        return true;
    }

    /**
     * @param $formData
     * @return array
     */
    private function prepareFormData($formData)
    {
        $result = [];
        foreach ($formData as $key => $item) {
            $result[$item['name']] = $item['value'];
        }

        return $result;
    }

    /**
     * @param $message
     * @return bool
     */
    private function sendTelegram($message)
    {
        $telegramService = new TelegramService();
        return $telegramService->sendMessage($message);
    }

}