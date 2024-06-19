<?php
/**
 * BannerClickHistory
 *
 * BannerClickHistory represents the model behind the search form about `ommu\banner\models\BannerClickHistory`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 13:29 WIB
 * @modified date 24 January 2019, 17:55 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\banner\models\BannerClickHistory as BannerClickHistoryModel;

class BannerClickHistory extends BannerClickHistoryModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'click_id', 
                'categoryId', 'bannerId'], 'integer'],
			[['click_date', 'click_ip', 
                'bannerTitle', 'userDisplayname'], 'safe'],
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
	public function search($params, $column=null)
	{
        if (!($column && is_array($column))) {
            $query = BannerClickHistoryModel::find();
        } else {
            $query = BannerClickHistoryModel::find();
        }
		$query->joinWith([
			// 'click click',
			// 'click.banner banner',
			// 'click.banner.category.title category',
			// 'click.user user',
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['bannerTitle', '-bannerTitle'])) || (
            (isset($params['bannerTitle']) && $params['bannerTitle'] != '') ||
            (isset($params['categoryId']) && $params['categoryId'] != '') ||
            (isset($params['category']) && $params['category'] != '')
        )) {
            $query->joinWith(['banner banner']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['userDisplayname', '-userDisplayname'])) || 
            (isset($params['userDisplayname']) && $params['userDisplayname'] != '')
        ) {
            $query->joinWith(['user user']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['categoryId', '-categoryId']))) {
            $query->joinWith(['categoryTitle categoryTitle']);
        }
        if ((isset($params['bannerId']) && $params['bannerId'] != '') || 
            (isset($params['banner']) && $params['banner'] != '')
        ) {
            $query->joinWith(['click click']);
        }

		$query->groupBy(['id']);

        // add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
        // disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['bannerTitle'] = [
			'asc' => ['banner.title' => SORT_ASC],
			'desc' => ['banner.title' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['categoryId'] = [
			'asc' => ['categoryTitle.message' => SORT_ASC],
			'desc' => ['categoryTitle.message' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.click_id' => isset($params['click']) ? $params['click'] : $this->click_id,
			'cast(t.click_date as date)' => $this->click_date,
			'banner.cat_id' => isset($params['category']) ? $params['category'] : $this->categoryId,
			'click.banner_id' => isset($params['banner']) ? $params['banner'] : $this->bannerId,
		]);

		$query->andFilterWhere(['like', 't.click_ip', $this->click_ip])
			->andFilterWhere(['like', 'banner.title', $this->bannerTitle])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname]);

		return $dataProvider;
	}
}
