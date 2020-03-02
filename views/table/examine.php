<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $table_names array of string table names */

$this->title = 'Tables';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Table', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'database_id',
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <p>
        <?= Html::a('Query tables in database 1', ['query'], ['class' => 'btn btn-success']) ?>
    </p>

    <?
    foreach ($table_names as $table_name) {
        print "<div class='panel panel-primary'>";
        print "<div class='panel-heading'>$table_name</div>";
        print "<table class='table'>";
//            foreach ($tables[$table_name] as $col) {
//                print "<tr>";
//                print "<td>$col[0]</td>";
//                print "<td>$col[1]</td>";
//                print "</tr>";
//            }
        print "</table>";
        print "</div>";
    }
    ?>


</div>
