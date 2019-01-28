<?php
/**
 * BannerClicks
 *
 * BannerClicks represents the model behind the search form about `ommu\banner\models\BannerClicks`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 14 October 2017, 08:11 WIB
 * @modified date 24 January 2019, 17:53 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\banner\models\BannerClicks as BannerClicksModel;

class BannerClicks extends BannerClicksModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['click_id', 'banner_id', 'user_id', 'clicks'], 'integer'],
			[['click_date', 'click_ip',
				'categoryId', 'bannerTitle', 'userDisplayname'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
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
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = BannerClicksModel::find()->alias('t');
		$query->joinWith([
			'banner banner',
			'banner.category.title category',
			'user user'
		]);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['categoryId'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['bannerTitle'] = [
			'asc' => ['banner.title' => SORT_ASC],
			'desc' => ['banner.title' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['click_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.click_id' => $this->click_id,
			't.banner_id' => isset($params['banner']) ? $params['banner'] : $this->banner_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.clicks' => $this->clicks,
			'cast(t.click_date as date)' => $this->click_date,
			'banner.cat_id' => isset($params['category']) ? $params['category'] : $this->categoryId,
		]);

		$query->andFilterWhere(['like', 't.click_ip', $this->click_ip])
			->andFilterWhere(['like', 'banner.title', $this->bannerTitle])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname]);

		return $dataProvider;
	}
}
