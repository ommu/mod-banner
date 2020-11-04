<?php
/**
 * BannerViewHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 6 October 2017, 13:17 WIB
 * @modified date 19 January 2019, 06:56 WIB
 * @link https://github.com/ommu/mod-banner
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
 *
 */

namespace ommu\banner\models;

use Yii;

class BannerViewHistory extends \app\components\ActiveRecord
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
		return 'ommu_banner_view_history';
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'view_id' => Yii::t('app', 'View'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View IP'),
			'bannerTitle' => Yii::t('app', 'Banner'),
			'userDisplayname' => Yii::t('app', 'User'),
			'categoryId' => Yii::t('app', 'Category'),
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
	 * {@inheritdoc}
	 * @return \ommu\banner\models\query\BannerViewHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\banner\models\query\BannerViewHistory(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['categoryId'] = [
			'attribute' => 'categoryId',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view->banner->category) ? $model->view->banner->category->title->message : '-';
				// return $model->categoryId;
			},
			'filter' => BannerCategory::getCategory(),
			'visible' => !Yii::$app->request->get('view') && !Yii::$app->request->get('banner') ? true : false,
		];
		$this->templateColumns['bannerTitle'] = [
			'attribute' => 'bannerTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view->banner) ? $model->view->banner->title : '-';
				// return $model->bannerTitle;
			},
			'visible' => !Yii::$app->request->get('view') && !Yii::$app->request->get('banner') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view->user) ? $model->view->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('view') ? true : false,
		];
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
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
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

		// $this->bannerTitle = isset($this->view->banner) ? $this->view->banner->title : '-';
		// $this->userDisplayname = isset($this->view->user) ? $this->view->user->displayname : '-';
		// $this->categoryId = isset($this->view->banner->category) ? $this->view->banner->category->title->message : '-';
	}
}
