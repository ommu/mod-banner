<?php
/**
 * BannerViews
 *
 * BannerViews represents the model behind the search form about `ommu\banner\models\BannerViews`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 6 October 2017, 13:16 WIB
 * @modified date 24 January 2019, 17:54 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\banner\models\BannerViews as BannerViewsModel;

class BannerViews extends BannerViewsModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['view_id', 'banner_id', 'user_id', 'views'], 'integer'],
			[['view_date', 'view_ip',
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
	public function search($params, $column=null)
	{
        if (!($column && is_array($column))) {
            $query = BannerViewsModel::find()->alias('t');
        } else {
            $query = BannerViewsModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'banner banner',
			// 'banner.category.title category',
			// 'user user'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['bannerTitle', '-bannerTitle'])) || (
            (isset($params['bannerTitle']) && $params['bannerTitle'] != '') ||
            (isset($params['categoryId']) && $params['categoryId'] != '') ||
            (isset($params['category']) && $params['category'] != '')
        )) {
            $query->joinWith(['banner banner']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['categoryId', '-categoryId']))) {
            $query->joinWith(['categoryTitle categoryTitle']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['userDisplayname', '-userDisplayname'])) || 
            (isset($params['userDisplayname']) && $params['userDisplayname'] != '')
        ) {
            $query->joinWith(['user user']);
        }

		$query->groupBy(['view_id']);

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
		$attributes['categoryId'] = [
			'asc' => ['categoryTitle.message' => SORT_ASC],
			'desc' => ['categoryTitle.message' => SORT_DESC],
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
			'cast(t.view_date as date)' => $this->view_date,
			'banner.cat_id' => isset($params['category']) ? $params['category'] : $this->categoryId,
		]);

        if (isset($params['views']) && $params['views'] != '') {
            if ($this->views == 1) {
                $query->andWhere(['<>', 'views', 0]);
            } else if ($this->views == 0) {
                $query->andWhere(['=', 'views', 0]);
            }
        }

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'banner.title', $this->bannerTitle])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname]);

		return $dataProvider;
	}
}
