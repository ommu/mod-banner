<?php
/**
 * LinkTree
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 19:22 WIB
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
 * @property BannerCategory $category
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

class LinkTree extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['modified_date', 'modifiedDisplayname', 'updated_date', 'slug'];

	public $categoryName;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $click;
	public $view;
	public $link;

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
			[['cat_id', 'title', 'url', 'creation_id'], 'required'],
			[['publish', 'cat_id', 'creation_id', 'modified_id'], 'integer'],
			[['url'], 'url'],
			[['modified_date'], 'safe'],
			[['title', 'slug'], 'string', 'max' => 64],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'banner_id' => Yii::t('app', 'Banner'),
			'publish' => Yii::t('app', 'Publish'),
			'cat_id' => Yii::t('app', 'Category'),
			'title' => Yii::t('app', 'Title'),
			'url' => Yii::t('app', 'Link'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'slug' => Yii::t('app', 'Slug'),
			'categoryName' => Yii::t('app', 'Category'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'click' => Yii::t('app', 'Click'),
			'view' => Yii::t('app', 'View'),
			'link' => Yii::t('app', 'Link'),
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
		return $this->hasOne(BannerCategory::className(), ['cat_id' => 'cat_id']);
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
	 * @return \ommu\banner\models\query\LinkTree the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\banner\models\query\LinkTree(get_called_class());
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
		$this->templateColumns['title'] = [
			'attribute' => 'title',
			'value' => function($model, $key, $index, $column) {
				return $model->title;
			},
			'visible' => Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['url'] = [
			'attribute' => 'url',
			'value' => function($model, $key, $index, $column) {
				return $model->url;
			},
			'visible' => Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
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
		$this->templateColumns['link'] = [
			'attribute' => 'link',
			'value' => function($model, $key, $index, $column) {
				$links = $model->link;
				return Html::a($links, ['manage', 'creation' => $model->creation_id], ['title' => Yii::t('app', '{count} links', ['count' => $links]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['click'] = [
			'attribute' => 'click',
			'value' => function($model, $key, $index, $column) {
				$clicks = $model->getClicks(true);
				return Html::a($clicks, ['o/click/manage', 'banner' => $model->primaryKey, 'linktree' => true], ['title' => Yii::t('app', '{count} clicks', ['count' => $clicks]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['view'] = [
			'attribute' => 'view',
			'value' => function($model, $key, $index, $column) {
				$views = $model->getViews(true);
				return Html::a($views, ['o/view/manage', 'banner' => $model->primaryKey, 'linktree' => true], ['title' => Yii::t('app', '{count} views', ['count' => $views]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => Yii::$app->request->get('creation') ? true : false,
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
			'visible' => !Yii::$app->request->get('trash') && Yii::$app->request->get('creation') ? true : false,
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
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		$category = BannerCategory::find()
			->select(['cat_id'])
			->andWhere(['publish' => 1])
			->andWhere(['type' => 'linktree'])
            ->one();

        if (parent::beforeValidate()) {
            if ($category != null) {
                $this->cat_id = $category->cat_id;
            }
            $this->is_banner = 0;

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
}
