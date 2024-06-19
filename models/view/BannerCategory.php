<?php
/**
 * BannerCategory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 11 October 2017, 10:32 WIB
 * @modified date 24 January 2019, 16:50 WIB
 * @link https://github.com/ommu/mod-banner
 *
 * This is the model class for table "_banner_category".
 *
 * The followings are the available columns in table "_banner_category":
 * @property integer $cat_id
 * @property string $publish
 * @property string $permanent
 * @property string $pending
 * @property string $expired
 * @property string $unpublish
 * @property integer $all
 *
 */

namespace ommu\banner\models\view;

use Yii;

class BannerCategory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_banner_category';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['cat_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['cat_id', 'all'], 'integer'],
			[['publish', 'permanent', 'pending', 'expired', 'unpublish'], 'number'],
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
			'permanent' => Yii::t('app', 'Permanent'),
			'pending' => Yii::t('app', 'Pending'),
			'expired' => Yii::t('app', 'Expired'),
			'unpublish' => Yii::t('app', 'Unpublish'),
			'all' => Yii::t('app', 'All'),
		];
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
}
