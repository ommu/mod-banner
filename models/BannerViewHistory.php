<?php
/**
 * BannerViewHistory
 * version: 0.0.1
 *
 * This is the model class for table "ommu_banner_view_history".
 *
 * The followings are the available columns in table "ommu_banner_view_history":
 * @property integer $id
 * @property integer $view_id
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property BannerViews $view

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 6 October 2017, 13:17 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\banner\models;

use Yii;
use yii\helpers\Url;

class BannerViewHistory extends \app\components\ActiveRecord
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
		return 'ommu_banner_view_history';
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
			[['view_id', 'view_ip'], 'required'],
			[['view_id'], 'integer'],
			[['view_date'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['view_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerViews::className(), 'targetAttribute' => ['view_id' => 'view_id']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(BannerViews::className(), ['view_id' => 'view_id']);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'view_id' => Yii::t('app', 'View'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View Ip'),
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
		if(!isset($_GET['view'])) {
			$this->templateColumns['category_search'] = [
				'attribute' => 'category_search',
				'filter' => BannerCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return $model->view->banner->category->title->message;
				},
			];
			$this->templateColumns['banner_search'] = [
				'attribute' => 'banner_search',
				'value' => function($model, $key, $index, $column) {
					return $model->view->banner->title;
				},
			];
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return $model->view->user->displayname ? $model->view->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'view_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->view_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->view_date, 'datetime') : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['view_ip'] = 'view_ip';
	}

}
