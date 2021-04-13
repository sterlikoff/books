<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Contact */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contacts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-index">

  <h1><?= Html::encode($this->title) ?></h1>

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],

      'id',
      'name',
      'subject',
      [
        'attribute' => 'email',
        'format' => "raw",
        'value' => function ($data) {
          /** @var \app\models\Contact $data */

          return Html::a($data->email, ["view", "id" => $data->id]);
        }
      ],
      'body:ntext',
    ],
  ]); ?>


</div>
