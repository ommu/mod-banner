<?php
/**
 * LinkRotatorItem
 *
 * LinkRotatorItem represents the model behind the search form about `ommu\banner\models\LinkRotatorItem`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 August 2021, 19:58 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\banner\models\LinkRotatorItem as LinkRotatorItemModel;

class LinkRotatorItem extends LinkRotatorItemModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['banner_id', 'publish', 'cat_id', 'creation_id', 'modified_id', 'click', 'view', 'permanent'], 'integer'],
			[['title', 'url', 'banner_desc', 'published_date', 'expired_date', 'creation_date', 'modified_date', 'updated_date',
                'categoryName', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
            $query = LinkRotatorItemModel::find()->alias('t');
        } else {
            $query = LinkRotatorItemModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'category category', 
			// 'category.title categoryTitle', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['cat_id', '-cat_id'])) || (isset($params['categoryName']) && $params['categoryName'] != '')) {
            $query->joinWith(['category.title categoryTitle']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')) {
            $query->joinWith(['creation creation']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['modifiedDisplayname', '-modifiedDisplayname'])) || (isset($params['modifiedDisplayname']) && $params['modifiedDisplayname'] != '')) {
            $query->joinWith(['modified modified']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['click', '-click'])) || (isset($params['click']) && $params['click'] != '')) {
            $query->joinWith(['clicks clicks']);
            if (isset($params['sort']) && in_array($params['sort'], ['click', '-click'])) {
                $query->select(['t.*', 'count(clicks.id) as click']);
            }
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['view', '-view'])) || (isset($params['view']) && $params['view'] != '')) {
            $query->joinWith(['views views']);
            if (isset($params['sort']) && in_array($params['sort'], ['view', '-view'])) {
                $query->select(['t.*', 'count(views.id) as view']);
            }
        }

		$query->groupBy(['banner_id']);

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
		$attributes['cat_id'] = [
			'asc' => ['categoryTitle.message' => SORT_ASC],
			'desc' => ['categoryTitle.message' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
        $attributes['click'] = [
            'asc' => ['click' => SORT_ASC],
            'desc' => ['click' => SORT_DESC],
        ];
        $attributes['view'] = [
            'asc' => ['view' => SORT_ASC],
            'desc' => ['view' => SORT_DESC],
        ];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['banner_id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('banner_id')) {
            unset($params['banner_id']);
        }
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
        $query->andFilterWhere([
			't.banner_id' => $this->banner_id,
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			't.is_banner' => 0,
			'cast(t.published_date as date)' => $this->published_date,
			'cast(t.expired_date as date)' => $this->expired_date,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'category.type' => 'rotator',
		]);

		if (isset($params['trash'])) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        } else {
            if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
                $query->andFilterWhere(['IN', 't.publish', [0,1]]);
            } else {
                $query->andFilterWhere(['t.publish' => $this->publish]);
            }
        }

		if (isset($params['click']) && $params['click'] != '') {
            if ($this->click == 1) {
                $query->andWhere(['is not', 'clicks.id', null]);
            } else if ($this->click == 0) {
                $query->andWhere(['is', 'clicks.id', null]);
            }
        }

		if (isset($params['view']) && $params['view'] != '') {
            if ($this->view == 1) {
                $query->andWhere(['is not', 'views.id', null]);
            } else if ($this->view == 0) {
                $query->andWhere(['is', 'views.id', null]);
            }
        }

		if (isset($params['permanent']) && $params['permanent'] != '') {
            if ($this->permanent == 1) {
                $query->andWhere(['in', 't.expired_date', ['0000-00-00', '1970-01-01', '0002-12-02', '-0001-11-30']]);
            } else if ($this->permanent == 0) {
                $query->andWhere(['not in', 't.expired_date', ['0000-00-00', '1970-01-01', '0002-12-02', '-0001-11-30']]);
            }
        }

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.url', $this->url])
			->andFilterWhere(['like', 't.banner_desc', $this->banner_desc])
			->andFilterWhere(['like', 'categoryTitle.message', $this->categoryName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
