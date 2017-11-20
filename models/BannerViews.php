<?php
/**
 * BannerViews
 * version: 1.3.0
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 8 January 2017, 19:18 WIB
 * @link https://github.com/ommu/mod-banner
 * @contact (+62)856-299-4114
 *
 * This is the model class for table "ommu_banner_views".
 *
 * The followings are the available columns in table 'ommu_banner_views':
 * @property string $view_id
 * @property string $banner_id
 * @property string $user_id
 * @property integer $views
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property BannerViewHistory[] $histories
 * @property Banners $banner
 * @property Users $user;
 */
class BannerViews extends CActiveRecord
{
	public $defaultColumns = array();
	public $templateColumns = array();
	public $gridForbiddenColumn = array();

	// Variable Search
	public $category_search;
	public $banner_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BannerViews the static model class
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
		return $matches[1].'.ommu_banner_views';
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
			array('views', 'numerical', 'integerOnly'=>true),
			array('banner_id, user_id', 'length', 'max'=>11),
			array('view_ip', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('view_id, banner_id, user_id, views, view_date, view_ip,
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
			'histories' => array(self::HAS_MANY, 'BannerViewHistory', 'view_id'),
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
			'view_id' => Yii::t('attribute', 'View'),
			'banner_id' => Yii::t('attribute', 'Banner'),
			'user_id' => Yii::t('attribute', 'User'),
			'views' => Yii::t('attribute', 'Views'),
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
			'banner' => array(
				'alias'=>'banner',
				'select'=>'publish, cat_id, title'
			),
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname'
			),
		);
		
		$criteria->compare('t.view_id', $this->view_id);
		$criteria->compare('t.banner_id', isset($_GET['banner']) ? $_GET['banner'] : $this->banner_id);
		$criteria->compare('t.user_id', isset($_GET['user']) ? $_GET['user'] : $this->user_id);
		$criteria->compare('t.views', $this->views);
		if($this->view_date != null && !in_array($this->view_date, array('0000-00-00 00:00:00', '1970-01-01 00:00:00')))
			$criteria->compare('date(t.view_date)', date('Y-m-d', strtotime($this->view_date)));
		$criteria->compare('t.view_ip', strtolower($this->view_ip), true);

		$criteria->compare('banner.cat_id', $this->category_search);
		$criteria->compare('banner.title', strtolower($this->banner_search), true);
		if(isset($_GET['banner']) && isset($_GET['publish']))
			$criteria->compare('banner.publish', $_GET['publish']);
		$criteria->compare('user.displayname',strtolower($this->user_search), true);

		if(!isset($_GET['BannerViews_sort']))
			$criteria->order = 't.view_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}

	/**
	 * Get kolom untuk Grid View
	 *
	 * @param array $columns kolom dari view
	 * @return array dari grid yang aktif
	 */
	public function getGridColumn($columns=null) 
	{
		// Jika $columns kosong maka isi defaultColumns dg templateColumns
		if(empty($columns) || $columns == null) {
			array_splice($this->defaultColumns, 0);
			foreach($this->templateColumns as $key => $val) {
				if(!in_array($key, $this->gridForbiddenColumn) && !in_array($key, $this->defaultColumns))
					$this->defaultColumns[] = $val;
			}
			return $this->defaultColumns;
		}

		foreach($columns as $val) {
			if(!in_array($val, $this->gridForbiddenColumn) && !in_array($val, $this->defaultColumns)) {
				$col = $this->getTemplateColumn($val);
				if($col != null)
					$this->defaultColumns[] = $col;
			}
		}

		array_unshift($this->defaultColumns, array(
			'header' => Yii::t('app', 'No'),
			'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
			'htmlOptions' => array(
				'class' => 'center',
			),
		));

		array_unshift($this->defaultColumns, array(
			'class' => 'CCheckBoxColumn',
			'name' => 'id',
			'selectableRows' => 2,
			'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
		));

		return $this->defaultColumns;
	}

	/**
	 * Get kolom template berdasarkan id pengenal
	 *
	 * @param string $name nama pengenal
	 * @return mixed
	 */
	public function getTemplateColumn($name) 
	{
		$data = null;
		if(trim($name) == '') return $data;

		foreach($this->templateColumns as $key => $item) {
			if($name == $key) {
				$data = $item;
				break;
			}
		}
		return $data;
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
			if(!isset($_GET['banner'])) {
				$this->templateColumns['category_search'] = array(
					'name' => 'category_search',
					'value' => '$data->banner->category->title->message',
					'filter'=> BannerCategory::getCategory(),
					'type' => 'raw',
				);
				$this->templateColumns['banner_search'] = array(
					'name' => 'banner_search',
					'value' => '$data->banner->title',
				);
			}
			if(!isset($_GET['user'])) {
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->user->displayname ? $data->user->displayname : \'-\'',
				);
			}
			$this->templateColumns['view_date'] = array(
				'name' => 'view_date',
				'value' => '!in_array($data->view_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->view_date, true) : \'-\'',
				'htmlOptions' => array(
					//'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'view_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'view_date_filter',
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
			$this->templateColumns['view_ip'] = array(
				'name' => 'view_ip',
				'value' => '$data->view_ip',
				'htmlOptions' => array(
					//'class' => 'center',
				),
			);
			$this->templateColumns['views'] = array(
				'name' => 'views',
				'value' => 'CHtml::link($data->views, Yii::app()->controller->createUrl("history/view/manage",array(\'view\'=>$data->view_id)))',
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
	 * User get information
	 */
	public static function insertView($banner_id)
	{
		$criteria=new CDbCriteria;
		$criteria->select = 'view_id, banner_id, user_id, views';
		$criteria->compare('banner_id', $banner_id);
		$criteria->compare('user_id', !Yii::app()->user->isGuest ? Yii::app()->user->id : '0');
		$findView = self::model()->find($criteria);
		
		if($findView != null)
			self::model()->updateByPk($findView->view_id, array('views'=>$findView->views + 1, 'view_ip'=>$_SERVER['REMOTE_ADDR']));
		
		else {
			$view=new BannerViews;
			$view->banner_id = $banner_id;
			$view->save();
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : 0;
			
			$this->view_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}

}