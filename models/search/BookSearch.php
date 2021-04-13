<?php

namespace app\models\search;

use app\models\BookCategory;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Book;

/**
 * BookSearch represents the model behind the search form of `app\models\Book`.
 */
class BookSearch extends Book
{

  public $searchCategoryId;

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['id', 'page_count', 'status', 'photo_id'], 'integer'],
      [['name', 'isbn', 'date_published', 'short_text', 'long_text'], 'safe'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function scenarios() {
    // bypass scenarios() implementation in the parent class
    return Model::scenarios();
  }

  /**
   * Creates data provider instance with search query applied
   *
   * @param array $params
   *
   * @return ActiveDataProvider
   */
  public function search($params) {
    $query = Book::find();

    // add conditions that should always apply here

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
    ]);

    $this->load($params);

    if (!$this->validate()) {
      // uncomment the following line if you do not want to return any records when validation fails
      // $query->where('0=1');
      return $dataProvider;
    }

    // grid filtering conditions
    $query->andFilterWhere([
      'id' => $this->id,
      'page_count' => $this->page_count,
      'date_published' => $this->date_published,
      'status' => $this->status,
      'photo_id' => $this->photo_id,
    ]);

    $query->andFilterWhere(['like', 'name', $this->name])
      ->andFilterWhere(['like', 'isbn', $this->isbn])
      ->andFilterWhere(['like', 'short_text', $this->short_text])
      ->andFilterWhere(['like', 'long_text', $this->long_text]);

    if ($this->searchCategoryId) {
      $query->joinWith(["bookCategories"])->andFilterWhere([
        BookCategory::tableName() . ".category_id" => $this->searchCategoryId,
      ]);
    }

    return $dataProvider;
  }
}
