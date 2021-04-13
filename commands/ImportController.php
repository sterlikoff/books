<?php

namespace app\commands;

use app\models\Author;
use app\models\Book;
use app\models\Category;
use app\models\Photo;
use yii\console\Controller;
use yii\console\ExitCode;

class ImportController extends Controller
{

  private static $statusNames = [
    "PUBLISH" => Book::STATUS_PUBLISHED,
    "MEAP" => Book::STATUS_MEAP,
  ];

  /**
   * @return int Exit code
   */
  public function actionIndex() {

    $url = "https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json";
    $json = file_get_contents($url);

    $data = \yii\helpers\Json::decode($json);

    foreach ($data as $datum) {

      $book = new Book();
      $book->name = $datum["title"];
      $book->isbn = $datum["isbn"];
      $book->page_count = $datum["pageCount"];
      $book->date_published = $datum["publishedDate"]["\$date"];
      $book->short_text = $datum["shortDescription"];
      $book->long_text = $datum["longDescription"];
      $book->status = self::$statusNames[$datum["status"]] ?: Book::STATUS_UNKNOWN;

      if ($url = $datum["thumbnailUrl"]) {
        $book->photo_id = Photo::createByUrl($url)->id;
      }

      foreach ($datum["authors"] as $authorName) {

        $author = Author::find()->filterWhere([
          "name" => $authorName
        ])->one();

        if (!$author) {
          $author = new Author();
          $author->name = $authorName;
          $author->save();
        }

        $book->authorIds[] = $author->id;

      }

      foreach ($datum["categories"] as $categoryName) {

        $category = Category::find()->filterWhere([
          "name" => $categoryName
        ])->one();

        if (!$category) {
          $category = new Category();
          $category->name = $categoryName;
          $category->save();
        }

        $book->categoriesIds[] = $category->id;

      }

      $book->save(false);

    }

    return ExitCode::OK;
  }

}
