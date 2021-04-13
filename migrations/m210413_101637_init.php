<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210413_101637_init
 */
class m210413_101637_init extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp() {

    $this->createTable("photo", [
      "id" => Schema::TYPE_PK,
      "url" => Schema::TYPE_STRING,
      "path" => Schema::TYPE_STRING,
    ]);

    $this->createTable("book", [
      "id" => Schema::TYPE_PK,
      "name" => Schema::TYPE_STRING,
      "isbn" => Schema::TYPE_STRING,
      "page_count" => Schema::TYPE_INTEGER,
      "date_published" => Schema::TYPE_DATETIME,
      "short_text" => Schema::TYPE_STRING,
      "long_text" => Schema::TYPE_TEXT,
      "status" => Schema::TYPE_TINYINT,
      "photo_id" => Schema::TYPE_INTEGER,
    ]);

    $this->addForeignKey("book_photo", "book", "photo_id", "photo", "id");

    $this->createTable("author", [
      "id" => Schema::TYPE_PK,
      "name" => Schema::TYPE_STRING,
    ]);

    $this->createTable("book_author", [
      "id" => Schema::TYPE_PK,
      "book_id" => Schema::TYPE_INTEGER,
      "author_id" => Schema::TYPE_INTEGER,
    ]);

    $this->addForeignKey("book_author_book", "book_author", "book_id", "book", "id");
    $this->addForeignKey("book_author_author", "book_author", "author_id", "author", "id");

    $this->createTable("category", [
      "id" => Schema::TYPE_PK,
      "name" => Schema::TYPE_STRING,
    ]);

    $this->createTable("book_category", [
      "id" => Schema::TYPE_PK,
      "book_id" => Schema::TYPE_INTEGER,
      "category_id" => Schema::TYPE_INTEGER,
    ]);

    $this->addForeignKey("book_category_book", "book_category", "book_id", "book", "id");
    $this->addForeignKey("book_category_category", "book_category", "category_id", "category", "id");

  }

  /**
   * {@inheritdoc}
   */
  public function safeDown() {

    $this->dropTable("book_category");
    $this->dropTable("category");
    $this->dropTable("book_author");
    $this->dropTable("author");
    $this->dropTable("book");
    $this->dropTable("photo");

  }

}