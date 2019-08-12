<?php
/**
 * BannerCategory
 *
 * This is the ActiveQuery class for [[\ommu\banner\models\BannerCategory]].
 * @see \ommu\banner\models\BannerCategory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 30 April 2018, 12:43 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\query;

class BannerCategory extends \yii\db\ActiveQuery
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
		return $this->andWhere(['publish' => 1]);
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
	 * @return \ommu\banner\models\BannerCategory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\BannerCategory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
