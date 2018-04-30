<?php
/**
 * Banners
 * version: 0.0.1
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
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property BannerClicks[] $clicks
 * @property BannerViews[] $views
 * @property BannerCategory $category

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 5 October 2017, 16:51 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\banner\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\behaviors\SluggableBehavior;
use app\coremodules\user\models\Users;
use app\libraries\grid\GridView;
use app\modules\banner\models\view\Banners as BannersView;

class Banners extends \app\components\ActiveRecord
{
    // Include semua fungsi yang ada pada traits FileSystem;
    use \app\components\traits\FileSystem;

    public $gridForbiddenColumn = ['creation_date', 'creation_search', 'modified_date', 'modified_search', 
        'slug', 'url', 'banner_filename', 'banner_desc'];

    // Variable Search
    public $linked_i;
    public $permanent_i;
    public $old_banner_filename_i;
    public $creation_search;
    public $modified_search;
    public $click_search;
    public $view_search;

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
                'class'     => SluggableBehavior::className(),
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
            [['publish', 'cat_id', 'creation_id', 'modified_id', 'linked_i', 'permanent_i'], 'integer'],
            [['cat_id', 'title', 'url', 'banner_desc', 'published_date', 'linked_i', 'permanent_i'], 'required'],
            [['banner_filename'], 'required', 'on' => 'formCreate'],
            [['url', 'banner_desc', 'slug'], 'string'],
            [['published_date', 'expired_date', 'creation_date', 'modified_date', 'banner_filename', 
                'url', 'old_banner_filename_i'], 'safe'],
            [['title'], 'string', 'max' => 64],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerCategory::className(), 
                'targetAttribute' => ['cat_id' => 'cat_id']],
            [['banner_filename'], 'file', 'extensions' => 'jpeg, jpg, png, bmp, gif'],
            [['url'], 'filter', 'filter' => 'trim'],
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
            'slug' => Yii::t('app', 'Slug'),
            'linked_i' => Yii::t('app', 'Linked'),
            'permanent_i' => Yii::t('app', 'Permanent'),
            'old_banner_filename_i' => Yii::t('app', 'Old Filename'),
            'creation_search' => Yii::t('app', 'Creation'),
            'modified_search' => Yii::t('app', 'Modified'),
            'click_search' => Yii::t('app', 'Clicks'),
            'view_search' => Yii::t('app', 'Views'),
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
            'contentOptions' => ['class'=>'center'],
        ];
        if(!isset($_GET['category'])) {
            $this->templateColumns['cat_id'] = [
                'attribute' => 'cat_id',
                'filter' => BannerCategory::getCategory(),
                'value' => function($model, $key, $index, $column) {
                    return isset($model->category->title)? $model->category->title->message: '-';
                },
            ];
        }
        $this->templateColumns['title'] = 'title';
        $this->templateColumns['url'] = 'url';
        $this->templateColumns['banner_filename'] = 'banner_filename';
        $this->templateColumns['banner_desc'] = 'banner_desc';
        $this->templateColumns['published_date'] = [
            'attribute' => 'published_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'published_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                return !in_array($model->published_date, ['0000-00-00','1970-01-01']) ? Yii::$app->formatter->format($model->published_date, 'date') : '-';
            },
            'format'    => 'html',
        ];
        $this->templateColumns['expired_date'] = [
            'attribute' => 'expired_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'expired_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                return !in_array($model->expired_date, ['0000-00-00','1970-01-01']) ? Yii::$app->formatter->format($model->expired_date, 'date') : '-';
            },
            'format' => 'html',
        ];
        $this->templateColumns['creation_date'] = [
            'attribute' => 'creation_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'creation_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                return !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-';
            },
            'format'    => 'html',
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
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'modified_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-';
            },
            'format'    => 'html',
        ];
        if(!Yii::$app->request->get('modified')) {
            $this->templateColumns['modified_search'] = [
                'attribute' => 'modified_search',
                'value' => function($model, $key, $index, $column) {
                    return isset($model->modified) ? $model->modified->displayname : '-';
                },
            ];
        }
        $this->templateColumns['slug'] = 'slug';
        $this->templateColumns['click_search'] = [
            'attribute' => 'click_search',
            'value' => function($model, $key, $index, $column) {
                $url = Url::to(['click/index', 'banner' => $model->primaryKey]);
                return Html::a($model->view->clicks ? $model->view->clicks : 0, $url);
            },
            'contentOptions' => ['class'=>'center'],
            'format'    => 'raw',
        ];
        $this->templateColumns['view_search'] = [
            'attribute' => 'view_search',
            'value' => function($model, $key, $index, $column) {
                $url = Url::to(['view/index', 'banner' => $model->primaryKey]);
                return Html::a($model->view->views ? $model->view->views : 0, $url);
            },
            'contentOptions' => ['class'=>'center'],
            'format'    => 'raw',
        ];
        if(!Yii::$app->request->get('trash')) {
            $this->templateColumns['publish'] = [
                'attribute' => 'publish',
                'filter' => GridView::getFilterYesNo(),
                'value' => function($model, $key, $index, $column) {
                    $url = Url::to(['publish', 'id' => $model->primaryKey]);
                    return GridView::getPublish($url, $model->publish);
                },
                'contentOptions' => ['class'=>'center'],
                'format'    => 'raw',
            ];
        }
    }
    
    /**
     * Mengembalikan lokasi banner
     *
     * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
     * relative path. default true.
     */
    public static function getBannerPath($returnAlias=true) 
    {
        return ($returnAlias ? Yii::getAlias('@webroot/public/banner') : 'public/banner');
    }

    /**
     * afterFind
     *
     * Simpan nama banner lama untuk keperluan jikalau kondisi update tp bannernya tidak diupdate.
     */
    public function afterFind() 
    {
        $this->old_banner_filename_i = $this->banner_filename;
        if($this->expired_date == '0000-00-00')
            $this->permanent_i = 1;

        $this->linked_i = 1;
        if($this->url == '-' || $this->url == '')
            $this->linked_i = 0;        
    }

    /**
     * before validate attributes
     */
    public function beforeValidate() 
    {
        if(parent::beforeValidate()) {
            if($this->isNewRecord)
                $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
            else
                $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
            
            if($this->linked_i == 0)
                $this->url = '-';
            
            if($this->linked_i == 1 && $this->url == '-')
                $this->addError('url', Yii::t('app', 'URL harus dalam format hyperlink'));

            if($this->permanent_i == 1)
                $this->expired_date = '0000-00-00';

            if($this->permanent_i != 1 && in_array(Yii::$app->formatter->asDate($this->expired_date, 'php:Y-m-d'), ['0000-00-00','1970-01-01','-0001-11-30']))
                $this->addError('expired_date', Yii::t('app', 'Expired Date cannot be blank.'));
            
            if($this->permanent_i != 1 && ($this->published_date != '' && $this->expired_date != '') && (Yii::$app->formatter->asDate($this->published_date, 'php:Y-m-d') >= Yii::$app->formatter->asDate($this->expired_date, 'php:Y-m-d')))
                $this->addError('expired_date', Yii::t('app', 'Expired Date harus lebih besar dari Publish Date'));
        }
        return true;
    }

    /**
     * before save attributes
     */
    public function beforeSave($insert) 
    {
        if(parent::beforeSave($insert)) {
            $bannerPath = Yii::getAlias('@webroot/public/banner');
            
            // Add directory
            if(!file_exists($bannerPath)) {
                @mkdir($bannerPath, 0755,true);

                // Add file in directory (index.php)
                $indexFile = join('/', [$bannerPath, 'index.php']);
                if(!file_exists($indexFile)) {
                    file_put_contents($indexFile, "<?php\n");
                }

            }else {
                @chmod($bannerPath, 0755,true);
            }

            $this->published_date = date('Y-m-d', strtotime($this->published_date));
            if($this->permanent_i != 1)
                $this->expired_date = date('Y-m-d', strtotime($this->published_date));

            // Upload banner
            if($this->banner_filename instanceof \yii\web\UploadedFile) {
                $imageName = time().'_'.$this->sanitizeFileName($this->title).'.'. $this->banner_filename->extension; 
                if($this->banner_filename->saveAs($bannerPath.'/'.$imageName)) {
                    $this->banner_filename = $imageName;
                    @chmod($imageName, 0777);
                }
            }
        }
        return true;
    }
    
    /**
     * After save attributes
     */
    public function afterSave($insert, $changedAttributes) 
    {
        parent::afterSave($insert, $changedAttributes);

        // jika banner file diperbarui, hapus banner yg lama.
        if(!$insert && $this->banner_filename != $this->old_banner_filename_i) {
            $fname = join('/', [self::getBannerPath(), $this->old_banner_filename_i]);
            if(file_exists($fname)) {
                @unlink($fname);
            }
        }
    }

    /**
     * After delete attributes
     */
    public function afterDelete() 
    {
        parent::afterDelete();

        $fname = join('/', [self::getBannerPath(), $this->banner_filename]);
        if(file_exists($fname)) {
            @unlink($fname);
        }
    }
}
