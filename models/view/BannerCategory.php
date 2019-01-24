<?php
/**
 * BannerCategory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 11 October 2017, 10:32 WIB
 * @modified date 24 January 2019, 16:50 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-banner
 *
 * This is the model class for table "_banner_category".
 *
 * The followings are the available columns in table "_banner_category":
 * @property integer $cat_id
 * @property string $banners
 * @property string $banner_permanent
 * @property string $banner_pending
 * @property string $banner_expired
 * @property string $banner_unpublish
 * @property integer $banner_all
 *
 */

namespace ommu\banner\models\view;

use Yii;

class BannerCategory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_banner_category';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['cat_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['cat_id', 'banner_all'], 'integer'],
			[['banners', 'banner_permanent', 'banner_pending', 'banner_expired', 'banner_unpublish'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Category'),
			'banners' => Yii::t('app', 'Banners'),
			'banner_permanent' => Yii::t('app', 'Banner Permanent'),
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
		$this->templateColumns['cat_id'] = [
			'attribute' => 'cat_id',
			'value' => function($model, $key, $index, $column) {
				return $model->cat_id;
			},
		];
		$this->templateColumns['banners'] = [
			'attribute' => 'banners',
			'value' => function($model, $key, $index, $column) {
				return $model->banners;
			},
		];
		$this->templateColumns['banner_permanent'] = [
			'attribute' => 'banner_permanent',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_permanent;
			},
		];
		$this->templateColumns['banner_pending'] = [
			'attribute' => 'banner_pending',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_pending;
			},
		];
		$this->templateColumns['banner_expired'] = [
			'attribute' => 'banner_expired',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_expired;
			},
		];
		$this->templateColumns['banner_unpublish'] = [
			'attribute' => 'banner_unpublish',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_unpublish;
			},
		];
		$this->templateColumns['banner_all'] = [
			'attribute' => 'banner_all',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_all;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['cat_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
