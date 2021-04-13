<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $subject
 * @property string|null $email
 * @property string|null $body
 */
class Contact extends \yii\db\ActiveRecord
{

  public $verifyCode;

  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'contact';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [

      [['email', 'body'], 'required'],
      [['body'], 'string'],
      [['name', 'subject', 'email'], 'string', 'max' => 255],
      ['email', 'email'],
      ['verifyCode', 'captcha'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'name' => 'Name',
      'subject' => 'Subject',
      'email' => 'Email',
      'body' => 'Body',
    ];
  }

  public function afterSave($insert, $changedAttributes) {

    if ($this->isNewRecord) {

      Yii::$app->mailer->compose()
        ->setTo(YYii::$app->params['adminEmail'])
        ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
        ->setReplyTo([$this->email => $this->name])
        ->setSubject($this->subject)
        ->setTextBody($this->body)
        ->send();

    }

    parent::afterSave($insert, $changedAttributes);
  }

}
