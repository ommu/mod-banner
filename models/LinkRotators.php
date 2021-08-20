<?php
/**
 * LinkRotators
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 August 2021, 19:45 WIB
 * @link https://github.com/ommu/mod-banner
 *
 * This is the model class for table "ommu_banner_category".
 *
 * The followings are the available columns in table "ommu_banner_category":
 * @property integer $cat_id
 * @property integer $publish
 * @property string $type
 * @property string $rotator_type
 * @property integer $name
 * @property integer $desc
 * @property string $code
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property LinkRotatorItem[] $items
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\banner\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use app\models\SourceMessage;
use app\models\Users;

class LinkRotators extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date', 'desc_i'];

	public $name_i;
	public $desc_i;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $item;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_banner_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['type', 'rotator_type', 'name_i', 'desc_i'], 'required'],
			[['publish', 'name', 'desc', 'creation_id', 'modified_id'], 'integer'],
			[['type', 'rotator_type', 'name_i', 'desc_i'], 'string'],
			[['code'], 'unique'],
			[['code'], 'safe'],
			[['name_i', 'code'], 'string', 'max' => 64],
			[['desc_i'], 'string', 'max' => 128],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'rotator_type' => Yii::t('app', 'Type'),
			'name' => Yii::t('app', 'Name'),
			'desc' => Yii::t('app', 'Description'),
			'code' => Yii::t('app', 'Code'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'name_i' => Yii::t('app', 'Name'),
			'desc_i' => Yii::t('app', 'Description'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'item' => Yii::t('app', 'Item'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getItems($count=false, $publish=null)
	{
        if ($count == false) {
            $model = $this->hasMany(LinkRotatorItem::className(), ['cat_id' => 'cat_id'])
                ->alias('items');
            if ($publish != null) {
                $model->andOnCondition([sprintf('%s.publish', 'items') => $publish]);
            } else {
                $model->andOnCondition(['IN', sprintf('%s.publish', 'items'), [0,1]]);
            }

            return $model;
        }

		$model = LinkRotatorItem::find()
            ->alias('t')
            ->where(['t.cat_id' => $this->cat_id]);
        if ($publish != null) {
            if ($publish == 0) {
                $model->unpublish();
            } else if ($publish == 1) {
                $model->published();
            } else if ($publish == 2) {
                $model->deleted();
            }
        } else {
            $model->andWhere(['IN', 'publish', [0,1]]);
        }
		$items = $model->count();

		return $items ? $items : 0;
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
	 * @return \ommu\banner\models\query\LinkRotators the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\banner\models\query\LinkRotators(get_called_class());
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
		$this->templateColumns['name_i'] = [
			'label' => Yii::t('app', 'Name'),
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
        $this->templateColumns['rotator_type'] = [
            'attribute' => 'rotator_type',
            'value' => function($model, $key, $index, $column) {
                return self::getRotatorType($model->rotator_type);
            },
            'filter' => self::getRotatorType(),
        ];
		$this->templateColumns['code'] = [
			'attribute' => 'code',
			'value' => function($model, $key, $index, $column) {
				return $model->code;
			},
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
		$this->templateColumns['item'] = [
			'attribute' => 'item',
			'value' => function($model, $key, $index, $column) {
				$items = $model->getItems(true);
				return Html::a($items, ['rotator/item/manage', 'category' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} items', ['count' => $items]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
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
            $model = $model->where(['cat_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

    /**
     * function getRotatorType
     */
    public static function getRotatorType($value=null)
    {
        $items = array(
            'url' => Yii::t('app', 'URL'),
            'wa' => Yii::t('app', 'WhatsApp'),
        );

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
    }

	/**
	 * function getRotator
	 */
	public static function getRotator($publish=null, $array=true) 
	{
		$model = self::find()->alias('t')
			->select(['t.cat_id', 't.name']);
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.name=title.id');
        if ($publish != null) {
            $model->andWhere(['t.publish' => $publish]);
        }
        $model->andWhere(['t.type' => 'rotator']);

		$model = $model->orderBy('title.message ASC')->all();

        if ($array == true) {
            return \yii\helpers\ArrayHelper::map($model, 'cat_id', 'name_i');
        }

		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->name_i = isset($this->title) ? $this->title->message : '';
		$this->desc_i = isset($this->description) ? $this->description->message : '';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		$this->item = $this->getItems(true) ? 1 : 0;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            $this->type = 'rotator';

            if ($this->code == '') {
                $this->code = $this->name_i;
                $this->code = Inflector::camelize($this->code);
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
        $module = strtolower(Yii::$app->controller->module->id);
        $controller = strtolower(Yii::$app->controller->id);
        $action = strtolower(Yii::$app->controller->action->id);

        $location = Inflector::slug($module.' '.$controller);

        if (parent::beforeSave($insert)) {
            if ($insert || (!$insert && !$this->name)) {
                $name = new SourceMessage();
                $name->location = $location.'_title';
                $name->message = $this->name_i;
                if ($name->save()) {
                    $this->name = $name->id;
                }

            } else {
                $name = SourceMessage::findOne($this->name);
                $name->message = $this->name_i;
                $name->save();
            }

            if ($insert || (!$insert && !$this->desc)) {
                $desc = new SourceMessage();
                $desc->location = $location.'_description';
                $desc->message = $this->desc_i;
                if ($desc->save()) {
                    $this->desc = $desc->id;
                }

            } else {
                $desc = SourceMessage::findOne($this->desc);
                $desc->message = $this->desc_i;
                $desc->save();
            }
        }

        return true;
	}
}
