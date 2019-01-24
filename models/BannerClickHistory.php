<?php
/**
 * BannerClickHistory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 13:09 WIB
 * @modified date 30 April 2018, 12:41 WIB
 * @link https://github.com/ommu/mod-banner
 *
 * This is the model class for table "ommu_banner_click_history".
 *
 * The followings are the available columns in table "ommu_banner_click_history":
 * @property integer $id
 * @property integer $click_id
 * @property string $click_date
 * @property string $click_ip
 *
 * The followings are the available model relations:
 * @property BannerClicks $click
 *
 */

namespace ommu\banner\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class BannerClickHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Variable Search
	public $category_search;
	public $banner_search;
	public $user_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_banner_click_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['click_id', 'click_ip'], 'required'],
			[['click_id'], 'integer'],
			[['click_date'], 'safe'],
			[['click_ip'], 'string', 'max' => 20],
			[['click_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerClicks::className(), 'targetAttribute' => ['click_id' => 'click_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'click_id' => Yii::t('app', 'Click'),
			'click_date' => Yii::t('app', 'Click Date'),
			'click_ip' => Yii::t('app', 'Click Ip'),
			'category_search' => Yii::t('app', 'Category'),
			'banner_search' => Yii::t('app', 'Banner'),
			'user_search' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getClick()
	{
		return $this->hasOne(BannerClicks::className(), ['click_id' => 'click_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\query\BannerClickHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\banner\models\query\BannerClickHistory(get_called_class());
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
		if(!Yii::$app->request->get('click')) {
			$this->templateColumns['category_search'] = [
				'attribute' => 'category_search',
				'filter' => BannerCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->click->banner->category) ? $model->click->banner->category->title->message : '-';
				},
			];
			$this->templateColumns['banner_search'] = [
				'attribute' => 'banner_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->click->banner) ? $model->click->banner->title : '-';
				},
			];
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->click->user) ? $model->click->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['click_date'] = [
			'attribute' => 'click_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->click_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'click_date'),
		];
		$this->templateColumns['click_ip'] = [
			'attribute' => 'click_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->click_ip;
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
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->click_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			if($insert)
				$this->click_date = Yii::$app->formatter->asDate($this->click_date, 'php:Y-m-d');
		}
		return true;
	}
}
