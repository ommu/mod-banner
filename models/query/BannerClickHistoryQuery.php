<?php
/**
 * BannerClickHistoryQuery
 *
 * This is the ActiveQuery class for [[\app\modules\banner\models\BannerClickHistory]].
 * @see \app\modules\banner\models\BannerClickHistory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 30 April 2018, 12:41 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace app\modules\banner\models\query;

class BannerClickHistoryQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * @inheritdoc
	 * @return \app\modules\banner\models\BannerClickHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\banner\models\BannerClickHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
