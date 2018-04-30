<?php
/**
 * Banners
 * version: 0.0.1
 *
 * Banners represents the model behind the search form about `app\modules\banner\models\Banners`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 6 October 2017, 08:14 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\banner\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\banner\models\Banners as BannersModel;
//use app\modules\banner\models\BannerCategory;
//use app\coremodules\user\models\Users;

class Banners extends BannersModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['banner_id', 'publish', 'cat_id', 'creation_id', 'modified_id'], 'integer'],
			[['title', 'url', 'banner_filename', 'banner_desc', 'published_date', 'expired_date', 
				'creation_date', 'modified_date', 'slug', 'creation_search', 'modified_search'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = BannersModel::find()->alias('t');
		$query->joinWith(['category category', 'creation creation', 'modified modified', 'view view', 'category.title category_title']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['creation_search'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['click_search'] = [
			'asc' => ['view.clicks' => SORT_ASC],
			'desc' => ['view.clicks' => SORT_DESC],
		];
		$attributes['view_search'] = [
			'asc' => ['view.views' => SORT_ASC],
			'desc' => ['view.views' => SORT_DESC],
		];
		$attributes['cat_id'] = [
			'asc' => ['category_title.message' => SORT_ASC],
			'desc' => ['category_title.message' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['banner_id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.banner_id' => $this->banner_id,
			't.publish' => isset($params['unpublish']) ? 0 : $this->publish,
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			't.published_date' => $this->published_date,
			't.expired_date' => $this->expired_date,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
		]);
		
		if(isset($_GET['publish'])) {
			$query->andFilterCompare('t.publish', 1);
			$query->andFilterCompare('cast(t.expired_date as date)', '>='.Yii::$app->formatter->asDate('now', 'php:Y-m-d'));
			$query->andFilterCompare('cast(t.published_date as date)', '>='.Yii::$app->formatter->asDate('now', 'php:Y-m-d'));
		} elseif(isset($_GET['pending'])) {
			$query->andFilterCompare('t.publish', 1);
			$query->andFilterCompare('cast(t.published_date as date)', '>'.Yii::$app->formatter->asDate('now', 'php:Y-m-d'));
		} elseif(isset($_GET['expired'])) {
			$query->andFilterCompare('t.publish', 1);
			$query->andFilterCompare('cast(t.expired_date as date)', '<'.Yii::$app->formatter->asDate('now', 'php:Y-m-d'));
		} else {
			if(!isset($_GET['trash']))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		}

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.url', $this->url])
			->andFilterWhere(['like', 't.banner_filename', $this->banner_filename])
			->andFilterWhere(['like', 't.banner_desc', $this->banner_desc])
			->andFilterWhere(['like', 't.slug', $this->slug])
			->andFilterWhere(['like', 'creation.displayname', $this->creation_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
