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
			[['banner_id', 'publish', 'cat_id', 'creation_id', 'modified_id', 'oClick', 'oView'], 'integer'],
			[['title', 'url', 'creation_date', 'modified_date', 'updated_date',
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
			'grid grid', 
			'category category', 
			// 'category.title categoryTitle', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && (in_array($params['sort'], ['cat_id', '-cat_id']) || in_array($params['sort'], ['categoryName', '-categoryName']))) || (isset($params['categoryName']) && $params['categoryName'] != '')) {
            $query->joinWith(['category.title categoryTitle']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')) {
            $query->joinWith(['creation creation']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['modifiedDisplayname', '-modifiedDisplayname'])) || (isset($params['modifiedDisplayname']) && $params['modifiedDisplayname'] != '')) {
            $query->joinWith(['modified modified']);
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
			'asc' => ['categoryTitle.message' => SORT_ASC],
			'desc' => ['categoryTitle.message' => SORT_DESC],
		];
		$attributes['categoryName'] = [
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
        $attributes['oClick'] = [
            'asc' => ['grid.click' => SORT_ASC],
            'desc' => ['grid.click' => SORT_DESC],
        ];
        $attributes['oView'] = [
            'asc' => ['grid.view' => SORT_ASC],
            'desc' => ['grid.view' => SORT_DESC],
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
			'category.type' => 'linktree',
		]);

        if (isset($params['oClick']) && $params['oClick'] != '') {
            if ($this->oClick == 1) {
                $query->andWhere(['<>', 'grid.click', 0]);
            } else if ($this->oClick == 0) {
                $query->andWhere(['=', 'grid.click', 0]);
            }
        }
        if (isset($params['oView']) && $params['oView'] != '') {
            if ($this->oView == 1) {
                $query->andWhere(['<>', 'grid.view', 0]);
            } else if ($this->oView == 0) {
                $query->andWhere(['=', 'grid.view', 0]);
            }
        }

        if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.url', $this->url])
			->andFilterWhere(['like', 'categoryTitle.message', $this->categoryName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
