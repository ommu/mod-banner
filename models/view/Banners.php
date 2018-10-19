<?php
/**
 * Banners
 * 
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 11 October 2017, 10:31 WIB
 * @modified date 30 April 2018, 11:53 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "_banners".
 *
 * The followings are the available columns in table "_banners":
 * @property integer $banner_id
 * @property integer $publish
 * @property integer $permanent
 * @property string $views
 * @property string $clicks
 *
 */

namespace ommu\banner\models\view;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class Banners extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_banners';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['banner_id'];
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
			[['banner_id', 'publish', 'permanent'], 'integer'],
			[['views', 'clicks'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'banner_id' => Yii::t('app', 'Banner'),
			'publish' => Yii::t('app', 'Publish'),
			'permanent' => Yii::t('app', 'Permanent'),
			'views' => Yii::t('app', 'Views'),
			'clicks' => Yii::t('app', 'Clicks'),
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
		$this->templateColumns['banner_id'] = [
			'attribute' => 'banner_id',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_id;
			},
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				return $model->publish;
			},
		];
		$this->templateColumns['permanent'] = [
			'attribute' => 'permanent',
			'value' => function($model, $key, $index, $column) {
				return $model->permanent;
			},
		];
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				return $model->views;
			},
		];
		$this->templateColumns['clicks'] = [
			'attribute' => 'clicks',
			'value' => function($model, $key, $index, $column) {
				return $model->clicks;
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
				->where(['banner_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
