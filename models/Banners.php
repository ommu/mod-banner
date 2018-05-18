<?php
/**
 * Banners
 * 
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 5 October 2017, 16:51 WIB
 * @modified date 30 April 2018, 12:39 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_banners".
 *
 * The followings are the available columns in table "ommu_banners":
 * @property integer $banner_id
 * @property integer $publish
 * @property integer $cat_id
 * @property string $title
 * @property string $url
 * @property string $banner_filename
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
 * @property BannerCategory $category
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\banner\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\behaviors\SluggableBehavior;
use app\modules\user\models\Users;
use ommu\banner\models\view\Banners as BannersView;

class Banners extends \app\components\ActiveRecord
{
	use \ommu\traits\GridViewTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['url','banner_filename','banner_desc','creation_date','creation_search','modified_date','modified_search','updated_date','slug'];
	public $linked_i;
	public $permanent_i;
	public $old_banner_filename_i;

	// Variable Search
	public $category_search;
	public $creation_search;
	public $modified_search;
	public $permanent_search;
	public $view_search;
	public $click_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_banners';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
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
			[['cat_id', 'title', 'url', 'published_date', 'expired_date',
				'linked_i', 'permanent_i'], 'required'],
			[['publish', 'cat_id', 'creation_id', 'modified_id',
				'linked_i', 'permanent_i'], 'integer'],
			[['url', 'banner_filename', 'banner_desc', 'slug',
				'old_banner_filename_i'], 'string'],
			[['banner_filename', 'banner_desc', 'published_date', 'expired_date', 'creation_date', 'modified_date', 'updated_date',
				'old_banner_filename_i'], 'safe'],
			[['title'], 'string', 'max' => 64],
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
			'url' => Yii::t('app', 'URL'),
			'banner_filename' => Yii::t('app', 'Filename'),
			'banner_desc' => Yii::t('app', 'Description'),
			'published_date' => Yii::t('app', 'Published Date'),
			'expired_date' => Yii::t('app', 'Expired Date'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'slug' => Yii::t('app', 'Slug'),
			'linked_i' => Yii::t('app', 'Linked'),
			'permanent_i' => Yii::t('app', 'Permanent'),
			'old_banner_filename_i' => Yii::t('app', 'Old Filename'),
			'category_search' => Yii::t('app', 'Category'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
			'permanent_search' => Yii::t('attribute', 'Permanent'),
			'view_search' => Yii::t('app', 'Views'),
			'click_search' => Yii::t('app', 'Clicks'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getClicks()
	{
		return $this->hasMany(BannerClicks::className(), ['banner_id' => 'banner_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViews()
	{
		return $this->hasMany(BannerViews::className(), ['banner_id' => 'banner_id']);
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(BannersView::className(), ['banner_id' => 'banner_id']);
	}

	/**
	 * @inheritdoc
	 * @return \ommu\banner\models\query\BannersQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\banner\models\query\BannersQuery(get_called_class());
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
		if(!Yii::$app->request->get('category')) {
			$this->templateColumns['cat_id'] = [
				'attribute' => 'cat_id',
				'filter' => BannerCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->category) ? $model->category->title->message : '-';
				},
			];
		}
		$this->templateColumns['title'] = [
			'attribute' => 'title',
			'value' => function($model, $key, $index, $column) {
				return $model->title;
			},
		];
		$this->templateColumns['url'] = [
			'attribute' => 'url',
			'value' => function($model, $key, $index, $column) {
				return $model->url;
			},
		];
		$this->templateColumns['banner_filename'] = [
			'attribute' => 'banner_filename',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_filename;
			},
		];
		$this->templateColumns['banner_desc'] = [
			'attribute' => 'banner_desc',
			'value' => function($model, $key, $index, $column) {
				return $model->banner_desc;
			},
		];
		$this->templateColumns['published_date'] = [
			'attribute' => 'published_date',
			'filter' => Html::input('date', 'published_date', Yii::$app->request->get('published_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->published_date, ['0000-00-00','1970-01-01','0002-12-02','-0001-11-30']) ? Yii::$app->formatter->format($model->published_date, 'date') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['expired_date'] = [
			'attribute' => 'expired_date',
			'filter' => Html::input('date', 'expired_date', Yii::$app->request->get('expired_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->expired_date, ['0000-00-00','1970-01-01','0002-12-02','-0001-11-30']) ? Yii::$app->formatter->format($model->expired_date, 'date') : 'Permanent';
			},
			'format' => 'html',
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'filter' => Html::input('date', 'creation_date', Yii::$app->request->get('creation_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-';
			},
			'format' => 'html',
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
			'filter' => Html::input('date', 'modified_date', Yii::$app->request->get('modified_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-';
			},
			'format' => 'html',
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
			'filter' => Html::input('date', 'updated_date', Yii::$app->request->get('updated_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['slug'] = [
			'attribute' => 'slug',
			'value' => function($model, $key, $index, $column) {
				return $model->slug;
			},
		];
		$this->templateColumns['view_search'] = [
			'attribute' => 'view_search',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['views/index', 'banner'=>$model->primaryKey]);
				return Html::a($model->view->views ? $model->view->views : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['click_search'] = [
			'attribute' => 'click_search',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['clicks/index', 'banner'=>$model->primaryKey]);
				return Html::a($model->view->clicks ? $model->view->clicks : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['permanent_search'] = [
			'attribute' => 'permanent_search',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->view->permanent ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		if(!Yii::$app->request->get('trash') && !Yii::$app->request->get('expired')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
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
				->where(['banner_id' => $id])
				->one();
			return $model->$column;
			
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
		return ($returnAlias ? Yii::getAlias('@webroot/public/banner') : 'public/banner');
	}

	/**
	 * after find attributes
	 */
	public function afterFind() 
	{
		$this->old_banner_filename_i = $this->banner_filename;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		$setting = BannerSetting::find()
			->select(['banner_validation','banner_file_type'])
			->where(['id' => 1])->one();

		$banner_file_type = $this->formatFileType($setting->banner_file_type);
		if(empty($banner_file_type))
			$banner_file_type = [];

		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

			if($this->linked_i) {
				if($this->url == '-')
					$this->addError('url', Yii::t('app', '{attribute} harus dalam format hyperlink', ['attribute'=>$this->getAttributeLabel('url')]));
			} else
				$this->url = '-';

			if($this->permanent_i)
				$this->expired_date = '0000-00-00';
			else {
				if(in_array(Yii::$app->formatter->asDate($this->expired_date, 'php:Y-m-d'), ['0000-00-00','1970-01-01','0002-12-02','-0001-11-30']))
					$this->addError('expired_date', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('expired_date')]));

				if(Yii::$app->formatter->asDate($this->published_date, 'php:Y-m-d') >= Yii::$app->formatter->asDate($this->expired_date, 'php:Y-m-d'))
					$this->addError('expired_date', Yii::t('app', '{expired-date} harus lebih besar dari {published-date}', ['expired-date'=>$this->getAttributeLabel('expired_date'), 'published-date'=>$this->getAttributeLabel('published_date')]));
			}

			$banner_filename = UploadedFile::getInstance($this, 'banner_filename');
			if($banner_filename instanceof UploadedFile && !$banner_filename->getHasError()) {
				if(!in_array(strtolower($banner_filename->getExtension()), $banner_file_type)) {
					$this->addError('banner_filename', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', array(
						'name'=>$banner_filename->name,
						'extensions'=>$setting->banner_file_type,
					)));

				} else {
					$fileSize = getimagesize($banner_filename->tempName);
					if($this->cat_id && $setting->banner_validation) {
						$banner_size = $this->category->banner_size;
						if(empty($banner_size))
							$this->addError('cat_id', Yii::t('app', 'Validate and resize banner is enable. {attribute} belum memiliki ukuran.', array('{attribute}'=>$this->getAttributeLabel('cat_id'))));

						else {
							if(!($fileSize[0] == $banner_size['width'] && $fileSize[1] == $banner_size['height'])) {
								$this->addError('banner_filename', Yii::t('app', 'The file {name} cannot be uploaded. ukuran banner ({file_size}) tidak sesuai dengan kategori ({banner_size})', array(
									'name'=>$banner_filename->name,
									'file_size'=>$fileSize[0].'x'.$fileSize[1],
									'banner_size'=>BannerCategory::getSize($banner_size),
								)));
							}
						}
					}
				}
			} else {
				if($this->isNewRecord)
					$this->addError('banner_filename', Yii::t('app', '{attribute} cannot be blank.', array('{attribute}'=>$this->getAttributeLabel('banner_filename'))));
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		$setting = BannerSetting::find()
			->select(['banner_validation','banner_resize'])
			->where(['id' => 1])->one();
			
		if(parent::beforeSave($insert)) {
			if(!$insert) {
				$uploadPath = self::getUploadPath();
				$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
				$this->createUploadDirectory(self::getUploadPath());

				$this->banner_filename = UploadedFile::getInstance($this, 'banner_filename');
				if($this->banner_filename instanceof UploadedFile && !$this->banner_filename->getHasError()) {
					$fileName = time().'_'.$this->banner_id.'.'.strtolower($this->banner_filename->getExtension()); 
					if($this->banner_filename->saveAs(join('/', [$uploadPath, $fileName]))) {
						if($this->old_banner_filename_i != '' && file_exists(join('/', [$uploadPath, $this->old_banner_filename_i])))
							rename(join('/', [$uploadPath, $this->old_banner_filename_i]), join('/', [$verwijderenPath, time().'_change_'.$this->old_banner_filename_i]));
						$this->banner_filename = $fileName;

						//if(!$setting->banner_validation && $setting->banner_resize)
						//	self::resizeBanner(join('/', [$uploadPath, $fileName]), unserialize($this->category->banner_size));
					}
				} else {
					if($this->banner_filename == '')
						$this->banner_filename = $this->old_banner_filename_i;
				}
			}
			$this->published_date = Yii::$app->formatter->asDate($this->published_date, 'php:Y-m-d');
			$this->expired_date = Yii::$app->formatter->asDate($this->expired_date, 'php:Y-m-d');
		}
		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes) 
	{
		parent::afterSave($insert, $changedAttributes);

		$setting = BannerSetting::find()
			->select(['banner_validation','banner_resize'])
			->where(['id' => 1])->one();
			
		$uploadPath = self::getUploadPath();
		$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
		$this->createUploadDirectory(self::getUploadPath());

		if($insert) {
			$this->banner_filename = UploadedFile::getInstance($this, 'banner_filename');
			if($this->banner_filename instanceof UploadedFile && !$this->banner_filename->getHasError()) {
				$fileName = time().'_'.$this->banner_id.'.'.strtolower($this->banner_filename->getExtension()); 
				if($this->banner_filename->saveAs(join('/', [$uploadPath, $fileName]))) {
					self::updateAll(['banner_filename' => $fileName], ['banner_id' => $this->banner_id]);

					//if(!$setting->banner_validation && $setting->banner_resize)
					//	self::resizeBanner(join('/', [$uploadPath, $fileName]), unserialize($this->category->banner_size));
				}
			}
		}
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete() 
	{
		parent::afterDelete();

		$uploadPath = self::getUploadPath();
		$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);

		if($this->banner_filename != '' && file_exists(join('/', [$uploadPath, $this->banner_filename])))
			rename(join('/', [$uploadPath, $this->banner_filename]), join('/', [$verwijderenPath, time().'_deleted_'.$this->banner_filename]));
	}
}
