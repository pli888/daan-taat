<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table".
 *
 * @property int $id
 * @property int $database_id
 * @property string $name
 *
 * @property Column[] $columns
 * @property Database $database
 */
class Table extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['database_id', 'name'], 'required'],
            [['database_id'], 'default', 'value' => null],
            [['database_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['database_id'], 'exist', 'skipOnError' => true, 'targetClass' => Database::className(), 'targetAttribute' => ['database_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'database_id' => 'Database ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Columns]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getColumns()
    {
        return $this->hasMany(Column::className(), ['table_id' => 'id']);
    }

    /**
     * Gets query for [[Database]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDatabase()
    {
        return $this->hasOne(Database::className(), ['id' => 'database_id']);
    }

    /**
     * Query tables in [[Database]].
     *
     * @param $database_id
     * @return array of table names
     */
    public function queryTablesInDatabase($database_id)
    {
        $tab_names = array();  // For storing table names
        $db1_ht = array();     // For storing columns for each table

        // SQL for querying table names
        $sql1 = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name";

        // Get database id based on database name and query table names
        $database = Database::findOne($database_id);
        if ($database->name == "db1")
            $rows = Yii::$app->db1->createCommand($sql1)->queryAll();
        else
            $rows = Yii::$app->db2->createCommand($sql1)->queryAll();

        // Extract table names into an array
        foreach ($rows as $row)
            $tab_names[] = $row['table_name'];

        // For each table, get their column names
        foreach ($tab_names as $tab_name) {
            if ($database->name == "db1")
                $columns = Yii::$app->db1->createCommand("SELECT column_name, data_type FROM information_schema.columns WHERE table_schema = 'public' AND table_name = '$tab_name'")->queryAll();
            else
                $columns = Yii::$app->db2->createCommand("SELECT column_name, data_type FROM information_schema.columns WHERE table_schema = 'public' AND table_name = '$tab_name'")->queryAll();

            Yii::error($columns, 'database_import');
            $col_names = array();
            foreach ($columns as $column) {
                $col_names[] = [$column['column_name'], $column['data_type']];
            }
            $db1_ht[$tab_name] = $col_names; // Add entry
        }

        return $db1_ht;
    }

    /**
     * Import tables into application
     *
     * @return \yii\db\ActiveQuery
     */
    public function import($database_id)
    {
        $db1_ht = $this->queryTablesInDatabase($database_id);

        $tab_names = array_keys($db1_ht);

        // Update database with table names
        foreach ($tab_names as $tab_name) {
            $table = new Table();
            $table->database_id = $database_id;
            $table->name = $tab_name;
            $table->save();
            foreach ($db1_ht[$tab_name] as $col) {
                $column = new Column();
                $column->name = $col[0];
                $column->type = $col[1];
                $column->save();
            }
        }

        // Update imported column to true
        $database = Database::findOne($database_id);
        $database->imported = TRUE;
        $database->save();

        return $this->hasOne(Database::className(), ['id' => 'database_id']);
    }
}
