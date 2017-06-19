<?php
/**
 * BannerClickDetail
 * version: 1.3.0
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 8 January 2017, 19:18 WIB
 * @link https://github.com/ommu/mod-banner
 * @contact (+62)856-299-4114
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_banner_click_detail".
 *
 * The followings are the available columns in table 'ommu_banner_click_detail':
 * @property string $id
 * @property string $click_id
 * @property string $click_date
 * @property string $click_ip
 *
 * The followings are the available model relations:
 * @property OmmuBannerClicks $click
 */
class BannerClickDetail extends CActiveRecord
{
	public $defaultColumns = array();
	
	// Variable Search
	public $category_search;
	public $banner_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BannerClickDetail the static model class
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
		return 'ommu_banner_click_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('click_id, click_ip', 'required'),
			array('click_id', 'length', 'max'=>11),
			array('click_ip', 'length', 'max'=>20),
			array('click_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, click_id, click_date, click_ip,
				category_search, banner_search, user_search', 'safe', 'on'=>'search'),
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
			'click' => array(self::BELONGS_TO, 'BannerClicks', 'click_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'click_id' => Yii::t('attribute', 'Click'),
			'click_date' => Yii::t('attribute', 'Click Date'),
			'click_ip' => Yii::t('attribute', 'Click Ip'),
			'category_search' => Yii::t('attribute', 'Category'),
			'banner_search' => Yii::t('attribute', 'Banner'),
			'user_search' => Yii::t('attribute', 'User'),
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
		
		// Custom Search
		$criteria->with = array(
			'click' => array(
				'alias'=>'click',
				'select'=>'banner_id, user_id'
			),
			'click.banner' => array(
				'alias'=>'click_banner',
				'select'=>'cat_id, title'
			),
			'click.user' => array(
				'alias'=>'click_user',
				'select'=>'displayname'
			),
		);

		$criteria->compare('t.id',$this->id);
		if(isset($_GET['click']))
			$criteria->compare('t.click_id',$_GET['click']);
		else
			$criteria->compare('t.click_id',$this->click_id);
		if($this->click_date != null && !in_array($this->click_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.click_date)',date('Y-m-d', strtotime($this->click_date)));
		$criteria->compare('t.click_ip',strtolower($this->click_ip),true);
		
		$criteria->compare('click_banner.cat_id',$this->category_search);
		$criteria->compare('click_banner.title',strtolower($this->banner_search),true);
		$criteria->compare('click_user.displayname',strtolower($this->user_search),true);

		if(!isset($_GET['BannerClickDetail_sort']))
			$criteria->order = 't.id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'id';
			$this->defaultColumns[] = 'click_id';
			$this->defaultColumns[] = 'click_date';
			$this->defaultColumns[] = 'click_ip';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			if(!isset($_GET['click'])) {
				$this->defaultColumns[] = array(
					'name' => 'category_search',
					'value' => 'Phrase::trans($data->click->banner->category->name)',
					'filter'=> BannerCategory::getCategory(),
					'type' => 'raw',
				);
				$this->defaultColumns[] = array(
					'name' => 'banner_search',
					'value' => '$data->click->banner->title',
				);
				$this->defaultColumns[] = array(
					'name' => 'user_search',
					'value' => '$data->click->user->displayname ? $data->click->user->displayname : \'-\'',
				);			
			}
			$this->defaultColumns[] = array(
				'name' => 'click_date',
				'value' => 'Utility::dateFormat($data->click_date, true)',
				'htmlOptions' => array(
					//'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'click_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'click_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			$this->defaultColumns[] = array(
				'name' => 'click_ip',
				'value' => '$data->click_ip',
				'htmlOptions' => array(
					//'class' => 'center',
				),
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
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;			
		}
	}

}