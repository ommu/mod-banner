<?php
/**
 * BannerCategory
 * version: 0.0.1
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
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property Banners[] $banners

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 5 October 2017, 15:42 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\banner\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\behaviors\SluggableBehavior;
use app\coremodules\user\models\Users;
use app\libraries\grid\GridView;
use app\components\Utility;
use app\models\SourceMessage;
use app\modules\banner\models\view\BannerCategory as BannerCategoryView;

class BannerCategory extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = ['modified_date','modified_search','slug','desc_i',
        'creation_date','creation_search'];

    // Variable Search
    public $name_i;
    public $desc_i;
    public $creation_search;
    public $modified_search;
    public $banner_search;
    public $pending_search;
    public $expired_search;
    public $unpublish_search;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'ommu_banner_category';
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
                'attribute' => 'name',
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
            [['publish', 'name', 'desc', 'banner_limit', 'creation_id', 'modified_id'], 'integer'],
            [['name_i', 'desc_i', 'cat_code', 'banner_size', 'banner_limit'], 'required'],
            [[], 'string'],
            [['creation_date', 'modified_date'], 'safe'],
            [['cat_code', 'slug', 'name_i'], 'string', 'max' => 32],
            [['desc_i'], 'string', 'max' => 256],
            [['banner_limit'], 'string', 'max' => 3],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanners()
    {
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
     * @return \yii\db\ActiveQuery
     */
    public function getView()
    {
        return $this->hasOne(BannerCategoryView::className(), ['cat_id' => 'cat_id']);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'cat_id'           => Yii::t('app', 'Category'),
            'publish'          => Yii::t('app', 'Publish'),
            'name'             => Yii::t('app', 'Name'),
            'desc'             => Yii::t('app', 'Description'),
            'cat_code'         => Yii::t('app', 'Code'),
            'banner_size'      => Yii::t('app', ' Size'),
            'banner_limit'     => Yii::t('app', ' Limit'),
            'creation_date'    => Yii::t('app', 'Creation Date'),
            'creation_id'      => Yii::t('app', 'Creation'),
            'modified_date'    => Yii::t('app', 'Modified Date'),
            'modified_id'      => Yii::t('app', 'Modified'),
            'slug'             => Yii::t('app', 'Slug'),
            'creation_search'  => Yii::t('app', 'Creation'),
            'modified_search'  => Yii::t('app', 'Modified'),
            'name_i'           => Yii::t('app', 'Name'),
            'desc_i'           => Yii::t('app', 'Description'),
            'banner_search'    => Yii::t('app', 'Banners'),
            'pending_search'   => Yii::t('app', 'Pending'),
            'expired_search'   => Yii::t('app', 'Expired'),
            'unpublish_search' => Yii::t('app', 'Unpublish'),
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
        $this->templateColumns['name_i'] = [
            'attribute' => 'name_i',
            'value' => function($model, $key, $index, $column) {
                return (isset($model->name) && isset($model->title))? $model->title->message : '-';
            },
        ];
        $this->templateColumns['desc_i'] = [
            'attribute' => 'desc_i',
            'value' => function($model, $key, $index, $column) {
                return $model->desc ? $model->description->message : '-';
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
            'contentOptions' => ['class'=>'center'],
        ];
        $this->templateColumns['creation_date'] = [
            'attribute' => 'creation_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'creation_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                return !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'date'/*datetime*/) : '-';
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
                return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'date'/*datetime*/) : '-';
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
        $this->templateColumns['banner_limit'] = [
            'attribute' => 'banner_limit',
            'value' => function($model, $key, $index, $column) {
                return $model->banner_limit;
            },
            'contentOptions' => ['class'=>'center'],
        ];
        $this->templateColumns['banner_search'] = [
            'attribute' => 'banner_search',
            'value' => function($model, $key, $index, $column) {
                $url = Url::to(['admin/index', 'category' => $model->primaryKey, 'publish' => 'true']);
                return Html::a($model->view->banners ? $model->view->banners : 0, $url);
            },
            'contentOptions' => ['class'=>'center'],
            'format'    => 'html',
        ];
        $this->templateColumns['pending_search'] = [
            'attribute' => 'pending_search',
            'value' => function($model, $key, $index, $column) {
                $url = Url::to(['admin/index', 'category' => $model->primaryKey, 'pending' => 'true']);
                return Html::a($model->view->banner_pending ? $model->view->banner_pending : 0, $url);
            },
            'contentOptions' => ['class'=>'center'],
            'format'    => 'html',
        ];
        $this->templateColumns['expired_search'] = [
            'attribute' => 'expired_search',
            'value' => function($model, $key, $index, $column) {
                $url = Url::to(['admin/index', 'category' => $model->primaryKey, 'expired' => 'true']);
                return Html::a($model->view->banner_expired ? $model->view->banner_expired : 0, $url);
            },
            'contentOptions' => ['class'=>'center'],
            'format'    => 'html',
        ];
        $this->templateColumns['unpublish_search'] = [
            'attribute' => 'unpublish_search',
            'value' => function($model, $key, $index, $column) {
                $url = Url::to(['admin/index', 'category' => $model->primaryKey, 'unpublish' => 'true']);
                return Html::a($model->view->banner_unpublish ? $model->view->banner_unpublish : 0, $url);
            },
            'contentOptions' => ['class'=>'center'],
            'format'    => 'html',
        ];
        if(!Yii::$app->request->get('trash')) {
            $this->templateColumns['publish'] = [
                'attribute' => 'publish',
                'filter' => GridView::getFilterYesNo(),
                'value' => function($model, $key, $index, $column) {
                    $url = Url::to(['category/publish', 'id' => $model->primaryKey]);
                    return GridView::getPublish($url, $model->publish);
                },
                'contentOptions' => ['class'=>'center'],
                'format'    => 'raw',
            ];
        }
    }

    /**
     * function getCategory
     */
    public static function getCategory($publish = null) {
        $items = []; 
        $model = self::find();
        if($publish != null) { 
            $model = $model->andWhere(['publish' => $publish]);
        }
        $model = $model->orderBy('name ASC')->all();

        if($model !== null) {
            foreach($model as $val) {
                $items[$val->cat_id] = isset($val->title)? $val->title->message: '-';
            }
        }
        
        return $items;
    }

    /**
     * getSize
     */
    public static function getSize($banner_size)
    {
        $bannerSize = unserialize($banner_size);
        return $bannerSize['width'].' x '.$bannerSize['height'];
    }

    /**
     * afterFind
     *
     * Simpan nama banner lama untuk keperluan jikalau kondisi update tp bannernya tidak diupdate.
     */
    public function afterFind() 
    {
        $this->name_i = $this->getTitle()->one() != null? $this->getTitle()->one()->message: '';
        $this->desc_i = $this->getDescription()->one() != null? $this->getDescription()->one()->message: '';
    }

    /**
     * before validate attributes
     */
    public function beforeValidate() 
    {
        if(parent::beforeValidate()) {
            if($this->isNewRecord) {
                $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
                $this->modified_id = 0;

            }else
                $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
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
        $location = Utility::getUrlTitle($module.' '.$controller);

        if(parent::beforeSave($insert)) {

            $transaction = SourceMessage::getDb()->beginTransaction();
            try {
                if($insert) {
                    $name = new SourceMessage();
                    $name->location = $location . '_name';
                    $name->message  = $this->name_i;
                    if(!$name->save()) {
                        throw new \Exception('Tidak dapat menyimpan nama kategori!.');                        
                    }
                    $this->name = $name->id;

                    $desc = new SourceMessage();
                    $desc->location = $location . '_desc';
                    $desc->message  = $this->desc_i;
                    if(!$desc->save()) {
                        throw new \Exception('Tidak dapat menyimpan deskripsi kategori!.');                        
                    }
                    $this->desc = $desc->id;
                
                }else {
                    // nama
                    $name = null;
                    if((int)$this->name < 1) {
                        $name = new SourceMessage();
                        $name->message = $this->name_i;
                    
                    }else {
                        $name = SourceMessage::findOne($this->name);
                        $name->message = $this->name_i;
                    }
                    if(!$name->save()) {
                        throw new \Exception('Tidak dapat menyimpan nama kategori!.');
                    }

                    // deskripsi
                    $desc = null;
                    if((int)$this->desc < 1) {
                        $desc = new SourceMessage();
                        $desc->message = $this->desc_i;
                    
                    }else {
                        $desc = SourceMessage::findOne($this->desc);
                        $desc->message = $this->desc_i;
                    }
                    if(!$desc->save()) {
                        throw new \Exception('Tidak dapat menyimpan deskripsi kategori!.');
                    }
                }
                $transaction->commit();

            }catch(\Exception $e) {
                $transaction->rollBack();
                Yii::error('## BannerCategory.beforeSave() exception: ' . $e->getMessage());
            }

            // TODO: hapus kode dibawah jika tidak ada error.
            // if($this->isNewRecord || (!$this->isNewRecord && !$this->name)) {
            //     $name = new SourceMessage();
            //     $name->location = $location.'_name';
            //     $name->message = $this->name_i;
            //     if($name->save())
            //         $this->name = $name->id;
                
            // } else {
            //     if($action == 'update') {
            //         $name = SourceMessage::findOne($this->name);
            //         $name->message = $this->name_i;
            //         $name->save();
            //     }
            // }

            // if($this->isNewRecord || (!$this->isNewRecord && !$this->desc)) {
            //     $desc = new SourceMessage();
            //     $desc->location = $location.'_desc';
            //     $desc->message = $this->desc_i;
            //     if($desc->save())
            //         $this->desc = $desc->id;
                
            // } else {
            //     if($action == 'update') {
            //         $desc = SourceMessage::findOne($this->desc);
            //         $desc->message = $this->desc_i;
            //         $desc->save();
            //     }
            // }
            
            if(in_array($action, ['create','update']))
                $this->banner_size = serialize($this->banner_size);                
        }
        return true;    
    }
}
