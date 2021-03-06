<?php
/**
 * BannerClickHistory
 *
 * This is the ActiveQuery class for [[\ommu\banner\models\BannerClickHistory]].
 * @see \ommu\banner\models\BannerClickHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 30 April 2018, 12:41 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\query;

class BannerClickHistory extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\BannerClickHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\BannerClickHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
