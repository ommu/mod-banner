<?php
/**
 * LinkRotatorItem
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 August 2021, 19:57 WIB
 * @link https://github.com/ommu/mod-banner
 *
 * This is the model class for table "ommu_banners".
 *
 * The followings are the available columns in table "ommu_banners":
 * @property integer $banner_id
 * @property integer $publish
 * @property integer $cat_id
 * @property integer $is_banner
 * @property string $title
 * @property string $url
 * @property string $banner_desc
 * @property string $published_date
 * @property string $expired_date
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property BannerClicks[] $clicks
 * @property BannerViews[] $views
 * @property LinkRotators $category
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\banner\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use yii\web\UploadedFile;
use yii\behaviors\SluggableBehavior;
use thamtech\uuid\helpers\UuidHelper;
use app\models\Users;

class LinkRotatorItem extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['url', 'banner_desc', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date', 'slug'];

	public $permanent;
	public $categoryName;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $click;
	public $view;

	const SCENARIO_IS_NOT_PERMANENT = 'isNotPermanent';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_banners';
	}

	/**
	 * behaviors model class.
	 */
	public function behaviors() {
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'title',
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
			[['cat_id', 'title', 'url', 'banner_desc', 'published_date', 'expired_date', 'permanent'], 'required'],
			[['publish', 'cat_id', 'creation_id', 'modified_id', 'permanent'], 'integer'],
			[['url'], 'url'],
			[['banner_desc'], 'string'],
			[['published_date', 'expired_date'], 'safe'],
			[['title', 'slug'], 'string', 'max' => 64],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => LinkRotators::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
			[['expired_date'], 'compare', 'compareAttribute' => 'published_date', 'operator' => '>=', 'on' => self::SCENARIO_IS_NOT_PERMANENT],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_IS_NOT_PERMANENT] = ['cat_id', 'title', 'url', 'banner_desc', 'published_date', 'expired_date', 'permanent'];
		return $scenarios;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'banner_id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'cat_id' => Yii::t('app', 'Rotator'),
			'title' => Yii::t('app', 'Title'),
			'url' => Yii::t('app', 'Url'),
			'banner_desc' => Yii::t('app', 'Description'),
			'published_date' => Yii::t('app', 'Published Date'),
			'expired_date' => Yii::t('app', 'Expired Date'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'slug' => Yii::t('app', 'Slug'),
			'categoryName' => Yii::t('app', 'Rotator'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'click' => Yii::t('app', 'Click'),
			'view' => Yii::t('app', 'View'),
			'permanent' => Yii::t('app', 'Permanent'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getClicks($count=false)
	{
        if ($count == false) {
            return $this->hasMany(BannerClicks::className(), ['banner_id' => 'banner_id']);
        }

		$model = BannerClicks::find()
            ->alias('t')
            ->where(['banner_id' => $this->banner_id]);
		$clicks = $model->count();

		return $clicks ? $clicks : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViews($count=false)
	{
        if ($count == false) {
            return $this->hasMany(BannerViews::className(), ['banner_id' => 'banner_id']);
        }

		$model = BannerViews::find()
            ->alias('t')
            ->where(['banner_id' => $this->banner_id]);
		$views = $model->count();

		return $views ? $views : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(LinkRotators::className(), ['cat_id' => 'cat_id']);
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
	 * @return \ommu\banner\models\query\LinkRotatorItem the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\banner\models\query\LinkRotatorItem(get_called_class());
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
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['cat_id'] = [
			'attribute' => 'cat_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->category) ? $model->category->title->message : '-';
				// return $model->categoryName;
			},
			'filter' => LinkRotators::getRotator(),
			'visible' => !Yii::$app->request->get('category') ? true : false,
		];
		$this->templateColumns['title'] = [
			'attribute' => 'title',
			'value' => function($model, $key, $index, $column) {
				return $model->title;
			},
		];
		$this->templateColumns['url'] = [
			'attribute' => 'url',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asUrl($model->url);
			},
			'format' => 'html',
		];
		$this->templateColumns['banner_desc'] = [
			'attribute' => 'banner_desc',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_desc;
			},
		];
		$this->templateColumns['published_date'] = [
			'attribute' => 'published_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDate($model->published_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'published_date'),
		];
		$this->templateColumns['expired_date'] = [
			'attribute' => 'expired_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDate($model->expired_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'expired_date'),
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
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
		$this->templateColumns['click'] = [
			'attribute' => 'click',
			'value' => function($model, $key, $index, $column) {
				$clicks = $model->getClicks(true);
				return Html::a($clicks, ['o/click/manage', 'banner' => $model->primaryKey], ['title' => Yii::t('app', '{count} clicks', ['count' => $clicks]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['view'] = [
			'attribute' => 'view',
			'value' => function($model, $key, $index, $column) {
				$views = $model->getViews(true);
				return Html::a($views, ['o/view/manage', 'banner' => $model->primaryKey], ['title' => Yii::t('app', '{count} views', ['count' => $views]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['permanent'] = [
			'attribute' => 'permanent',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->permanent);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
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
            $model = $model->where(['banner_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true) 
	{
		return ($returnAlias ? Yii::getAlias('@public/main') : 'main');
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->categoryName = isset($this->category) ? $this->category->title->message : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		$this->click = $this->getClicks(true) ? 1 : 0;
		$this->view = $this->getViews(true) ? 1 : 0;
		$this->permanent = 0;
        if (Yii::$app->formatter->asDate($this->expired_date, 'php:Y-m-d') == '-') {
            $this->permanent = 1;
        }
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->permanent == 0) {
                $this->scenario = self::SCENARIO_IS_NOT_PERMANENT;
            }

            $this->is_banner = 0;

            if ($this->permanent) {
                $this->expired_date = '0000-00-00';
            }

            if ($this->isNewRecord) {
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
        if (parent::beforeSave($insert)) {
            $this->published_date = Yii::$app->formatter->asDate($this->published_date, 'php:Y-m-d');
            $this->expired_date = Yii::$app->formatter->asDate($this->expired_date, 'php:Y-m-d');
        }
        return true;
	}
}
