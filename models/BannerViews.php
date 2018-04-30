<?php
/**
 * BannerViews
 * version: 0.0.1
 *
 * This is the model class for table "ommu_banner_views".
 *
 * The followings are the available columns in table "ommu_banner_views":
 * @property integer $view_id
 * @property integer $banner_id
 * @property integer $user_id
 * @property integer $views
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property BannerViewHistory[] $histories
 * @property Banners $banner

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 6 October 2017, 13:14 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\banner\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\html;
use app\coremodules\user\models\Users;

class BannerViews extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['view_ip'];

	// Variable Search
	public $category_search;
	public $banner_search;
	public $user_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_banner_views';
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
			[['banner_id', 'user_id'], 'required'],
			[['banner_id', 'user_id', 'views'], 'integer'],
			[['view_date', 'view_ip'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['banner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Banners::className(), 'targetAttribute' => ['banner_id' => 'banner_id']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(BannerViewHistory::className(), ['view_id' => 'view_id']);
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'view_id' => Yii::t('app', 'View'),
			'banner_id' => Yii::t('app', 'Banner'),
			'user_id' => Yii::t('app', 'User'),
			'views' => Yii::t('app', 'Views'),
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
		if(!isset($_GET['banner'])) {
			$this->templateColumns['category_search'] = [
				'attribute' => 'category_search',
				'filter' => BannerCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return $model->banner->category->title->message;
				},
			];
			$this->templateColumns['banner_search'] = [
				'attribute' => 'banner_search',
				'value' => function($model, $key, $index, $column) {
					return $model->banner->title;
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
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['view-history/index', 'view' => $model->primaryKey]);
				return Html::a($model->views ? $model->views : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format'	=> 'raw',
		];
	}

	public function insertView($banner_id)
	{
		$user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
		$view = BannerViews::find()->select(['banner_id', 'views'])->where(['banner_id' => $banner_id, 'user_id' => $user_id])->one();

		if($view == null) {
			$view = new BannerViews;
			$view->banner_id = $banner_id;
		} else
			$view->views = $view->views+1;
			
		$view->save();
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';

			$this->view_ip = Yii::$app->request->userIP;
		}
		return true;
	}

}
