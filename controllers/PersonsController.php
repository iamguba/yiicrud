<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

use app\models\Person;
use app\models\Device;

use yii\bootstrap\ActiveForm;

class PersonsController extends Controller
{

     /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    /**
     * Displays persons list.
     *
     * @return string
     */
    public function actionIndex()
    {
        $persons = Person::find()
                ->orderBy('created_at DESC')
                ->all();

        return $this->render('list', [
            'persons' => $persons
        ]);
    }


    /**
     * Displays persons new form.
     *
     * @return string
     */
    public function actionNew()
    {
        $model = new Person();

        if ($model->load(Yii::$app->request->post()) &&
            $model->loadOrRemovePicImage() &&
            $model->validate()) {
            $model->save();

            return $this->redirect('/persons');
        }


        return $this->render('edit', [
            'model' => $model,
        ]);
    }


    /**
     * Displays persons edit form.
     *
     * @return string
     */
    public function actionEdit($id)
    {
        $model = Person::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException();
        }


        if ($model->load(Yii::$app->request->post()) &&
            $model->loadOrRemovePicImage() &&
            $model->validate()) {
            $model->save();

            return $this->redirect('/persons');
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }


    /**
     * Removes person if exists.
     *
     * @return string
     */
    public function actionRemove($id)
    {
        $model = Person::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException();
        }

        $model->delete();

        return $this->render('remove', [
            'model' => $model,
        ]);
    }


    /**
    * Dynamically gets options for platform select
     *
     * @return string
     */
    public function actionDevices($platform_id)
    {
        $form = ActiveForm::begin();

        $dropdown = $form->field(new Person, 'device_id')->dropdownList(
            Device::find()
            ->where(['platform_id' => $platform_id])
            ->orderBy('sort')
            ->select(['title', 'id'])->indexBy('id')->column(),
            ['prompt'=>'Select Device']
        )->__toString();

        $dropdownRows = explode("\n", $dropdown);
        $dropdownRows = array_filter($dropdownRows, function($row) {
            return strpos($row, '<option') === 0;
        });


        Yii::$app->response->format = Response::FORMAT_JSON;
        return implode("\n", array_values($dropdownRows));
    }

  
}
