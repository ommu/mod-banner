<?php
/**
 * Banners
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 5 October 2017, 16:51 WIB
 * @modified date 12 February 2019, 22:27 WIB
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
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\behaviors\SluggableBehavior;
use thamtech\uuid\helpers\UuidHelper;
use app\models\Users;
use ommu\banner\models\view\Banners as BannersView;

class Banners extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['url', 'banner_filename', 'banner_desc', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date', 'slug'];

	public $linked;
	public $permanent;
	public $old_banner_filename;
	public $categoryName;
	public $creationDisplayname;
	public $modifiedDisplayname;

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
			[['cat_id', 'title', 'url', 'published_date', 'expired_date', 'linked', 'permanent'], 'required'],
			[['publish', 'cat_id', 'creation_id', 'modified_id', 'linked', 'permanent'], 'integer'],
			[['url', 'banner_desc', 'slug'], 'string'],
			[['banner_filename', 'banner_desc', 'published_date', 'expired_date'], 'safe'],
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
			'old_banner_filename' => Yii::t('app', 'Old Filename'),
			'clicks' => Yii::t('app', 'Clicks'),
			'views' => Yii::t('app', 'Views'),
			'linked' => Yii::t('app', 'Linked'),
			'permanent' => Yii::t('app', 'Permanent'),
			'categoryName' => Yii::t('app', 'Category'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
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
            ->where(['t.banner_id' => $this->banner_id]);
		$clicks = $model->sum('clicks');

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
            ->where(['t.banner_id' => $this->banner_id]);
		$views = $model->sum('views');

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
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(BannersView::className(), ['banner_id' => 'banner_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\query\Banners the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\banner\models\query\Banners(get_called_class());
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
			'filter' => BannerCategory::getCategory(),
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
				return $model->url;
			},
		];
		$this->templateColumns['banner_filename'] = [
			'attribute' => 'banner_filename',
			'value' => function($model, $key, $index, $column) {
				$uploadPath = self::getUploadPath(false);
				return $model->banner_filename ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->banner_filename])), ['alt' => $model->banner_filename]) : '-';
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
				$expired_date = Yii::$app->formatter->asDate($model->expired_date, 'medium');
				return $expired_date != '-' ? $expired_date : Yii::t('app', 'Permanent');
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
		$this->templateColumns['clicks'] = [
			'attribute' => 'clicks',
			'value' => function($model, $key, $index, $column) {
				$clicks = $model->getClicks(true);
				return Html::a($clicks, ['o/click/manage', 'banner' => $model->primaryKey], ['title' => Yii::t('app', '{count} clicks', ['count' => $clicks]), 'data-pjax' => 0]);
			},
			'filter' => false,
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				$views = $model->getViews(true);
				return Html::a($views, ['o/view/manage', 'banner' => $model->primaryKey], ['title' => Yii::t('app', '{count} views', ['count' => $views]), 'data-pjax' => 0]);
			},
			'filter' => false,
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['permanent'] = [
			'attribute' => 'permanent',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->view->permanent);
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
            'visible' => !Yii::$app->request->get('trash') && !Yii::$app->request->get('expired') ? true : false,
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
		return ($returnAlias ? Yii::getAlias('@public/banner') : 'banner');
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->old_banner_filename = $this->banner_filename;
		// $this->categoryName = isset($this->category) ? $this->category->title->message : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		$setting = BannerSetting::find()
			->select(['banner_validation', 'banner_file_type'])
			->where(['id' => 1])->one();

		$banner_file_type = $this->formatFileType($setting->banner_file_type);
        if (empty($banner_file_type)) {
            $banner_file_type = [];
        }

        if (parent::beforeValidate()) {
            $this->is_banner = 1;

            if ($this->linked) {
                if ($this->url == '-') {
                    $this->addError('url', Yii::t('app', '{attribute} harus dalam format hyperlink', ['attribute' => $this->getAttributeLabel('url')]));
                }
			} else {
                $this->url = '-';
            }

            if ($this->permanent) {
                $this->expired_date = '0000-00-00';
            } else {
                if (in_array(Yii::$app->formatter->asDate($this->expired_date, 'php:Y-m-d'), ['0000-00-00', '1970-01-01', '0002-12-02', '-0001-11-30'])) {
                    $this->addError('expired_date', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('expired_date')]));
                }

                if (Yii::$app->formatter->asDate($this->published_date, 'php:Y-m-d') >= Yii::$app->formatter->asDate($this->expired_date, 'php:Y-m-d')) {
					$this->addError('expired_date', Yii::t('app', '{expired-date} harus lebih besar dari {published-date}', [
						'expired-date' => $this->getAttributeLabel('expired_date'), 
						'published-date' => $this->getAttributeLabel('published_date'),
                    ]));
                }
			}

			// $this->banner_filename = UploadedFile::getInstance($this, 'banner_filename');
            if ($this->banner_filename instanceof UploadedFile && !$this->banner_filename->getHasError()) {
                if (!in_array(strtolower($this->banner_filename->getExtension()), $banner_file_type)) {
					$this->addError('banner_filename', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
						'name' => $this->banner_filename->name,
						'extensions' => $setting->banner_file_type,
					]));

				} else {
					$fileSize = getimagesize($this->banner_filename->tempName);
                    if ($this->cat_id && $setting->banner_validation) {
						$banner_size = $this->category->banner_size;
                        if (empty($banner_size)) {
                            $this->addError('cat_id', Yii::t('app', 'Validate and resize banner is enable. {attribute} belum memiliki ukuran.', ['attribute' => $this->getAttributeLabel('cat_id')]));
                        } else {
                            if (!($fileSize[0] == $banner_size['width'] && $fileSize[1] == $banner_size['height'])) {
								$this->addError('banner_filename', Yii::t('app', 'The file {name} cannot be uploaded. ukuran banner ({file_size}) tidak sesuai dengan kategori ({banner_size})', [
									'name' => $this->banner_filename->name,
									'file_size' => $fileSize[0].'x'.$fileSize[1],
									'banner_size' => BannerCategory::getSize($banner_size),
								]));
							}
						}
					}
                }
            } else {
                if ($this->isNewRecord || (!$this->isNewRecord && $this->old_banner_filename == '')) {
                    $this->addError('banner_filename', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('banner_filename')]));
                }
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
		$setting = BannerSetting::find()
			->select(['banner_validation', 'banner_resize'])
			->where(['id' => 1])
            ->one();

        if (parent::beforeSave($insert)) {
            if (!$insert) {
                $uploadPath = self::getUploadPath();
                $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
                $this->createUploadDirectory(self::getUploadPath());

				$banner_size = $this->category->banner_size;
				// $this->banner_filename = UploadedFile::getInstance($this, 'banner_filename');
                if ($this->banner_filename instanceof UploadedFile && !$this->banner_filename->getHasError()) {
					$fileName = join('-', [time(), UuidHelper::uuid(), $this->banner_id]).'.'.strtolower($this->banner_filename->getExtension());
                    if ($this->banner_filename->saveAs(join('/', [$uploadPath, $fileName]))) {
                        if ($this->old_banner_filename != '' && file_exists(join('/', [$uploadPath, $this->old_banner_filename]))) {
                            rename(join('/', [$uploadPath, $this->old_banner_filename]), join('/', [$verwijderenPath, $this->banner_id.'-'.time().'_change_'.$this->old_banner_filename]));
                        }
						$this->banner_filename = $fileName;

                        if (!$setting->banner_validation && $setting->banner_resize) {
                            $this->resizeImage(join('/', [$uploadPath, $fileName]), $banner_size['width'], $banner_size['height']);
                        }
					}
				} else {
                    if ($this->banner_filename == '') {
                        $this->banner_filename = $this->old_banner_filename;
                    }
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
			->select(['banner_validation', 'banner_resize'])
			->where(['id' => 1])->one();

        $uploadPath = self::getUploadPath();
        $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
        $this->createUploadDirectory(self::getUploadPath());

        if ($insert) {
			$banner_size = $this->category->banner_size;
			// $this->banner_filename = UploadedFile::getInstance($this, 'banner_filename');
            if ($this->banner_filename instanceof UploadedFile && !$this->banner_filename->getHasError()) {
				$fileName = join('-', [time(), UuidHelper::uuid(), $this->banner_id]).'.'.strtolower($this->banner_filename->getExtension());
                if ($this->banner_filename->saveAs(join('/', [$uploadPath, $fileName]))) {
					self::updateAll(['banner_filename' => $fileName], ['banner_id' => $this->banner_id]);

                    if (!$setting->banner_validation && $setting->banner_resize) {
                        $this->resizeImage(join('/', [$uploadPath, $fileName]), $banner_size['width'], $banner_size['height']);
                    }
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

        if ($this->banner_filename != '' && file_exists(join('/', [$uploadPath, $this->banner_filename]))) {
            rename(join('/', [$uploadPath, $this->banner_filename]), join('/', [$verwijderenPath, $this->banner_id.'-'.time().'_deleted_'.$this->banner_filename]));
        }
	}
}
