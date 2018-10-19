<?php
/**
 * ViewBanners
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 January 2017, 19:20 WIB
 * @modified date 19 January 2018, 21:10 WIB
 * @link https://github.com/ommu/mod-banner
 *
 * This is the model class for table "_banners".
 *
 * The followings are the available columns in table '_banners':
 * @property string $banner_id
 * @property integer $publish
 * @property integer $permanent
 * @property string $views
 * @property string $clicks
 */
class ViewBanners extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewBanners the static model class
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
		return $matches[1].'._banners';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'banner_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('publish, permanent', 'numerical', 'integerOnly'=>true),
			array('banner_id', 'length', 'max'=>11),
			array('views, clicks', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('banner_id, publish, permanent, views, clicks', 'safe', 'on'=>'search'),
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
			'banner_id' => Yii::t('attribute', 'Banner'),
			'publish' => Yii::t('attribute', 'Publish'),
			'permanent' => Yii::t('attribute', 'Permanent'),
			'views' => Yii::t('attribute', 'Views'),
			'clicks' => Yii::t('attribute', 'Clicks'),
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

		$criteria->compare('t.banner_id', strtolower($this->banner_id), true);
		$criteria->compare('t.publish', $this->publish);
		$criteria->compare('t.permanent', $this->permanent);
		$criteria->compare('t.views', strtolower($this->views), true);
		$criteria->compare('t.clicks', strtolower($this->clicks), true);

		if(!Yii::app()->getRequest()->getParam('ViewBanners_sort'))
			$criteria->order = 't.banner_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['grid-view'] ? Yii::app()->params['grid-view']['pageSize'] : 50,
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
			$this->templateColumns['banner_id'] = array(
				'name' => 'banner_id',
				'value' => '$data->banner_id',
			);
			$this->templateColumns['publish'] = array(
				'name' => 'publish',
				'value' => '$data->publish',
			);
			$this->templateColumns['permanent'] = array(
				'name' => 'permanent',
				'value' => '$data->permanent',
			);
			$this->templateColumns['views'] = array(
				'name' => 'views',
				'value' => '$data->views',
			);
			$this->templateColumns['clicks'] = array(
				'name' => 'clicks',
				'value' => '$data->clicks',
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
			$model = self::model()->findByPk($id, array(
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