<?php
/**
 * BannerClicks
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 8 January 2017, 19:18 WIB
 * @modified date 19 January 2018, 17:03 WIB
 * @link https://github.com/ommu/ommu-banner
 *
 * This is the model class for table "ommu_banner_clicks".
 *
 * The followings are the available columns in table 'ommu_banner_clicks':
 * @property string $click_id
 * @property string $banner_id
 * @property string $user_id
 * @property integer $clicks
 * @property string $click_date
 * @property string $click_ip
 *
 * The followings are the available model relations:
 * @property BannerClickHistory[] $histories
 * @property Banners $banner
 * @property Users $user;
 */
class BannerClicks extends OActiveRecord
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
	 * @return BannerClicks the static model class
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
		return $matches[1].'.ommu_banner_clicks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('banner_id, user_id', 'required'),
			array('clicks', 'numerical', 'integerOnly'=>true),
			array('banner_id, user_id', 'length', 'max'=>11),
			array('click_ip', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('click_id, banner_id, user_id, clicks, click_date, click_ip,
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
			'histories' => array(self::HAS_MANY, 'BannerClickHistory', 'click_id'),
			'banner' => array(self::BELONGS_TO, 'Banners', 'banner_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'click_id' => Yii::t('attribute', 'Click'),
			'banner_id' => Yii::t('attribute', 'Banner'),
			'user_id' => Yii::t('attribute', 'User'),
			'clicks' => Yii::t('attribute', 'Clicks'),
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
			'banner' => array(
				'alias'=>'banner',
				'select'=>'publish, cat_id, title'
			),
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname',
			),
		);
		
		$criteria->compare('t.click_id', $this->click_id);
		$criteria->compare('t.banner_id', Yii::app()->getRequest()->getParam('banner') ? Yii::app()->getRequest()->getParam('banner') : $this->banner_id);
		$criteria->compare('t.user_id', Yii::app()->getRequest()->getParam('user') ? Yii::app()->getRequest()->getParam('user') : $this->user_id);
		$criteria->compare('t.clicks', $this->clicks);
		if($this->click_date != null && !in_array($this->click_date, array('0000-00-00 00:00:00', '1970-01-01 00:00:00')))
			$criteria->compare('date(t.click_date)', date('Y-m-d', strtotime($this->click_date)));
		$criteria->compare('t.click_ip', strtolower($this->click_ip), true);

		$criteria->compare('banner.cat_id', $this->category_search);
		$criteria->compare('banner.title', strtolower($this->banner_search), true);
		if(Yii::app()->getRequest()->getParam('banner') && Yii::app()->getRequest()->getParam('publish'))
			$criteria->compare('banner.publish', Yii::app()->getRequest()->getParam('publish'));
		$criteria->compare('user.displayname', strtolower($this->user_search), true);

		if(!Yii::app()->getRequest()->getParam('BannerClicks_sort'))
			$criteria->order = 't.click_id DESC';

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
			if(!Yii::app()->getRequest()->getParam('banner')) {
				$this->templateColumns['category_search'] = array(
					'name' => 'category_search',
					'value' => '$data->banner->category->title->message',
					'filter'=> BannerCategory::getCategory(),
					'type' => 'raw',
				);
				$this->templateColumns['banner_search'] = array(
					'name' => 'banner_search',
					'value' => '$data->banner->title ? $data->banner->title : \'-\'',
				);
			}
			if(!Yii::app()->getRequest()->getParam('user')) {
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->user->displayname ? $data->user->displayname : \'-\'',
				);
			}
			$this->templateColumns['click_date'] = array(
				'name' => 'click_date',
				'value' => '!in_array($data->click_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->click_date, true) : \'-\'',
				'htmlOptions' => array(
					//'class' => 'center',
				),
				'filter' => 'native-datepicker',
				/*
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
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
				*/
			);
			$this->templateColumns['click_ip'] = array(
				'name' => 'click_ip',
				'value' => '$data->click_ip',
				'htmlOptions' => array(
					//'class' => 'center',
				),
			);
			$this->templateColumns['clicks'] = array(
				'name' => 'clicks',
				'value' => 'CHtml::link($data->clicks, Yii::app()->controller->createUrl("history/click/manage",array(\'click\'=>$data->click_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
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

	/**
	 * insertClick
	 */
	public static function insertClick($banner_id)
	{
		$criteria=new CDbCriteria;
		$criteria->select = 'click_id, banner_id, user_id, clicks';
		$criteria->compare('banner_id', $banner_id);
		$criteria->compare('user_id', !Yii::app()->user->isGuest ? Yii::app()->user->id : '0');
		$findClick = self::model()->find($criteria);
		
		if($findClick != null)
			self::model()->updateByPk($findClick->click_id, array('clicks'=>$findClick->clicks + 1, 'click_ip'=>$_SERVER['REMOTE_ADDR']));
		
		else {
			$click=new BannerClicks;
			$click->banner_id = $banner_id;
			$click->save();
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : 0;
			
			$this->click_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}

}