<?php
/**
 * BannerClickHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 13:09 WIB
 * @modified date 19 January 2019, 06:57 WIB
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

class BannerClickHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $bannerTitle;
	public $userDisplayname;
	public $categoryId;
	public $bannerId;

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
			'click_ip' => Yii::t('app', 'Click IP'),
			'bannerTitle' => Yii::t('app', 'Banner'),
			'userDisplayname' => Yii::t('app', 'User'),
			'categoryId' => Yii::t('app', 'Category'),
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

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('click')) {
			if(!Yii::$app->request->get('banner')) {
				$this->templateColumns['categoryId'] = [
					'attribute' => 'categoryId',
					'value' => function($model, $key, $index, $column) {
						return isset($model->click->banner->category) ? $model->click->banner->category->title->message : '-';
						// return $model->categoryId;
					},
					'filter' => BannerCategory::getCategory(),
				];
				$this->templateColumns['bannerTitle'] = [
					'attribute' => 'bannerTitle',
					'value' => function($model, $key, $index, $column) {
						return isset($model->click->banner) ? $model->click->banner->title : '-';
						// return $model->bannerTitle;
					},
				];
			}
			$this->templateColumns['userDisplayname'] = [
				'attribute' => 'userDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->click->user) ? $model->click->user->displayname : '-';
					// return $model->userDisplayname;
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
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->bannerTitle = isset($this->click->banner) ? $this->click->banner->title : '-';
		// $this->userDisplayname = isset($this->click->user) ? $this->click->user->displayname : '-';
		// $this->categoryId = isset($this->click->banner->category) ? $this->click->banner->category->title->message : '-';
	}
}
