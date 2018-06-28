<?php
/**
 * ViewBannerCategory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (opensource.ommu.co)
 * @modified date 19 January 2018, 21:09 WIB
 * @link https://github.com/ommu/mod-banner
 *
 * This is the model class for table "_banner_category".
 *
 * The followings are the available columns in table '_banner_category':
 * @property integer $cat_id
 * @property string $banners
 * @property string $banner_pending
 * @property string $banner_expired
 * @property string $banner_unpublish
 * @property string $banner_all
 */
class ViewBannerCategory extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewBannerCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
		return $matches[1].'._banner_category';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'cat_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id', 'numerical', 'integerOnly'=>true),
			array('banners, banner_pending, banner_expired, banner_unpublish', 'length', 'max'=>23),
			array('banner_all', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cat_id, banners, banner_pending, banner_expired, banner_unpublish, banner_all', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cat_id' => Yii::t('attribute', 'Category'),
			'banners' => Yii::t('attribute', 'Banners'),
			'banner_pending' => Yii::t('attribute', 'Banner Pending'),
			'banner_expired' => Yii::t('attribute', 'Banner Expired'),
			'banner_unpublish' => Yii::t('attribute', 'Banner Unpublish'),
			'banner_all' => Yii::t('attribute', 'Banner All'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.cat_id', $this->cat_id);
		$criteria->compare('t.banners', strtolower($this->banners), true);
		$criteria->compare('t.banner_pending', strtolower($this->banner_pending), true);
		$criteria->compare('t.banner_expired', strtolower($this->banner_expired), true);
		$criteria->compare('t.banner_unpublish', strtolower($this->banner_unpublish), true);
		$criteria->compare('t.banner_all', strtolower($this->banner_all), true);

		if(!Yii::app()->getRequest()->getParam('ViewBannerCategory_sort'))
			$criteria->order = 't.cat_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['grid-view'] ? Yii::app()->params['grid-view']['pageSize'] : 20,
			),
		));
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->templateColumns) == 0) {
			$this->templateColumns['_option'] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			$this->templateColumns['_no'] = array(
				'header' => Yii::t('app', 'No'),
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->templateColumns['cat_id'] = array(
				'name' => 'cat_id',
				'value' => '$data->cat_id',
			);
			$this->templateColumns['banners'] = array(
				'name' => 'banners',
				'value' => '$data->banners',
			);
			$this->templateColumns['banner_pending'] = array(
				'name' => 'banner_pending',
				'value' => '$data->banner_pending',
			);
			$this->templateColumns['banner_expired'] = array(
				'name' => 'banner_expired',
				'value' => '$data->banner_expired',
			);
			$this->templateColumns['banner_unpublish'] = array(
				'name' => 'banner_unpublish',
				'value' => '$data->banner_unpublish',
			);
			$this->templateColumns['banner_all'] = array(
				'name' => 'banner_all',
				'value' => '$data->banner_all',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column,
			));
 			if(count(explode(',', $column)) == 1)
 				return $model->$column;
 			else
 				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

}