<?php
/**
 * LinkRotatorItem
 *
 * This is the ActiveQuery class for [[\ommu\banner\models\LinkRotatorItem]].
 * @see \ommu\banner\models\LinkRotatorItem
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 August 2021, 19:57 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\query;

class LinkRotatorItem extends \yii\db\ActiveQuery
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
		return $this->andWhere(['t.publish' => 1])
            ->andWhere(['or', 
                ['in', 'cast(expired_date as date)', ['0000-00-00', '1970-01-01']],
                ['>=', 'cast(expired_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')],
            ])
			->andWhere(['<=', 'cast(published_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function permanent()
	{
		return $this->andWhere(['t.publish' => 1])
			->andWhere(['in', 'cast(expired_date as date)', ['0000-00-00', '1970-01-01']])
			->andWhere(['<=', 'cast(published_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function pending()
	{
		return $this->andWhere(['t.publish' => 1])
			->andWhere(['>', 'cast(published_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function expired()
	{
		return $this->andWhere(['t.publish' => 1])
			->andWhere(['<', 'cast(expired_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish()
	{
		return $this->andWhere(['t.publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted()
	{
		return $this->andWhere(['t.publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\LinkRotatorItem[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\LinkRotatorItem|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}