<?php
/**
 * Banners
 *
 * Banners represents the model behind the search form about `ommu\banner\models\Banners`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 08:14 WIB
 * @modified date 13 February 2019, 05:27 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\banner\models\Banners as BannersModel;

class Banners extends BannersModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['banner_id', 'publish', 'cat_id', 'creation_id', 'modified_id'], 'integer'],
			[['title', 'url', 'banner_filename', 'banner_desc', 'published_date', 'expired_date', 'creation_date', 'modified_date', 'updated_date', 'slug',
				'permanent', 'categoryName', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
            $query = BannersModel::find()->alias('t');
        } else {
            $query = BannersModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'view view',
			'category.title category', 
			'creation creation', 
			'modified modified'
		]);

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
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['categoryName'] = [
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
		$attributes['permanent'] = [
			'asc' => ['view.permanent' => SORT_ASC],
			'desc' => ['view.permanent' => SORT_DESC],
		];
		$attributes['views'] = [
			'asc' => ['view.views' => SORT_ASC],
			'desc' => ['view.views' => SORT_DESC],
		];
		$attributes['clicks'] = [
			'asc' => ['view.clicks' => SORT_ASC],
			'desc' => ['view.clicks' => SORT_DESC],
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
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			'cast(t.published_date as date)' => $this->published_date,
			'cast(t.expired_date as date)' => $this->expired_date,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'view.permanent' => $this->permanent,
		]);

        if (isset($params['expired'])) {
			$query->andFilterCompare('t.publish', 1);
            if ($params['expired'] == 'publish') {
				$query->andFilterWhere(['not in', 'cast(expired_date as date)', ['0000-00-00', '1970-01-01']])
					->andFilterWhere(['>=', 'cast(expired_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')])
					->andFilterWhere(['<=', 'cast(published_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);

			} else if ($params['expired'] == 'permanent') {
				$query->andWhere(['in', 'cast(expired_date as date)', ['0000-00-00', '1970-01-01']])
					->andWhere(['<=', 'cast(published_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);

			} else if ($params['expired'] == 'pending') {
                $query->andFilterWhere(['>', 'cast(t.published_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
            } else if ($params['expired'] == 'expired') {
                $query->andFilterWhere(['<', 'cast(t.expired_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
            }

		} else {
            if (isset($params['trash'])) {
                $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
            } else {
                if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
                    $query->andFilterWhere(['IN', 't.publish', [0,1]]);
                } else {
                    $query->andFilterWhere(['t.publish' => $this->publish]);
                }
            }
        }

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.url', $this->url])
			->andFilterWhere(['like', 't.banner_filename', $this->banner_filename])
			->andFilterWhere(['like', 't.banner_desc', $this->banner_desc])
			->andFilterWhere(['like', 't.slug', $this->slug])
			->andFilterWhere(['like', 'category.message', $this->categoryName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
