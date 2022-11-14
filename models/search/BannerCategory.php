<?php
/**
 * BannerCategory
 *
 * BannerCategory represents the model behind the search form about `ommu\banner\models\BannerCategory`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 5 October 2017, 15:43 WIB
 * @modified date 24 January 2019, 13:06 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\banner\models\BannerCategory as BannerCategoryModel;

class BannerCategory extends BannerCategoryModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['cat_id', 'publish', 'name', 'desc', 'banner_limit', 'creation_id', 'modified_id', 'oPublish', 'oPermanent', 'oPending', 'oExpired', 'oUnpublish', 'oAll'], 'integer'],
			[['code', 'banner_size', 'creation_date', 'modified_date', 'updated_date',
				'name_i', 'desc_i', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
            $query = BannerCategoryModel::find()->alias('t');
        } else {
            $query = BannerCategoryModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'view view',
			// 'title title', 
			// 'description description', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['oPublish', '-oPublish', 'oPermanent', '-oPermanent', 'oPending', '-oPending', 'oExpired', '-oExpired', 'oUnpublish', '-oUnpublish', 'oAll', '-oAll'])) || (
            (isset($params['oPublish']) && $params['oPublish'] != '') ||
            (isset($params['oPermanent']) && $params['oPermanent'] != '') ||
            (isset($params['oPending']) && $params['oPending'] != '') ||
            (isset($params['oExpired']) && $params['oExpired'] != '') ||
            (isset($params['oUnpublish']) && $params['oUnpublish'] != '') ||
            (isset($params['oAll']) && $params['oAll'] != '')
        )) {
            $query->joinWith(['view view']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['name_i', '-name_i'])) || (isset($params['name_i']) && $params['name_i'] != '')) {
            $query->joinWith(['title title']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['desc_i', '-desc_i'])) || (isset($params['desc_i']) && $params['desc_i'] != '')) {
            $query->joinWith(['description description']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')) {
            $query->joinWith(['creation creation']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['modifiedDisplayname', '-modifiedDisplayname'])) || (isset($params['modifiedDisplayname']) && $params['modifiedDisplayname'] != '')) {
            $query->joinWith(['modified modified']);
        }

		$query->groupBy(['cat_id']);

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
		$attributes['name_i'] = [
			'asc' => ['title.message' => SORT_ASC],
			'desc' => ['title.message' => SORT_DESC],
		];
		$attributes['desc_i'] = [
			'asc' => ['description.message' => SORT_ASC],
			'desc' => ['description.message' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['oPublish'] = [
			'asc' => ['view.publish' => SORT_ASC],
			'desc' => ['view.publish' => SORT_DESC],
		];
		$attributes['oPermanent'] = [
			'asc' => ['view.permanent' => SORT_ASC],
			'desc' => ['view.permanent' => SORT_DESC],
		];
		$attributes['oPending'] = [
			'asc' => ['view.pending' => SORT_ASC],
			'desc' => ['view.pending' => SORT_DESC],
		];
		$attributes['oExpired'] = [
			'asc' => ['view.expired' => SORT_ASC],
			'desc' => ['view.expired' => SORT_DESC],
		];
		$attributes['oUnpublish'] = [
			'asc' => ['view.unpublish' => SORT_ASC],
			'desc' => ['view.unpublish' => SORT_DESC],
		];
		$attributes['oAll'] = [
			'asc' => ['view.all' => SORT_ASC],
			'desc' => ['view.all' => SORT_DESC],
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
			't.type' => 'banner',
			't.name' => $this->name,
			't.desc' => $this->desc,
			't.banner_limit' => $this->banner_limit,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

        if (isset($params['oPublish']) && $params['oPublish'] != '') {
            if ($this->oPublish == 1) {
                $query->andWhere(['<>', 'view.publish', 0]);
            } else if ($this->oPublish == 0) {
                $query->andWhere(['=', 'view.publish', 0]);
            }
        }
        if (isset($params['oPermanent']) && $params['oPermanent'] != '') {
            if ($this->oPermanent == 1) {
                $query->andWhere(['<>', 'view.permanent', 0]);
            } else if ($this->oPermanent == 0) {
                $query->andWhere(['=', 'view.permanent', 0]);
            }
        }
        if (isset($params['oPending']) && $params['oPending'] != '') {
            if ($this->oPending == 1) {
                $query->andWhere(['<>', 'view.pending', 0]);
            } else if ($this->oPending == 0) {
                $query->andWhere(['=', 'view.pending', 0]);
            }
        }
        if (isset($params['oExpired']) && $params['oExpired'] != '') {
            if ($this->oExpired == 1) {
                $query->andWhere(['<>', 'view.expired', 0]);
            } else if ($this->oExpired == 0) {
                $query->andWhere(['=', 'view.expired', 0]);
            }
        }
        if (isset($params['oUnpublish']) && $params['oUnpublish'] != '') {
            if ($this->oUnpublish == 1) {
                $query->andWhere(['<>', 'view.unpublish', 0]);
            } else if ($this->oUnpublish == 0) {
                $query->andWhere(['=', 'view.unpublish', 0]);
            }
        }
        if (isset($params['oAll']) && $params['oAll'] != '') {
            if ($this->oAll == 1) {
                $query->andWhere(['<>', 'view.all', 0]);
            } else if ($this->oAll == 0) {
                $query->andWhere(['=', 'view.all', 0]);
            }
        }

        if ((!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) && !$this->publish) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		$query->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.banner_size', $this->banner_size])
			->andFilterWhere(['like', 'title.message', $this->name_i])
			->andFilterWhere(['like', 'description.message', $this->desc_i])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
