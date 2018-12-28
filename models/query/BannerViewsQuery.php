<?php
/**
 * BannerViewsQuery
 *
 * This is the ActiveQuery class for [[\ommu\banner\models\BannerViews]].
 * @see \ommu\banner\models\BannerViews
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 30 April 2018, 12:44 WIB
 * @modified date 30 April 2018, 12:44 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\query;

class BannerViewsQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\BannerViews[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\BannerViews|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
