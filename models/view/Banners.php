<?php
/**
 * Banners
 * version: 0.0.1
 *
 * This is the model class for table "_banners".
 *
 * The followings are the available columns in table "_banners":
 * @property string $banner_id
 * @property string $clicks
 * @property string $views

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 11 October 2017, 10:31 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\banner\models\view;

use Yii;
use yii\helpers\Url;
use app\libraries\grid\GridView;

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
			[['banner_id', 'publish'], 'integer'],
			[['clicks', 'views'], 'number'],
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
			'clicks' => Yii::t('app', 'Clicks'),
			'views' => Yii::t('app', 'Views'),
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
		$this->templateColumns['banner_id'] = 'banner_id';
		$this->templateColumns['clicks'] = 'clicks';
		$this->templateColumns['views'] = 'views';
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => GridView::getFilterYesNo(),
				'value' => function($model, $key, $index, $column) {
					return $model->publish == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
				},
				'contentOptions' => ['class'=>'center'],
				'format'	=> 'raw',
			];
		}
	}
}
