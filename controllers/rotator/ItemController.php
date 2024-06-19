<?php
/**
 * ItemController
 * @var $this ommu\banner\controllers\rotator\ItemController
 * @var $model ommu\banner\models\LinkRotatorItem
 *
 * ItemController implements the CRUD actions for LinkRotatorItem model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Create
 *  Update
 *  View
 *  Delete
 *  RunAction
 *  Publish
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 August 2021, 19:58 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\controllers\rotator;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\banner\models\LinkRotatorItem;
use ommu\banner\models\search\LinkRotatorItem as LinkRotatorItemSearch;

class ItemController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('category') || Yii::$app->request->get('id')) {
            $this->subMenu = $this->module->params['rotator_submenu'];
        }
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
        return [
            'access' => [
                'class' => AccessControl::className(),
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
					'publish' => ['POST'],
                ],
            ],
        ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
        return $this->redirect(['manage']);
	}

	/**
	 * Lists all LinkRotatorItem models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new LinkRotatorItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

        if (($category = Yii::$app->request->get('category')) != null) {
            $this->subMenuParam = $category;
            $category = \ommu\banner\models\LinkRotators::findOne($category);
        }

		$this->view->title = Yii::t('app', 'Rotator Items');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'category' => $category,
		]);
	}

	/**
	 * Creates a new LinkRotatorItem model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        if (($id = Yii::$app->request->get('id')) == null) {
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        $this->subMenuParam = $id;

        $model = new LinkRotatorItem([
            'cat_id' => $id,
        ]);

        if ($model->category->rotator_type == 'url') {
            $model->scenario = $model::SCENARIO_IS_LINKED;
        } else {
            $model->scenario = $model::SCENARIO_IS_NOT_LINKED;
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Link rotator item success created.'));
                if (Yii::$app->request->isAjax) {
                    return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'category' => $model->cat_id]);
                }
                return $this->redirect(['manage', 'category' => $model->cat_id]);
                //return $this->redirect(['view', 'id' => $model->banner_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Create Rotator Item');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing LinkRotatorItem model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
        $this->subMenuParam = $model->cat_id;

        if ($model->category->rotator_type == 'url') {
            $model->scenario = $model::SCENARIO_IS_LINKED;
        } else {
            $model->scenario = $model::SCENARIO_IS_NOT_LINKED;
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Link rotator item success updated.'));
                if (Yii::$app->request->isAjax) {
                    return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'category' => $model->cat_id]);
                }
                return $this->redirect(['manage', 'category' => $model->cat_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update Rotator Item: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single LinkRotatorItem model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);
        $this->subMenuParam = $model->cat_id;

		$this->view->title = Yii::t('app', 'Detail Rotator Item: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing LinkRotatorItem model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish','modified_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Link rotator item success deleted.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'category' => $model->cat_id]);
        }
	}

	/**
	 * actionPublish an existing LinkRotatorItem model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

        if ($model->save(false, ['publish','modified_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Link rotator item success updated.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'category' => $model->cat_id]);
        }
	}

	/**
	 * Finds the LinkRotatorItem model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return LinkRotatorItem the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        $model = LinkRotatorItem::find()
            ->alias('t')
            ->select(['t.*'])
            ->joinWith(['category category'])
            ->andWhere(['t.banner_id' => $id])
            ->andWhere(['t.is_banner' => 0])
            ->andWhere(['category.type' => 'rotator'])
            ->one();

        if ($model !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}