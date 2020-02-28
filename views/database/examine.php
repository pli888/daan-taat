<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Database */
/* @var $table_names app\models\Database */
/* @var $tables app\models\Database */



$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Databases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="database-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'host',
            'port',
            'username',
            'password',
        ],
    ]) ?>

    <h1>Tables:</h1>
    <ul>
        <?
            foreach ($table_names as $table_name) {
                print "<li> $table_name";
                print "<ul>";
                foreach ($tables[$table_name] as $col) {
                    print "<li> $col[0], $col[1]";
                }
                print "</ul>";
            }
        ?>
    </ul>
</div>
