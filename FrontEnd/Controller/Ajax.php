<?php


namespace FormsPlugin\FrontEnd\Controller;

use FormsPlugin\Models\FormsRequest;


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
        return $formRequest->save();
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

}