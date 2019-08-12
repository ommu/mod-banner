<?php
/**
 * BannerSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 6 October 2017, 06:21 WIB
 * @modified date 19 January 2019, 06:54 WIB
 * @link https://github.com/ommu/mod-banner
 *
 * This is the model class for table "ommu_banner_setting".
 *
 * The followings are the available columns in table "ommu_banner_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_description
 * @property string $meta_keyword
 * @property integer $banner_validation
 * @property integer $banner_resize
 * @property string $banner_file_type
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Users $modified
 *
 */

namespace ommu\banner\models;

use Yii;
use yii\helpers\Html;
use ommu\users\models\Users;

class BannerSetting extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = [];

	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_banner_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['license', 'permission', 'meta_description', 'meta_keyword', 'banner_validation', 'banner_resize', 'banner_file_type'], 'required'],
			[['permission', 'banner_validation', 'banner_resize', 'modified_id'], 'integer'],
			[['meta_keyword', 'meta_description', 'banner_file_type'], 'string'],
			//[['banner_file_type'], 'serialize'],
			[['license'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'banner_validation' => Yii::t('app', 'Banner Validation'),
			'banner_resize' => Yii::t('app', 'Banner Resize'),
			'banner_file_type' => Yii::t('app', 'Banner File Type'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\query\BannerSetting the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\banner\models\query\BannerSetting(get_called_class());
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
		$this->templateColumns['license'] = [
			'attribute' => 'license',
			'value' => function($model, $key, $index, $column) {
				return $model->license;
			},
		];
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return self::getPermission($model->permission);
			},
		];
		$this->templateColumns['meta_description'] = [
			'attribute' => 'meta_description',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_description;
			},
		];
		$this->templateColumns['meta_keyword'] = [
			'attribute' => 'meta_keyword',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_keyword;
			},
		];
		$this->templateColumns['banner_file_type'] = [
			'attribute' => 'banner_file_type',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_file_type;
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
				},
			];
		}
		$this->templateColumns['banner_validation'] = [
			'attribute' => 'banner_validation',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->banner_validation);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['banner_resize'] = [
			'attribute' => 'banner_resize',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->banner_resize);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => 1])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne(1);
			return $model;
		}
	}

	/**
	 * function getPermission
	 */
	public static function getPermission($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, the public can view "module name" unless they are made private.'),
			0 => Yii::t('app', 'No, the public cannot view "module name".'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getBannerValidation
	 */
	public static function getBannerValidation($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, validation banner size before upload.'),
			0 => Yii::t('app', 'No, not validation banner size before upload.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getBannerResize
	 */
	public static function getBannerResize($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, resize banner after upload.'),
			0 => Yii::t('app', 'No, not resize banner after upload.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$banner_file_type = unserialize($this->banner_file_type);
		if(!empty($banner_file_type))
			$this->banner_file_type = $this->formatFileType($banner_file_type, false);
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord) {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			$this->banner_file_type = serialize($this->formatFileType($this->banner_file_type));
		}
		return true;
	}
}
