<?php
/**
 * BannerCategory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 5 October 2017, 15:42 WIB
 * @modified date 19 January 2019, 06:55 WIB
 * @link https://github.com/ommu/mod-banner
 *
 * This is the model class for table "ommu_banner_category".
 *
 * The followings are the available columns in table "ommu_banner_category":
 * @property integer $cat_id
 * @property integer $publish
 * @property integer $name
 * @property integer $desc
 * @property string $cat_code
 * @property string $banner_size
 * @property integer $banner_limit
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property Banners[] $banners
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\banner\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\behaviors\SluggableBehavior;
use app\models\SourceMessage;
use ommu\users\models\Users;

class BannerCategory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['creation_date','creation_search','modified_date','modified_search','updated_date','slug','desc_i'];
	public $name_i;
	public $desc_i;

	// Search Variable
	public $creation_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_banner_category';
	}

	/**
	 * behaviors model class.
	 */
	public function behaviors() {
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'title.message',
				'immutable' => true,
				'ensureUnique' => true,
			],
		];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['name_i', 'desc_i', 'banner_size', 'banner_limit'], 'required'],
			[['publish', 'name', 'desc', 'banner_limit', 'creation_id', 'modified_id'], 'integer'],
			[['name_i', 'desc_i'], 'string'],
			//[['banner_size'], 'serialize'],
			[['name_i'], 'string', 'max' => 64],
			[['desc_i'], 'string', 'max' => 128],
			[['cat_code', 'slug'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Category'),
			'publish' => Yii::t('app', 'Publish'),
			'name' => Yii::t('app', 'Category'),
			'desc' => Yii::t('app', 'Description'),
			'cat_code' => Yii::t('app', 'Code'),
			'banner_size' => Yii::t('app', 'Size'),
			'banner_size[i]' => Yii::t('app', 'Size'),
			'banner_size[width]' => Yii::t('app', 'Width'),
			'banner_size[height]' => Yii::t('app', 'Height'),
			'banner_limit' => Yii::t('app', 'Limit'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'slug' => Yii::t('app', 'Slug'),
			'name_i' => Yii::t('app', 'Category'),
			'desc_i' => Yii::t('app', 'Description'),
			'banners' => Yii::t('app', 'Banners'),
			'permanent' => Yii::t('app', 'Permanent'),
			'pending' => Yii::t('app', 'Pending'),
			'expired' => Yii::t('app', 'Expired'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBannersByType($cat_id, $type='published')
	{
		$model = Banners::find()
			->where(['cat_id' => $cat_id]);
		if($type == 'published')
			$model->published();
		elseif($type == 'permanent')
			$model->permanent();
		elseif($type == 'pending')
			$model->pending();
		elseif($type == 'expired')
			$model->expired();

		return $model->count();
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBanners($count=true, $publish=null)
	{
		if($count == true) {
			if($publish === null)
				return self::getBannersByType($this->cat_id, 'published');

			$model = Banners::find()
				->where(['cat_id' => $this->cat_id]);
			if($publish == 0)
				$model->unpublish();
			elseif($publish == 1)
				$model->published();
			elseif($publish == 2)
				$model->deleted();

			return $model->count();
		}

		return $this->hasMany(Banners::className(), ['cat_id' => 'cat_id'])
			->andOnCondition([sprintf('%s.publish', Banners::tableName()) => $publish]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPermanent($count=true)
	{
		if($count == true)
			return self::getBannersByType($this->cat_id, 'permanent');

		return $this->hasMany(Banners::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPending($count=true)
	{
		if($count == true)
			return self::getBannersByType($this->cat_id, 'pending');

		return $this->hasMany(Banners::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExpired($count=true)
	{
		if($count == true)
			return self::getBannersByType($this->cat_id, 'expired');

		return $this->hasMany(Banners::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'desc']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
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
	 * @return \ommu\banner\models\query\BannerCategory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\banner\models\query\BannerCategory(get_called_class());
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
		$this->templateColumns['name_i'] = [
			'attribute' => 'name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->name_i;
			},
		];
		$this->templateColumns['desc_i'] = [
			'attribute' => 'desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->desc_i;
			},
		];
		$this->templateColumns['cat_code'] = [
			'attribute' => 'cat_code',
			'value' => function($model, $key, $index, $column) {
				return $model->cat_code;
			},
		];
		$this->templateColumns['banner_size'] = [
			'attribute' => 'banner_size',
			'value' => function($model, $key, $index, $column) {
				return self::getSize($model->banner_size);
			},
		];
		$this->templateColumns['banner_limit'] = [
			'attribute' => 'banner_limit',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return $model->banner_limit;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creation_search'] = [
				'attribute' => 'creation_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['slug'] = [
			'attribute' => 'slug',
			'value' => function($model, $key, $index, $column) {
				return $model->slug;
			},
		];
		$this->templateColumns['banners'] = [
			'attribute' => 'banners',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->banners, ['admin/manage', 'category'=>$model->primaryKey, 'expired'=>'publish']);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['permanent'] = [
			'attribute' => 'permanent',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->permanent, ['admin/manage', 'category'=>$model->primaryKey, 'expired'=>'permanent']);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['pending'] = [
			'attribute' => 'pending',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->pending, ['admin/manage', 'category'=>$model->primaryKey, 'expired'=>'pending']);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['expired'] = [
			'attribute' => 'expired',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->expired, ['admin/manage', 'category'=>$model->primaryKey, 'expired'=>'expired']);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['category/publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish, 'Enable,Disable');
				},
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['cat_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getCategory
	 */
	public static function getCategory($publish=null, $array=true) 
	{
		$model = self::find()->alias('t');
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.name=title.id');
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('title.message ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'cat_id', 'name_i');

		return $model;
	}

	/**
	 * getSize
	 */
	public static function getSize($banner_size)
	{
		if(empty($banner_size))
			return '-';

		return $banner_size['width'].'x'.$banner_size['height'];
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->name_i = isset($this->title) ? $this->title->message : '';
		$this->desc_i = isset($this->description) ? $this->description->message : '';
		$this->banner_size = unserialize($this->banner_size);
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->banner_size['width'] == '' && $this->banner_size['height'] == '')
				$this->addError('banner_size', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('banner_size')]));
			else {
				if($this->banner_size['width'] == '')
					$this->addError('banner_size', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('banner_size[width]')]));
				else if($this->banner_size['height'] == '')
					$this->addError('banner_size', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('banner_size[height]')]));
			}

			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}

			$this->cat_code = $this->urlTitle($this->name_i);
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);

		$location = $this->urlTitle($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($insert || (!$insert && !$this->name)) {
				$name = new SourceMessage();
				$name->location = $location.'_title';
				$name->message = $this->name_i;
				if($name->save())
					$this->name = $name->id;

				$this->slug = $this->urlTitle($this->name_i);

			} else {
				$name = SourceMessage::findOne($this->name);
				$name->message = $this->name_i;
				$name->save();
			}

			if($insert || (!$insert && !$this->desc)) {
				$desc = new SourceMessage();
				$desc->location = $location.'_description';
				$desc->message = $this->desc_i;
				if($desc->save())
					$this->desc = $desc->id;

			} else {
				$desc = SourceMessage::findOne($this->desc);
				$desc->message = $this->desc_i;
				$desc->save();
			}

			$this->banner_size = serialize($this->banner_size);
		}
		return true;
	}
}
