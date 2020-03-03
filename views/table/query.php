<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $db1_ht array of string table names */
/* @var $tables_imported boolean to check if database has been imported yet */
/* @var $database_id */


$this->title = 'Tables';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        if ($tables_imported == FALSE) {
            echo Html::a('Import Tables', ['import', 'database_id' => $database_id], ['class' => 'btn btn-primary']);
            echo "<hr>";
        }
    ?>

    <?
        $tab_names = array_keys($db1_ht);
        foreach ($tab_names as $tab_name) {
            print "<div class='panel panel-primary'>";
            print "<div class='panel-heading'>$tab_name</div>";
            print "<table class='table'>";
            foreach ($db1_ht[$tab_name] as $col) {
                print "<tr>";
                print "<td>$col[0]</td>";
                print "<td>$col[1]</td>";
                print "</tr>";
            }
            print "</table>";
            print "</div>";
        }
        ?>
</div>
