<?php
/**
 * BannersQuery
 *
 * This is the ActiveQuery class for [[\app\modules\banner\models\Banners]].
 * @see \app\modules\banner\models\Banners
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 30 April 2018, 12:43 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace app\modules\banner\models\query;

class BannersQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * @inheritdoc
	 */
	public function published() 
	{
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * @inheritdoc
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\banner\models\Banners[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\banner\models\Banners|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
