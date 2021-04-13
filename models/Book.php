<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $isbn
 * @property int|null $page_count
 * @property string|null $date_published
 * @property string|null $short_text
 * @property string|null $long_text
 * @property int|null $status
 * @property int|null $photo_id
 *
 * @property Photo $photo
 * @property BookAuthor[] $bookAuthors
 * @property BookCategory[] $bookCategories
 */
class Book extends ActiveRecord
{

  const STATUS_UNKNOWN = 0;
  const STATUS_PUBLISHED = 1;
  const STATUS_MEAP = 2;

  public $authorIds = [];
  public $categoriesIds = [];

  public static $statusTitles = [
    self::STATUS_UNKNOWN => "New",
    self::STATUS_PUBLISHED => "Published",
    self::STATUS_MEAP => "MEAP",
  ];

  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'book';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      ['name', 'required'],
      [['page_count', 'status', 'photo_id'], 'integer'],
      [['date_published'], 'safe'],
      [['long_text'], 'string'],
      [['name', 'isbn', 'short_text'], 'string', 'max' => 255],
      [['authorIds', 'categoriesIds'], 'safe'],
      [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photo::className(), 'targetAttribute' => ['photo_id' => 'id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'name' => 'Name',
      'isbn' => 'Isbn',
      'page_count' => 'Page Count',
      'date_published' => 'Date Published',
      'short_text' => 'Short Text',
      'long_text' => 'Long Text',
      'status' => 'Status',
      'photo_id' => 'Photo ID',
    ];
  }

  /**
   * Gets query for [[Photo]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getPhoto() {
    return $this->hasOne(Photo::className(), ['id' => 'photo_id']);
  }

  /**
   * Gets query for [[BookAuthors]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getBookAuthors() {
    return $this->hasMany(BookAuthor::className(), ['book_id' => 'id']);
  }

  /**
   * Gets query for [[BookCategories]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getBookCategories() {
    return $this->hasMany(BookCategory::className(), ['book_id' => 'id']);
  }

  private function saveAuthors() {

    foreach ($this->getBookAuthors()->all() as $bookAuthor) {
      if (!in_array($bookAuthor->author_id, $this->authorIds)) {
        $bookAuthor->delete();
      }
    }

    foreach ($this->authorIds as $authorId) {
      $link = BookAuthor::find()->filterWhere([
        "author_id" => $authorId,
        "book_id" => $this->id,
      ])->one();

      if (!$link) {
        $link = new BookAuthor();
        $link->author_id = $authorId;
        $link->book_id = $this->id;
        $link->save();
      }

    }

  }

  private function saveCategories() {

    foreach ($this->getBookCategories()->all() as $bookCategory) {
      if (!in_array($bookCategory->category_id, $this->categoriesIds)) {
        $bookCategory->delete();
      }
    }

    foreach ($this->categoriesIds as $categoryId) {
      $link = BookCategory::find()->filterWhere([
        "category_id" => $categoryId,
        "book_id" => $this->id,
      ])->one();

      if (!$link) {
        $link = new BookCategory();
        $link->category_id = $categoryId;
        $link->book_id = $this->id;
      }

    }

  }

  public function afterSave($insert, $changedAttributes) {

    $this->saveAuthors();
    $this->saveCategories();

    parent::afterSave($insert, $changedAttributes);
  }

  protected function uploadImage() {

    if ($image = UploadedFile::getInstanceByName("image")) {

      $photo = new Photo();
      $photo->instance = $image;
      $photo->save();

      $this->photo_id = $photo->id;

    }

  }

  /**
   * @param array $data
   * @param null $formName
   * @return bool
   */
  public function load($data, $formName = null) {
    $this->uploadImage();
    return parent::load($data, $formName);
  }

}
