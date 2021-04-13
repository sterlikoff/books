<?php

namespace app\models;

use Exception;
use yii\web\UploadedFile;

/**
 * This is the model class for table "photo".
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $path
 *
 * @property Book[] $books
 */
class Photo extends \yii\db\ActiveRecord
{

  /** @var UploadedFile */
  public $instance;

  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'photo';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['url', 'path'], 'string', 'max' => 255],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'url' => 'Url',
      'path' => 'Path',
    ];
  }

  /**
   * Gets query for [[Books]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getBooks() {
    return $this->hasMany(Book::className(), ['photo_id' => 'id']);
  }

  public static function getPath() {
    return realpath(dirname(__FILE__) . '/../web/media');
  }

  /**
   * @return false|string
   */
  public function getLocal() {

    if (!$this->path) {
      return false;
    }

    $path = static::getPath();
    return "$path/$this->path";

  }

  /**
   * @return bool
   */
  public function fileExists() {
    return file_exists($this->getLocal());
  }

  /**
   * @param bool $insert
   * @return bool
   */
  public function beforeSave($insert) {

    $filename = md5($this->url) . "." . pathinfo($this->url, PATHINFO_EXTENSION);
    $path = $this::getPath();

    if ($this->url) {

      if (!$this->fileExists()) {

        try {

          if (file_put_contents("$path/$filename", file_get_contents($this->url))) {
            $this->path = $filename;
          }

        } catch (Exception $e) {

        }

      }

    }

    if ($this->instance instanceof UploadedFile) {
      $this->instance->saveAs("$path/$filename");
      $this->path = $filename;
    }

    return parent::beforeSave($insert);
  }

  /**
   * @param string $url
   *
   * @return static
   */
  public static function createByUrl($url) {

    $model = new static();
    $model->url = $url;
    $model->save();

    return $model;

  }

  /**
   * @return string
   */
  public function getUrl() {
    return "/media/$this->path";
  }

}
