<?php
/**
 * Banners
 *
 * This is the ActiveQuery class for [[\ommu\banner\models\Banners]].
 * @see \ommu\banner\models\Banners
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 30 April 2018, 12:43 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\query;

use Yii;

class Banners extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function published() 
	{
		return $this->andWhere(['publish' => 1])
			->andWhere(['not in', 'cast(expired_date as date)', ['0000-00-00','1970-01-01']])
			->andWhere(['>=', 'cast(expired_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')])
			->andWhere(['<=', 'cast(published_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function permanent() 
	{
		return $this->andWhere(['publish' => 1])
			->andWhere(['in', 'cast(expired_date as date)', ['0000-00-00','1970-01-01']])
			->andWhere(['<=', 'cast(published_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function pending() 
	{
		return $this->andWhere(['publish' => 1])
			->andWhere(['>', 'cast(published_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function expired() 
	{
		return $this->andWhere(['publish' => 1])
			->andWhere(['<', 'cast(expired_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted() 
	{
		return $this->andWhere(['publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\Banners[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\Banners|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
