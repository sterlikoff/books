<?php

use app\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">

  <?php $form = ActiveForm::begin([
    'options' => [
      'enctype' => 'multipart/form-data'
    ]
  ]); ?>

  <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

  <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

  <?= $form->field($model, 'page_count')->textInput() ?>

  <?= $form->field($model, 'date_published')->textInput() ?>

  <?= $form->field($model, 'short_text')->textInput(['maxlength' => true]) ?>

  <?= $form->field($model, 'long_text')->textarea(['rows' => 6]) ?>

  <?= $form->field($model, 'status')->dropDownList(\app\models\Book::$statusTitles); ?>

  <?= $form->field($model, 'categoriesIds')->dropDownList(ArrayHelper::map(Category::find()->all(), "id", "name"), [
    "multiple" => "multiple",
  ]); ?>

  <div class="form-group">
    <?= Html::fileInput("image", "", [
      "placeholder" => "Choose file",
    ]); ?>
  </div>

  <div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
  </div>

  <?php ActiveForm::end(); ?>

</div>
