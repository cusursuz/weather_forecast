<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Weather;
use frontend\models\ForecastSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ForecastController implements the CRUD actions for Weather model.
 */
class ForecastController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Weather models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ForecastSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $request = Yii::$app->getRequest();
        $date['createdFrom'] = $request->getQueryParam('createdFrom');
        $date['createdTo'] = $request->getQueryParam('createdTo');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'date' => $date,
        ]);
    }

    /**
     * Displays a single Weather model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionHistory(): string
    {
        $searchModel = new ForecastSearch();
        $dataProvider = $searchModel->searchHistory($this->request->queryParams);

        $request = Yii::$app->getRequest();
        $date['createdFrom'] = $request->getQueryParam('createdFrom');
        $date['createdTo'] = $request->getQueryParam('createdTo');

        $this->view->title = 'Statistics';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'date' => $date,
        ]);
    }

    /**
     * Finds the Weather model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Weather the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Weather::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
