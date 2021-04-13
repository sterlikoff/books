<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

  <h1><?= Html::encode($this->title) ?></h1>

  <?php if (!Yii::$app->user->isGuest): ?>

    <p>
      <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

  <?php endif; ?>

  <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],

      'id',
      [
        "attribute" => "name",
        "format" => "raw",
        "value" => function ($data) {
          /** @var \app\models\Book $data */

          return Html::a($data->name, ["view", "id" => $data->id]);
        }
      ],
      'isbn',
      'page_count',
      [
        "attribute" => "date_published",
        "filter" => false,
      ],
      [
        'class' => 'yii\grid\ActionColumn',
        'visible' => !Yii::$app->user->isGuest,
      ],
    ],
  ]); ?>


</div>
