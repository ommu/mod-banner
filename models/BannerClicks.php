<?php
/**
 * BannerClicks
 * 
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 6 October 2017, 13:04 WIB
 * @modified date 30 April 2018, 12:39 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_banner_clicks".
 *
 * The followings are the available columns in table "ommu_banner_clicks":
 * @property integer $click_id
 * @property integer $banner_id
 * @property integer $user_id
 * @property integer $clicks
 * @property string $click_date
 * @property string $click_ip
 *
 * The followings are the available model relations:
 * @property BannerClickHistory[] $histories
 * @property Banners $banner
 * @property Users $user
 *
 */

namespace app\modules\banner\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\coremodules\user\models\Users;

class BannerClicks extends \app\components\ActiveRecord
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
		return 'ommu_banner_clicks';
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
			[['banner_id'], 'required'],
			[['banner_id', 'user_id', 'clicks'], 'integer'],
			[['user_id', 'click_date', 'click_ip'], 'safe'],
			[['click_ip'], 'string', 'max' => 20],
			[['banner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Banners::className(), 'targetAttribute' => ['banner_id' => 'banner_id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'click_id' => Yii::t('app', 'Click'),
			'banner_id' => Yii::t('app', 'Banner'),
			'user_id' => Yii::t('app', 'User'),
			'clicks' => Yii::t('app', 'Clicks'),
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
	public function getHistories()
	{
		return $this->hasMany(BannerClickHistory::className(), ['click_id' => 'click_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBanner()
	{
		return $this->hasOne(Banners::className(), ['banner_id' => 'banner_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\banner\models\query\BannerClicksQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\modules\banner\models\query\BannerClicksQuery(get_called_class());
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
		if(!Yii::$app->request->get('category') && !Yii::$app->request->get('banner')) {
			$this->templateColumns['category_search'] = [
				'attribute' => 'category_search',
				'filter' => BannerCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->banner->category) ? $model->banner->category->title->message : '-';
				},
			];
		}
		if(!Yii::$app->request->get('banner')) {
			$this->templateColumns['banner_search'] = [
				'attribute' => 'banner_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->banner) ? $model->banner->title : '-';
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['click_date'] = [
			'attribute' => 'click_date',
			'filter' => Html::input('date', 'click_date', Yii::$app->request->get('click_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->click_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->click_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['click_ip'] = [
			'attribute' => 'click_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->click_ip;
			},
		];
		$this->templateColumns['clicks'] = [
			'attribute' => 'clicks',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['history-click/index', 'click'=>$model->primaryKey]);
				return Html::a($model->clicks ? $model->clicks : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
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
				->where(['click_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	public function insertCLick($banner_id)
	{
		$user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
		
		$findClick = self::find()
			->select(['click_id','banner_id','user_id','clicks'])
			->where(['banner_id' => $banner_id]);
		if($user_id != null)
			$findClick->andWhere(['user_id' => $user_id]);
		else
			$findClick->andWhere(['is', 'user_id', null]);
		$findClick = $findClick->one();
			
		if($findClick !== null)
			$findClick->updateAttributes(['clicks'=>$findClick->clicks+1, 'click_ip'=>$_SERVER['REMOTE_ADDR']]);

		else {
			$click = new BannerClicks();
			$click->banner_id = $banner_id;
			$click->save();
		}
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

			$this->click_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
