<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "column".
 *
 * @property string $name
 * @property string $type
 * @property int $table_id
 *
 * @property Table $table
 */
class Column extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'column';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'table_id'], 'required'],
            [['table_id'], 'default', 'value' => null],
            [['table_id'], 'integer'],
            [['name', 'type'], 'string', 'max' => 50],
            [['table_id'], 'exist', 'skipOnError' => true, 'targetClass' => Table::className(), 'targetAttribute' => ['table_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'table_id' => 'Table ID',
        ];
    }

    /**
     * Gets query for [[Table]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTable()
    {
        return $this->hasOne(Table::className(), ['id' => 'table_id']);
    }
}
