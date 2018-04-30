<?php
/**
 * BannerViews
 * version: 0.0.1
 *
 * BannerViews represents the model behind the search form about `app\modules\banner\models\BannerViews`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 6 October 2017, 13:16 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\banner\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\banner\models\BannerViews as BannerViewsModel;
//use app\modules\banner\models\Banners;
//use app\coremodules\user\models\Users;

class BannerViews extends BannerViewsModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['view_id', 'banner_id', 'user_id', 'views'], 'integer'],
			[['view_date', 'view_ip', 'banner_search', 'user_search', 'category_search'], 'safe'],
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
		$query = BannerViewsModel::find()->alias('t');
		$query->joinWith(['banner banner', 'user user', 'banner.category.title category_title']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['banner_search'] = [
			'asc' => ['banner.title' => SORT_ASC],
			'desc' => ['banner.title' => SORT_DESC],
		];
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['category_search'] = [
			'asc' => ['category_title.message' => SORT_ASC],
			'desc' => ['category_title.message' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['view_id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.view_id' => $this->view_id,
			't.banner_id' => isset($params['banner']) ? $params['banner'] : $this->banner_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.views' => $this->views,
			'cast(t.view_date as date)' => $this->view_date,
			'banner.cat_id' => $this->category_search,
		]);

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'banner.title', $this->banner_search])
			->andFilterWhere(['like', 'user.displayname', $this->user_search]);

		return $dataProvider;
	}
}
