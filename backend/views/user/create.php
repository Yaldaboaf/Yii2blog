<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Create User Edit';
$this->params['breadcrumbs'][] = ['label' => 'User Edits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-edit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
