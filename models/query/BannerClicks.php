<?php
/**
 * BannerClicks
 *
 * This is the ActiveQuery class for [[\ommu\banner\models\BannerClicks]].
 * @see \ommu\banner\models\BannerClicks
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 30 April 2018, 12:44 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\query;

class BannerClicks extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\BannerClicks[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\BannerClicks|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
