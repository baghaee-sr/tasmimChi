<?php

use app\models\Invoice;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use sadi01\bidashboard\widgets\ReportModalWidget;

/** @var yii\web\View $this */
/** @var app\models\search\InvoiceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Invoices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="row d-flex d-inline-flex">
    <p>
        <?= Html::a(Yii::t('app', 'Create Invoice'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
        <?= ReportModalWidget::widget([
            'queryParams' => $queryParams,
            'searchModel' => $searchModel,
            'searchModelMethod' => 'searchWidget',
            'searchRoute' => \Yii::$app->request->pathInfo,
            'searchModelFormName' => key(\Yii::$app->request->getQueryParams()),
            'outputColumn' => [
                "day" => "روز",
                "year"=> "سال",
                "month"=> "ماه",
                "total_count"=> "تعداد",
                "total_amount"=> "جمع‌کل"
            ],
        ]) ?>
    </p>
    <p>
        <a class="btn btn-primary"
           data-toggle="collapse"
           href="#collapseSearch"
           role="button"
           aria-expanded="false"
           aria-controls="collapseSearch">
            جستجو
        </a>
    </p>
</div>

<?php Pjax::begin(); ?>

<div class="collapse" id="collapseSearch">
    <div class="card card-body">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'title',
        'price',
        'created_at',
        'updated_at',
        [
            'class' => ActionColumn::class,
            'urlCreator' => function ($action, Invoice $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->id]);
            }
        ],
    ],
]); ?>

<?php Pjax::end(); ?>

</div>
