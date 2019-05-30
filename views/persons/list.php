<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Persons list';
?>

<div class="text-right">
    <a class="btn btn-success" href="<?=Url::to('/persons/new')?>" role="button">New</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th style="width: 64px"></th>
            <th>Name</th>
            <th>E-mail</th>
            <th>Platform</th>
            <th>Device</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($persons as $person): ?>

        <tr>
            <td><?=$person->id?></td>

            <?php
            $image = $person->pic ? (Url::to('@web/uploads/') . $person->pic) : Url::to('@web/img/no-user.jpg');
            ?>

            <td><img src="<?=$image?>" class="img-circle img-responsive"></td>

            <td><?=$person->name?></td>
            <td><?=$person->email?></td>
            <td><?=$person->platform->title?></td>
            <td><?=$person->device->title?></td>
            <td><?=$person->created_at?></td>

            <td>
                <a class="btn btn-primary" href="<?=Url::to('/persons/edit/' . $person->id)?>" role="button">Edit</a>
                <a class="btn btn-danger" href="<?=Url::to('/persons/remove/' . $person->id)?>" role="button">Remove</a>
            </td>
        </tr>

        <?php endforeach; ?>
    </tbody>
</table>

