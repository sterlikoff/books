<?php
/**
 * @var $this yii\web\View
 */

use app\models\Category;
use yii\helpers\Html;

$this->title = 'Books Catalog';

?>
<div class="site-index">

  <div class="jumbotron">

    <h1>Welcome!</h1>

    <p class="lead">
      Choose your category:
    </p>

  </div>

  <div class="body-content">

    <div class="row">

      <?php foreach (Category::find()->all() as $i => $category): ?>
      <?php /** @var Category $category */ ?>

      <div class="col-lg-4">
        <h2><?= $category->name; ?></h2>

        <p>
          <?= Html::a("View " . count($category->getBookCategories()->all()) . " books", ["/book/index", "categoryId" => $category->id], [
            "class" => "btn btn-default",
          ]); ?>
        </p>
      </div>

      <?php if ($i % 3 === 2): ?>
    </div>
    <div class="row">
      <?php endif; ?>

      <?php endforeach; ?>

    </div>

  </div>

</div>