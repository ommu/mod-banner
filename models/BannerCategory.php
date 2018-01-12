<?php
/**
 * BannerCategory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-banner
 *
 * This is the model class for table "ommu_banner_category".
 *
 * The followings are the available columns in table 'ommu_banner_category':
 * @property integer $cat_id
 * @property integer $publish
 * @property string $name
 * @property string $desc
 * @property string $cat_code
 * @property string $banner_size
 * @property integer $banner_limit
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property Banners[] $banners
 * @property Users $creation;
 * @property Users $modified;
 */
class BannerCategory extends CActiveRecord
{
	public $defaultColumns = array();
	public $templateColumns = array();
	public $gridForbiddenColumn = array('desc_i','cat_code','banner_size','creation_date','creation_search','modified_date','modified_search','slug');
	public $name_i;
	public $desc_i;
	
	// Variable Search
	public $publish_search;
	public $pending_search;
	public $expired_search;
	public $unpublish_search;
	public $all_search;
	public $creation_search;
	public $modified_search;

	/**
	 * Behaviors for this model
	 */
	public function behaviors() 
	{
		return array(
			'sluggable' => array(
				'class'=>'ext.yii-behavior-sluggable.SluggableBehavior',
				'columns' => array('title.message'),
				'unique' => true,
				'update' => true,
			),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BannerCategory the static model class
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
		return $matches[1].'.ommu_banner_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('banner_limit,
				name_i, desc_i', 'required'),
			array('publish, banner_limit', 'numerical', 'integerOnly'=>true),
			array('banner_limit', 'length', 'max'=>2),
			array('name, desc, creation_id, modified_id', 'length', 'max'=>11),
			array('cat_code, slug,
				name_i', 'length', 'max'=>32),
			array('
				desc_i', 'length', 'max'=>64),
			array('cat_code, banner_size, banner_limit', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cat_id, publish, name, desc, cat_code, banner_size, banner_limit, creation_date, creation_id, modified_date, modified_id, slug, 
				name_i, desc_i, publish_search, pending_search, expired_search, unpublish_search, all_search, creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewBannerCategory', 'cat_id'),
			'title' => array(self::BELONGS_TO, 'SourceMessage', 'name'),
			'description' => array(self::BELONGS_TO, 'SourceMessage', 'desc'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'banners' => array(self::HAS_MANY, 'Banners', 'cat_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cat_id' => Yii::t('attribute', 'Category'),
			'publish' => Yii::t('attribute', 'Status'),
			'name' => Yii::t('attribute', 'Title'),
			'desc' => Yii::t('attribute', 'Description'),
			'cat_code' => Yii::t('attribute', 'Code'),
			'banner_size' => Yii::t('attribute', 'Size'),
			'banner_limit' => Yii::t('attribute', 'Limit'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'slug' => Yii::t('attribute', 'Slug'),
			'name_i' => Yii::t('attribute', 'Title'),
			'desc_i' => Yii::t('attribute', 'Description'),
			'publish_search' => Yii::t('attribute', 'Publish'),
			'pending_search' => Yii::t('attribute', 'Pending'),
			'expired_search' => Yii::t('attribute', 'Expired'),
			'unpublish_search' => Yii::t('attribute', 'Unpublish'),
			'all_search' => Yii::t('attribute', 'Total'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
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
			),
			'title' => array(
				'alias'=>'title',
				'select'=>'message',
			),
			'description' => array(
				'alias'=>'description',
				'select'=>'message',
			),
			'creation' => array(
				'alias'=>'creation',
				'select'=>'displayname'
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname'
			),
		);

		$criteria->compare('t.cat_id', $this->cat_id);
		if(isset($_GET['type']) && $_GET['type'] == 'publish')
			$criteria->compare('t.publish', 1);
		elseif(isset($_GET['type']) && $_GET['type'] == 'unpublish')
			$criteria->compare('t.publish', 0);
		elseif(isset($_GET['type']) && $_GET['type'] == 'trash')
			$criteria->compare('t.publish', 2);
		else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish', $this->publish);
		}
		$criteria->compare('t.name', $this->name);
		$criteria->compare('t.desc', $this->desc);
		$criteria->compare('t.cat_code', strtolower($this->cat_code), true);
		$criteria->compare('t.banner_size', $this->banner_size, true);
		$criteria->compare('t.banner_limit', $this->banner_limit);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		$criteria->compare('t.creation_id', isset($_GET['creation']) ? $_GET['creation'] : $this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', isset($_GET['modified']) ? $_GET['modified'] : $this->modified_id);
		$criteria->compare('t.slug', strtolower($this->slug), true);

		$criteria->compare('title.message', strtolower($this->name_i), true);
		$criteria->compare('description.message', strtolower($this->desc_i), true);
		$criteria->compare('view.banners', $this->publish_search);
		$criteria->compare('view.banner_pending', $this->pending_search);
		$criteria->compare('view.banner_expired', $this->expired_search);
		$criteria->compare('view.banner_unpublish', $this->unpublish_search);
		$criteria->compare('view.banner_all', $this->all_search);
		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);

		if(!isset($_GET['BannerCategory_sort']))
			$criteria->order = 't.cat_id DESC';

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
			$this->templateColumns['name_i'] = array(
				'name' => 'name_i',
				'value' => '$data->title->message',
			);
			$this->templateColumns['desc_i'] = array(
				'name' => 'desc_i',
				'value' => '$data->description->message',
			);
			$this->templateColumns['cat_code'] = array(
				'name' => 'cat_code',
				'value' => '$data->cat_code',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->templateColumns['banner_size'] = array(
				'name' => 'banner_size',
				'value' => 'BannerCategory::getPreviewSize($data->banner_size)',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->templateColumns['creation_date'] = array(
				'name' => 'creation_date',
				'value' => '!in_array($data->creation_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->creation_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
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
			if(!isset($_GET['creation'])) {
				$this->templateColumns['creation_search'] = array(
					'name' => 'creation_search',
					'value' => '$data->creation->displayname ? $data->creation->displayname : \'-\'',
				);
			}
			$this->templateColumns['modified_date'] = array(
				'name' => 'modified_date',
				'value' => '!in_array($data->modified_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->modified_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'modified_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'modified_date_filter',
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
			if(!isset($_GET['modified'])) {
				$this->templateColumns['modified_search'] = array(
					'name' => 'modified_search',
					'value' => '$data->modified->displayname ? $data->modified->displayname : \'-\'',
				);
			}
			$this->templateColumns['slug'] = array(
				'name' => 'slug',
				'value' => '$data->slug',
			);
			$this->templateColumns['banner_limit'] = array(
				'name' => 'banner_limit',
				'value' => '$data->banner_limit ? $data->banner_limit : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->templateColumns['publish_search'] = array(
				'name' => 'publish_search',
				'value' => '$data->view->banners > $data->banner_limit ? $data->banner_limit."/".$data->view->banners : $data->view->banners',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->templateColumns['pending_search'] = array(
				'name' => 'pending_search',
				'value' => '$data->view->banner_pending',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->templateColumns['expired_search'] = array(
				'name' => 'expired_search',
				'value' => '$data->view->banner_expired',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->templateColumns['unpublish_search'] = array(
				'name' => 'unpublish_search',
				'value' => '$data->view->banner_unpublish',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->templateColumns['all_search'] = array(
				'name' => 'all_search',
				'value' => '$data->view->banner_all',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			if(!isset($_GET['type'])) {
				$this->templateColumns['publish'] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'o/category/publish\',array(\'id\'=>$data->cat_id)), $data->publish)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Yii::t('phrase', 'Yes'),
						0=>Yii::t('phrase', 'No'),
					),
					'type' => 'raw',
				);
			}
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
	 * Get category
	 * 0 = unpublish
	 * 1 = publish
	 */
	public static function getCategory($publish=null, $type=null) 
	{
		$criteria=new CDbCriteria;
		if($publish != null)
			$criteria->compare('publish', $publish);
		
		$model = self::model()->findAll($criteria);

		if($type == null) {
			$items = array();
			if($model != null) {
				foreach($model as $key => $val) {
					$items[$val->cat_id] = $val->title->message;
				}
				return $items;
			} else
				return false;
		} else
			return $model;
	}

	/**
	 * BannerCategory get information
	 */
	public static function getPreviewSize($banner_size)
	{
		$bannerSize = unserialize($banner_size);
		return $bannerSize['width'].' x '.$bannerSize['height'];
	}

	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	protected function afterFind()
	{
		$this->name_i = $this->title->message;
		$this->desc_i = $this->description->message;
		
		parent::afterFind();
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : 0;
			else
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : 0;
			
			if($this->banner_size['width'] == '' || $this->banner_size['height'] == '')
				$this->addError('banner_size', Yii::t('phrase', 'Banner Size cannot be blank.'));
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		$module = strtolower(Yii::app()->controller->module->id);
		$controller = strtolower(Yii::app()->controller->id);
		$action = strtolower(Yii::app()->controller->action->id);

		$location = $module.' '.$controller;
		
		if(parent::beforeSave()) {
			if($this->isNewRecord || (!$this->isNewRecord && !$this->name)) {
				$name=new SourceMessage;
				$name->message = $this->name_i;
				$name->location = $location.'_title';
				if($name->save())
					$this->name = $name->id;
				
				$this->slug = Utility::getUrlTitle($this->name_i);
				
			} else {
				$name = SourceMessage::model()->findByPk($this->name);
				$name->message = $this->name_i;
				$name->save();
			}
			
			if($this->isNewRecord || (!$this->isNewRecord && !$this->desc)) {
				$desc=new SourceMessage;
				$desc->message = $this->desc_i;
				$desc->location = $location.'_description';
				if($desc->save())
					$this->desc = $desc->id;
				
			} else {
				$desc = SourceMessage::model()->findByPk($this->desc);
				$desc->message = $this->desc_i;
				$desc->save();
			}
			
			if($action != 'publish') {
				$this->cat_code = Utility::getUrlTitle(strtolower(trim($this->name_i)));
				//Banner Size
				$this->banner_size = serialize($this->banner_size);
			}
		}
		return true;
	}

}