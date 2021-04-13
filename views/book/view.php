<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="book-view">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
      'class' => 'btn btn-danger',
      'data' => [
        'confirm' => 'Are you sure you want to delete this item?',
        'method' => 'post',
      ],
    ]) ?>
  </p>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'id',
      'name',
      'isbn',
      'page_count',
      'date_published',
      'short_text',
      'long_text:ntext',
      [
        'attribute' => 'status',
        'value' => \app\models\Book::$statusTitles[$model->status],
      ],
      [
        'attribute' => 'photo_id',
        'format' => 'raw',
        'value' => $model->photo ? Html::img($model->photo->getUrl()) : "",
        'visible' => !!$model->photo,
      ],
      [
        "label" => "Categories",
        "format" => "raw",
        'value' => function ($data) {

          /** @var \app\models\Book $data */

          $links = [];
          foreach ($data->bookCategories as $bookCategory) {
            $links[] = Html::a($bookCategory->category->name, ["/book/index", "categoryId" => $bookCategory->category_id], [
              "target" => "_blank",
            ]);
          }

          return implode(", ", $links);

        }
      ],
      [
        "label" => "Authors",
        "format" => "raw",
        'value' => function ($data) {

          /** @var \app\models\Book $data */

          $links = [];
          foreach ($data->bookAuthors as $bookAuthor) {
            $links[] = $bookAuthor->author->name;
          }

          return implode(", ", $links);

        }
      ]
    ],
  ]) ?>

</div>
