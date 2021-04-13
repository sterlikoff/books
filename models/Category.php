<?php

namespace app\models;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property BookCategory[] $bookCategories
 */
class Category extends \yii\db\ActiveRecord
{
  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'category';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      ['name', 'required'],
      [['name'], 'string', 'max' => 255],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'name' => 'Name',
    ];
  }

  /**
   * Gets query for [[BookCategories]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getBookCategories() {
    return $this->hasMany(BookCategory::className(), ['category_id' => 'id']);
  }
}
