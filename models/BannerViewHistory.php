<?php
/**
 * BannerViewHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 8 January 2017, 19:18 WIB
 * @modified date 19 January 2018, 17:03 WIB
 * @link https://github.com/ommu/mod-banner
 *
 * This is the model class for table "ommu_banner_view_history".
 *
 * The followings are the available columns in table 'ommu_banner_view_history':
 * @property string $id
 * @property string $view_id
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property BannerViews $view
 */
class BannerViewHistory extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	// Variable Search
	public $category_search;
	public $banner_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BannerViewHistory the static model class
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
		return $matches[1].'.ommu_banner_view_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('view_id, view_ip', 'required'),
			array('view_id', 'length', 'max'=>11),
			array('view_ip', 'length', 'max'=>20),
			array('view_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, view_id, view_date, view_ip,
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
			'view' => array(self::BELONGS_TO, 'BannerViews', 'view_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'view_id' => Yii::t('attribute', 'View'),
			'view_date' => Yii::t('attribute', 'View Date'),
			'view_ip' => Yii::t('attribute', 'View Ip'),
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
			'view' => array(
				'alias'=>'view',
				'select'=>'banner_id, user_id'
			),
			'view.banner' => array(
				'alias'=>'view_banner',
				'select'=>'cat_id, title'
			),
			'view.user' => array(
				'alias'=>'view_user',
				'select'=>'displayname'
			),
		);
		
		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.view_id', Yii::app()->getRequest()->getParam('view') ? Yii::app()->getRequest()->getParam('view') : $this->view_id);
		if($this->view_date != null && !in_array($this->view_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.view_date)', date('Y-m-d', strtotime($this->view_date)));
		$criteria->compare('t.view_ip', strtolower($this->view_ip), true);

		$criteria->compare('view_banner.cat_id', $this->category_search);
		$criteria->compare('view_banner.title', strtolower($this->banner_search), true);
		$criteria->compare('view_user.displayname', strtolower($this->user_search), true);

		if(!Yii::app()->getRequest()->getParam('BannerViewHistory_sort'))
			$criteria->order = 't.id DESC';

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
			if(!Yii::app()->getRequest()->getParam('view')) {
				$this->templateColumns['category_search'] = array(
					'name' => 'category_search',
					'value' => '$data->view->banner->category->title->message',
					'filter'=> BannerCategory::getCategory(),
					'type' => 'raw',
				);
				$this->templateColumns['banner_search'] = array(
					'name' => 'banner_search',
					'value' => '$data->view->banner->title',
				);
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->view->user->displayname ? $data->view->user->displayname : \'-\'',
				);
			}
			$this->templateColumns['view_date'] = array(
				'name' => 'view_date',
				'value' => '!in_array($data->view_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Utility::dateFormat($data->view_date) : \'-\'',
				'htmlOptions' => array(
					//'class' => 'center',
				),
				'filter' => 'native-datepicker',
				/*
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'view_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'view_date_filter',
						'on_datepicker' => 'on',
						'placeholder' => Yii::t('phrase', 'filter'),
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'yy-mm-dd',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
				*/
			);
			$this->templateColumns['view_ip'] = array(
				'name' => 'view_ip',
				'value' => '$data->view_ip',
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