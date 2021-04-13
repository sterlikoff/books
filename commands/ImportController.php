<?php

namespace app\commands;

use app\models\Author;
use app\models\Book;
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

}
