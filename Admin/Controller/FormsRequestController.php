<?php


namespace FormsPlugin\Admin\Controller;

use FormsPlugin\Admin\Widgets\AdminTableWidget;
use FormsPlugin\Services\DataBaseService;
use FormsPlugin\Models\FormsRequest;

class FormsRequestController extends BaseController
{
    const ACTION_VARIABLE = 'requests-index';

    public function __construct( ) {
        $this->actionVariable = self::ACTION_VARIABLE;
    }

    public function actionIndex()
    {
        $items['title'] = 'Заявки';
        $items['action_variable'] = self::ACTION_VARIABLE;
        $items['action_add'] = 'edit-request';
        $items['action_parent'] = self::ACTION_VARIABLE;

        $formsRequestModel = new FormsRequest();
        $columns = $formsRequestModel->getAttributesName();

        $dataBaseService = new DataBaseService();
        $data = $dataBaseService->findAll(
            $formsRequestModel->tableName(),
            ['model'=> "FormsPlugin\\\Models\\\FormsRequest"],
            ['id' => 'DESC']);

        $tableWidget = new AdminTableWidget(
            $data,
            $columns,
            self::ACTION_VARIABLE,
            $items['action_add'],
            'delete-request',
            $this->getWpNonceName('actionDeleteRequest'),
            $this->getWpNonceAction('actionDeleteRequest')
        );

        $tableWidget->prepareItems();

        echo $this->render('/Admin/views/list', $items, $tableWidget);
    }

    public function actionEditRequest()
    {
        $items['title'] = 'Добавить Заявку';
        $items['wpnonce_name'] = $this->getWpNonceName(__FUNCTION__);
        $items['wpnonce_action'] = $this->getWpNonceAction(__FUNCTION__);
        $items['data'] = [];
        $model = new FormsRequest();
        if (isset($_GET['id'])) {
            $dataBaseService = new DataBaseService();
            $rawData = $dataBaseService->findAll($model->tableName(), [
                    'model'=> "FormsPlugin\\\Models\\\FormsRequest",
                    'id' => $_GET['id']
                ]
            );

            if (isset($rawData[0])) {
                $storedModel = $dataBaseService->loadStoredModel($rawData[0]);
                $items['data'] = $storedModel->getAttributes();
            }
        }

        if (isset($_POST[$items['wpnonce_name']]) ) {
            $model->load($_POST);
            $model->save();
            $this->notices(['succeeded' => true, 'message' => 'saved']);
            $items['data'] = $model->attributes();
        }

        echo $this->render('/Admin/views/edit_request', $items, $this);
    }

    public function actionDeleteRequest()
    {
        if (($_GET[ $this->getWpNonceName('actionDeleteRequest')] == $this->getWpNonceAction('actionDeleteRequest') )
            && isset($_GET['id'])) {
            $model = new FormsRequest();
            $dataBaseService = new DataBaseService();
            if ($dataBaseService->delete($model->tableName(), ['id' => $_GET['id']]) != false){
                $this->notices(['succeeded' => true, 'message' => 'Form deleted.']);
                return true;
            }
        }

        $this->notices(['error' => true, 'message' => 'Ошибка удаления']);
        return false;
    }

}