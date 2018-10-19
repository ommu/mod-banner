<?php
/**
 * BannerClicksQuery
 *
 * This is the ActiveQuery class for [[\ommu\banner\models\BannerClicks]].
 * @see \ommu\banner\models\BannerClicks
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 30 April 2018, 12:44 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace ommu\banner\models\query;

class BannerClicksQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * @inheritdoc
	 * @return \ommu\banner\models\BannerClicks[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \ommu\banner\models\BannerClicks|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
