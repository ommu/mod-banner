<?php
/**
 * BannerSetting
 * version: 0.0.1
 *
 * This is the model class for table "ommu_banner_setting".
 *
 * The followings are the available columns in table "ommu_banner_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_keyword
 * @property string $meta_description
 * @property integer $banner_validation
 * @property integer $banner_resize
 * @property string $banner_file_type
 * @property string $modified_date
 * @property integer $modified_id

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 6 October 2017, 06:21 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\banner\models;

use Yii;
use yii\helpers\Url;
use app\coremodules\user\models\Users;
use app\components\Utility;

class BannerSetting extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Variable Search
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_banner_setting';
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
			[['license', 'permission', 'meta_keyword', 'meta_description', 'banner_validation', 'banner_resize', 'banner_file_type'], 'required'],
			[['permission', 'banner_validation', 'banner_resize', 'modified_id'], 'integer'],
			[['meta_keyword', 'meta_description', 'banner_file_type'], 'string'],
			[['modified_date'], 'safe'],
			[['license'], 'string', 'max' => 32],
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'banner_validation' => Yii::t('app', 'Banner Validation'),
			'banner_resize' => Yii::t('app', 'Banner Resize'),
			'banner_file_type' => Yii::t('app', 'Banner File Type'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modified_search' => Yii::t('app', 'Modified'),
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
		];
		$this->templateColumns['license'] = 'license';
		$this->templateColumns['meta_keyword'] = 'meta_keyword';
		$this->templateColumns['meta_description'] = 'meta_description';
		$this->templateColumns['banner_file_type'] = 'banner_file_type';
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'modified_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-';
			},
			'format'	=> 'html',
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return $model->permission;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['banner_validation'] = [
			'attribute' => 'banner_validation',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_validation;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['banner_resize'] = [
			'attribute' => 'banner_resize',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_resize;
			},
			'contentOptions' => ['class'=>'center'],
		];
	}

	/**
	 * get Module License
	 */
	public static function getLicense($source='1234567890', $length=16, $char=4)
	{
		$mod = $length%$char;
		if($mod == 0)
			$sep = ($length/$char);
		else
			$sep = (int)($length/$char)+1;
		
		$sourceLength = strlen($source);
		$random = '';
		for ($i = 0; $i < $length; $i++)
			$random .= $source[rand(0, $sourceLength - 1)];
		
		$license = '';
		for ($i = 0; $i < $sep; $i++) {
			if($i != $sep-1)
				$license .= substr($random,($i*$char),$char).'-';
			else
				$license .= substr($random,($i*$char),$char);
		}

		return $license;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord)
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert) 
	{
		if(parent::beforeSave($insert)) {
			$this->banner_file_type = serialize(Utility::formatFileType($this->banner_file_type));
		}
		return true;	
	}
}
