<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

use app\models\Platform;
use app\models\Device;

$this->title = "Remove $model->name";
?>
<div class="site-contact">
    <h1><?=$model->name?> has been succesfully removed!</h1>
    <a class="btn btn-default" href="<?=Url::to('/persons')?>" role="button">Back to list</a>
</div>



