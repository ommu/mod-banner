<?php
/**
 * BannerCategory
 * version: 0.0.1
 *
 * BannerCategory represents the model behind the search form about `app\modules\banner\models\BannerCategory`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 5 October 2017, 15:43 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\banner\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\banner\models\BannerCategory as BannerCategoryModel;
//use app\coremodules\user\models\Users;

class BannerCategory extends BannerCategoryModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['cat_id', 'publish', 'name', 'desc', 'banner_limit', 'creation_id', 'modified_id'], 'integer'],
			[['cat_code', 'creation_date', 'modified_date', 'slug', 'creation_search', 'modified_search', 'name_i', 'desc_i'], 'safe'],
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
		$query = BannerCategoryModel::find()->alias('t');
		$query->joinWith(['creation creation', 'modified modified', 'title title', 'description description', 'view view']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes = array_diff($attributes, ['banner_size']);
		$attributes['creation_search'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['name_i'] = [
			'asc' => ['title.message' => SORT_ASC],
			'desc' => ['title.message' => SORT_DESC],
		];
		$attributes['desc_i'] = [
			'asc' => ['description.message' => SORT_ASC],
			'desc' => ['description.message' => SORT_DESC],
		];
		$attributes['banner_search'] = [
			'asc' => ['view.banners' => SORT_ASC],
			'desc' => ['view.banners' => SORT_DESC],
		];
		$attributes['pending_search'] = [
			'asc' => ['view.banner_pending' => SORT_ASC],
			'desc' => ['view.banner_pending' => SORT_DESC],
		];
		$attributes['expired_search'] = [
			'asc' => ['view.banner_expired' => SORT_ASC],
			'desc' => ['view.banner_expired' => SORT_DESC],
		];
		$attributes['unpublish_search'] = [
			'asc' => ['view.banner_unpublish' => SORT_ASC],
			'desc' => ['view.banner_unpublish' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['cat_id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.cat_id' => $this->cat_id,
			't.name' => $this->name,
			't.desc' => $this->desc,
			't.banner_limit' => $this->banner_limit,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		$query->andFilterWhere(['like', 't.cat_code', $this->cat_code])
			->andFilterWhere(['like', 't.banner_size', $this->banner_size])
			->andFilterWhere(['like', 't.slug', $this->slug])
			->andFilterWhere(['like', 'creation.displayname', $this->creation_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search])
			->andFilterWhere(['like', 'title.message', $this->name_i])
			->andFilterWhere(['like', 'description.message', $this->desc_i]);

		return $dataProvider;
	}
}
