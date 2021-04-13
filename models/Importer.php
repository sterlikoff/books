<?php

namespace app\models;

use yii\helpers\Json;

class Importer
{

  public $url;

  private static $statusNames = [
    "PUBLISH" => Book::STATUS_PUBLISHED,
    "MEAP" => Book::STATUS_MEAP,
  ];

  /**
   * Importer constructor.
   *
   * @param $url
   */
  public function __construct($url) {
    $this->url = $url;
  }

  public function run() {

    $json = file_get_contents($this->url);

    $data = Json::decode($json);

    foreach ($data as $datum) {

      $book = Book::find()->andFilterWhere([
        "isbn" => $datum["isbn"],
      ])->one();

      if (!$book) {
        $book = new Book();
      }

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

      if ($datum["categories"]) {

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

      } else {
        $book->categoriesIds[] = self::defaultCategory()->id;
      }

      $book->save(false);
    }

  }

  /**
   * @return Category
   */
  protected static function defaultCategory() {

    $name = "Новинки";

    $model = Category::find()->andFilterWhere([
      "name" => $name,
    ])->one();

    if (!$model) {
      $model = new Category();
      $model->name = $name;
      $model->save(false);
    }

    return $model;

  }

}