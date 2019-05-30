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
        // get list sorted by date from newest to oldest
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


        // in case we have POST request
        // we try to load post data (including image)
        // validate it
        // on success, save and redirect home
        if ($model->load(Yii::$app->request->post()) &&
            $model->loadOrRemovePicImage() &&
            $model->validate()) {
            $model->save();
            return $this->redirect('/persons');
        }

        // otherwise, pass model object in view
        // in case of errors, it will contain them
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

        // if not found show 404
        if (!$model) {
            throw new \yii\web\NotFoundHttpException();
        }

        // same principle as actionNew
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

        // if not found show 404
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
        // actually, i don't know exactly
        // how to write this according to yii concepts

        // this is ajax endpoint returning list of devices
        // depending of parent platform
        // so i use ActiveForm
        // to get most native html for form
        
        $form = ActiveForm::begin();

        // oops usage of magic method __toString() directly
        // i googled but i can't find how to get options of dropdown directly

        $dropdown = $form->field(new Person, 'device_id')->dropdownList(
            Device::find()
            ->where(['platform_id' => $platform_id])
            ->orderBy('sort')
            ->select(['title', 'id'])->indexBy('id')->column(),
            ['prompt'=>'Select Device']
        )->__toString();

        // so i get pure string representation
        // and filter only <option>'s

        $dropdownRows = explode("\n", $dropdown);
        $dropdownRows = array_filter($dropdownRows, function($row) {
            return strpos($row, '<option') === 0;
        });


        Yii::$app->response->format = Response::FORMAT_JSON;
        return implode("\n", array_values($dropdownRows));
    }

  
}
