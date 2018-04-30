<?php
/**
 * BannerClickHistory
 * version: 0.0.1
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

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 6 October 2017, 13:09 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\banner\models;

use Yii;
use yii\helpers\Url;

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
			[['click_id', 'click_ip'], 'required'],
			[['click_id'], 'integer'],
			[['click_date'], 'safe'],
			[['click_ip'], 'string', 'max' => 20],
			[['click_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerClicks::className(), 'targetAttribute' => ['click_id' => 'click_id']],
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
		if(!isset($_GET['click'])) {
			$this->templateColumns['category_search'] = [
				'attribute' => 'category_search',
				'filter' => BannerCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return $model->click->banner->category->title->message;
				},
			];
			$this->templateColumns['banner_search'] = [
				'attribute' => 'banner_search',
				'value' => function($model, $key, $index, $column) {
					return $model->click->banner->title;
				},
			];
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return $model->click->user->displayname ? $model->click->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['click_date'] = [
			'attribute' => 'click_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'click_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->click_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->click_date, 'datetime') : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['click_ip'] = 'click_ip';
	}

}
