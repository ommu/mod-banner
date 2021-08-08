<?php
/**
 * LinkTree
 *
 * LinkTree represents the model behind the search form about `ommu\banner\models\LinkTree`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 22:42 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\banner\models\LinkTree as LinkTreeModel;

class LinkTree extends LinkTreeModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['banner_id', 'publish', 'cat_id', 'creation_id', 'modified_id', 'click', 'view'], 'integer'],
			[['title', 'url', 'creation_date', 'modified_date', 'updated_date', 'slug',
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
            $query = LinkTreeModel::find()->alias('t');
        } else {
            $query = LinkTreeModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			// 'category.title category', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['cat_id', '-cat_id'])) || (isset($params['categoryName']) && $params['categoryName'] != '')) {
            $query->joinWith(['category.title category']);
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
                $query->select(['t.*', 'count(clicks.click_id) as click']);
            }
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['view', '-view'])) || (isset($params['view']) && $params['view'] != '')) {
            $query->joinWith(['views views']);
            if (isset($params['sort']) && in_array($params['sort'], ['view', '-view'])) {
                $query->select(['t.*', 'count(views.view_id) as view']);
            }
        }

        if (!Yii::$app->request->get('creation')) {
            $query->select(['t.*', 'count(t.banner_id) as link']);
            $query->groupBy(['creation_id']);
        } else {
            $query->groupBy(['banner_id']);
        }

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
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
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
        $attributes['link'] = [
            'asc' => ['link' => SORT_ASC],
            'desc' => ['link' => SORT_DESC],
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
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
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
                $query->andWhere(['is not', 'clicks.click_id', null]);
            } else if ($this->click == 0) {
                $query->andWhere(['is', 'clicks.click_id', null]);
            }
        }

		if (isset($params['view']) && $params['view'] != '') {
            if ($this->view == 1) {
                $query->andWhere(['is not', 'views.view_id', null]);
            } else if ($this->view == 0) {
                $query->andWhere(['is', 'views.view_id', null]);
            }
        }

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.url', $this->url])
			->andFilterWhere(['like', 't.slug', $this->slug])
			->andFilterWhere(['like', 'category.message', $this->categoryName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
