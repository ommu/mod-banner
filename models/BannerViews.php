<?php
/**
 * BannerViews
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 13:14 WIB
 * @modified date 12 February 2019, 22:53 WIB
 * @link https://github.com/ommu/mod-banner
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
 * @property Users $user
 * @property Banners $banner
 *
 */

namespace ommu\banner\models;

use Yii;
use yii\helpers\Html;
use ommu\users\models\Users;

class BannerViews extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $categoryId;
	public $bannerTitle;
	public $userDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_banner_views';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['banner_id'], 'required'],
			[['banner_id', 'user_id', 'views'], 'integer'],
			[['view_ip'], 'string', 'max' => 20],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
			[['banner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Banners::className(), 'targetAttribute' => ['banner_id' => 'banner_id']],
		];
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
			'view_ip' => Yii::t('app', 'View IP'),
			'histories' => Yii::t('app', 'Histories'),
			'categoryId' => Yii::t('app', 'Category'),
			'bannerTitle' => Yii::t('app', 'Banner'),
			'userDisplayname' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories($count=false)
	{
		if($count == false)
			return $this->hasMany(BannerViewHistory::className(), ['view_id' => 'view_id']);

		$model = BannerViewHistory::find()
			->where(['view_id' => $this->view_id]);
		$histories = $model->count();

		return $histories ? $histories : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBanner()
	{
		return $this->hasOne(Banners::className(), ['banner_id' => 'banner_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\query\BannerViews the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\banner\models\query\BannerViews(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('banner')) {
			$this->templateColumns['categoryId'] = [
				'attribute' => 'categoryId',
				'value' => function($model, $key, $index, $column) {
					return isset($model->banner->category) ? $model->banner->category->title->message : '-';
					// return $model->categoryId;
				},
				'filter' => BannerCategory::getCategory(),
			];
			$this->templateColumns['bannerTitle'] = [
				'attribute' => 'bannerTitle',
				'value' => function($model, $key, $index, $column) {
					return isset($model->banner) ? $model->banner->title : '-';
					// return $model->bannerTitle;
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['userDisplayname'] = [
				'attribute' => 'userDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
					// return $model->userDisplayname;
				},
			];
		}
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->view_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'view_date'),
		];
		$this->templateColumns['view_ip'] = [
			'attribute' => 'view_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->view_ip;
			},
		];
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				$views = $model->views;
				return Html::a($views, ['history/view-detail/manage', 'view'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} histories', ['count'=>$views]), 'data-pjax' => 0]);
			},
			'filter' => false,
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
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['view_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function insertView($banner_id, $user_id=null)
	{
		if($user_id == null)
			$user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
		
		$findView = self::find()
			->select(['view_id','banner_id','user_id','views'])
			->where(['banner_id' => $banner_id]);
		if($user_id != null)
			$findView->andWhere(['user_id' => $user_id]);
		else
			$findView->andWhere(['is', 'user_id', null]);
		$findView = $findView->one();
			
		if($findView !== null)
			$findView->updateAttributes(['views'=>$findView->views+1, 'view_ip'=>$_SERVER['REMOTE_ADDR']]);

		else {
			$view = new BannerViews();
			$view->banner_id = $banner_id;
			$view->user_id = $user_id;
			$view->save();
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// this->categoryId = isset($this->banner->category) ? $this->banner->category->title->message : '-';
		// $this->bannerTitle = isset($this->banner) ? $this->banner->title : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->user_id == null)
					$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
			$this->view_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
