<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

use app\models\Platform;
use app\models\Device;

$this->title = $model->id ? "Edit $model->name" : 'New person';
?>
<div class="site-contact">

    <div class="text-left">
        <a class="btn btn-default" href="<?=Url::to('/persons')?>" role="button">Back to list</a>
    </div>
    <h1><?= Html::encode($this->title) ?></h1>

    
        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin([
                    'id' => 'person-form',
                    'options' => ['enctype' => 'multipart/form-data']
                    ]); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'email')->input('email') ?>

                    <?= $form->field($model, 'platform_id')->dropdownList(
                        Platform::find()->select(['title', 'id'])->indexBy('id')->column(),
                        ['prompt'=>'Select Platform',
                        'onchange' => 'loadDropdownDevices(event.currentTarget.value)']
                    ); ?>

                    <?php 
                        $devices = [];
                        if ($model->id) {
                            $devices = Device::find()
                                ->where(['platform_id' => $model->platform_id])
                                ->orderBy('sort')
                                ->select(['title', 'id'])->indexBy('id')->column();
                        }
                    ?>

                    <?= $form->field($model, 'device_id')->dropdownList(
                        $devices,
                        ['prompt'=>'Select Device']
                    ); ?>

                    <?= $form->field($model, 'picImage')->fileInput([
                        'style' => 'display: none',
                        'onchange' => 'showImage()'
                    ]) ?>

                    <div style="margin-bottom: 15px">
                        <button type="button" class="btn btn-success" onclick="openPicImageFile()">Select image</button>
                        <button type="button" class="btn btn-danger" onclick="removePicImageFile()">Remove image</button>
                    </div>

                    <?php
                    $image = $model->pic ? (Url::to('@web/uploads/') . $model->pic) : Url::to('@web/img/no-user.jpg');
                    ?>

                    <div style="width: 240px; height: 240px">
                        <img src="<?=$image?>" alt="" id="picPreview" class="img-circle img-responsive">
                        <img src="<?=Url::to('@web/img/no-user.jpg')?>" alt="" id="noPicPreview" class="img-circle img-responsive" style="display: none">
                    </div>

                    <!-- <?=$form->field($model, 'removePicImage')->checkbox([
                        'uncheck' => '0',
                        'style' => 'display: none',
                        ])?> -->

                    <?=$form->field($model, 'removePicImage')->hiddenInput()->label(false)?>

                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'person-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
</div>


<script>

function loadDropdownDevices(platform_id) {
    if (!platform_id) {
        $('#<?=Html::getInputId($model, 'device_id') ?> option')
            .each(function(i, el) {
                if (el.getAttribute('value')) el.remove();
            });

        return;
    }

    $.ajax('/persons/devices/' + platform_id, {
        dataType: 'json'
    })
    .done(function(data) {
        $('#<?=Html::getInputId($model, 'device_id') ?>').html(data);
    });
}


function toggleRemoveImage(checked) {
    if (checked) {
        $('#picPreview').hide();
        $('#noPicPreview').show();
    } else {
        $('#noPicPreview').hide();
        $('#picPreview').show();
    }

    $('#<?=Html::getInputId($model, 'removePicImage') ?>').val(Number(checked));
}


function openPicImageFile() {
    $('#<?=Html::getInputId($model, 'picImage') ?>').click();
}


function removePicImageFile() {
    toggleRemoveImage(true);
    $('#<?=Html::getInputId($model, 'picImage') ?>').val('');
}


function showImage() {
    var input = $('#<?=Html::getInputId($model, 'picImage') ?>').get(0);

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            toggleRemoveImage(false);

            $('#picPreview')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}


</script>
