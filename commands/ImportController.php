<?php

namespace app\commands;

use app\models\Author;
use app\models\Book;
use app\models\BookAuthor;
use app\models\BookCategory;
use app\models\Category;
use app\models\Importer;
use app\models\Photo;
use yii\console\Controller;
use yii\console\ExitCode;

class ImportController extends Controller
{

  /**
   * @return int Exit code
   */
  public function actionIndex() {

    $importer = new Importer("https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json");
    $importer->run();

    return ExitCode::OK;

  }

  protected function delete($models) {
    array_map(function ($item) {
      $item->delete();
    }, $models);
  }

  public function actionClear() {
    $this->delete(BookCategory::find()->all());
    $this->delete(BookAuthor::find()->all());
    $this->delete(Book::find()->all());
    $this->delete(Author::find()->all());
    $this->delete(Category::find()->all());
    $this->delete(Photo::find()->all());
  }

}
