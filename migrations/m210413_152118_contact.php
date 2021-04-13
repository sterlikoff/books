<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210413_152118_contact
 */
class m210413_152118_contact extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp() {

    $this->createTable("contact", [
      "id" => Schema::TYPE_PK,
      "name" => Schema::TYPE_STRING,
      "subject" => Schema::TYPE_STRING,
      "email" => Schema::TYPE_STRING,
      "body" => Schema::TYPE_TEXT,
    ]);

  }

  /**
   * {@inheritdoc}
   */
  public function safeDown() {
    $this->dropTable("contact");
  }

}
