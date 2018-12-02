<?php
namespace FormsPlugin\Admin\Controller;

use FormsPlugin\Models\Forms;
use FormsPlugin\Admin\Widgets\AdminTableWidget;
use FormsPlugin\Admin\Widgets\ItemAction;
use FormsPlugin\Admin\Widgets\ItemActionsBuilder;
use FormsPlugin\Services\DataBaseService;

class FormsTypesController extends BaseController
{

    const ACTION_VARIABLE = 'forms-index';

    public function __construct( ) {
        $this->actionVariable = self::ACTION_VARIABLE;
    }

    public function actionIndex()
    {
        $items['title'] = 'Формы';
        $items['action_variable'] = self::ACTION_VARIABLE;
        $items['action_add'] = 'edit-form';
        $items['action_parent'] = self::ACTION_VARIABLE;

        $formsModel = new Forms();
        $columns = $formsModel->getAttributesName();

        $dataBaseService = new DataBaseService();
        $data = $dataBaseService->findAll($formsModel->tableName(), ['model'=> "FormsPlugin\\\Models\\\Forms"]);

        $tableWidget = new AdminTableWidget(
            $data,
            $columns,
            self::ACTION_VARIABLE,
            $items['action_add'],
            'delete-membership',
            $this->getWpNonceName('actionDeleteMembership'),
            $this->getWpNonceAction('actionDeleteMembership')
        );

        $tableWidgetActionEdit = new ItemAction(self::ACTION_VARIABLE, 'edit-form', 'Редактировать', self::ACTION_VARIABLE);
        $tableWidgetActionEdit->setActionItemIdVariable('id');
        $tableWidgetActionRemove = new ItemAction(self::ACTION_VARIABLE, 'delete-form', 'Удалить', self::ACTION_VARIABLE);
        $tableWidgetActionRemove->setActionItemIdVariable('id');
        $tableWidgetActionRemove->setAdditionalParameters(['redirect-url' => urlencode($_SERVER['REQUEST_URI'])]);
        $tableWidgetActionRemove->setAdditionalParameters(['nonceName' => urlencode($this->getWpNonceName(__FUNCTION__))]);


        $tableWidgetActions = new ItemActionsBuilder();
        $tableWidgetActions->addActions($tableWidgetActionEdit);
        $tableWidgetActions->addActions($tableWidgetActionRemove);

        $tableWidget->prepareItems();
        $tableWidget->setWidgetItemActions($tableWidgetActions);

        echo $this->render('/admin/views/list', $items, $tableWidget);
    }

    public function actionEditForm()
    {
        $items['title'] = 'Добавить Форму';
        $items['wpnonce_name'] = $this->getWpNonceName(__FUNCTION__);
        $items['wpnonce_action'] = $this->getWpNonceAction(__FUNCTION__);
        $items['data'] = [];
        $model = new Forms();
        if (isset($_GET['id'])) {
            $dataBaseService = new DataBaseService();
            $rawData = $dataBaseService->findAll($model->tableName(), [
                'model'=> "FormsPlugin\\\Models\\\Forms",
                    'id' => $_GET['id']
                ]
            );

            if (isset($rawData[0])) {
                $storedModel = $dataBaseService->loadStoredModel($rawData[0]);
                $items['data'] = $storedModel->attributes();
            }
        }

        if (isset($_POST[$items['wpnonce_name']]) ) {
            $model->load($_POST);
            $model->save();
            $this->notices(['succeeded' => true, 'message' => 'saved']);
            $items['data'] = $model->attributes();
        }

        echo $this->render('/admin/views/edit_form', $items, $this);
    }

    public function actionDeleteForm()
    {
        if (isset($_GET['nonceName']) && isset($_GET['id'])) {
            $model = new Forms();
            $dataBaseService = new DataBaseService();
            if ($dataBaseService->delete($model->tableName(), ['id' => $_GET['id']]) != false){
                $this->notices(['succeeded' => true, 'message' => 'Form deleted.']);
            }
        }
    }
}