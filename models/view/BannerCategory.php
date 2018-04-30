<?php
/**
 * BannerCategory
 * version: 0.0.1
 *
 * This is the model class for table "_view_banner_category".
 *
 * The followings are the available columns in table "_view_banner_category":
 * @property integer $cat_id
 * @property string $banners
 * @property string $banner_pending
 * @property string $banner_expired
 * @property string $banner_unpublish
 * @property string $banner_all

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 11 October 2017, 10:32 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\banner\models\view;

use Yii;
use yii\helpers\Url;

class BannerCategory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_view_banner_category';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['cat_id'];
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['cat_id', 'banner_all'], 'integer'],
			[['banners', 'banner_pending', 'banner_expired', 'banner_unpublish'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Cat'),
			'banners' => Yii::t('app', 'Banners'),
			'banner_pending' => Yii::t('app', 'Banner Pending'),
			'banner_expired' => Yii::t('app', 'Banner Expired'),
			'banner_unpublish' => Yii::t('app', 'Banner Unpublish'),
			'banner_all' => Yii::t('app', 'Banner All'),
		];
	}
	
	/**
	 * Set default columns to display
	 */
	public function init() 
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['cat_id'] = 'cat_id';
		$this->templateColumns['banners'] = 'banners';
		$this->templateColumns['banner_pending'] = 'banner_pending';
		$this->templateColumns['banner_expired'] = 'banner_expired';
		$this->templateColumns['banner_unpublish'] = 'banner_unpublish';
		$this->templateColumns['banner_all'] = 'banner_all';
	}
}
