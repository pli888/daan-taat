<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "database".
 *
 * @property int $id
 * @property string $name
 * @property string $host
 * @property string $port
 * @property string $username
 * @property string $password
 * @property bool $imported
 *
 * @property Table[] $tables
 */
class Database extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'database';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'host', 'port', 'username', 'password', 'imported'], 'required'],
            [['imported'], 'boolean'],
            [['name', 'host', 'port', 'username', 'password'], 'string', 'max' => 50],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'host' => 'Host',
            'port' => 'Port',
            'username' => 'Username',
            'password' => 'Password',
            'imported' => 'Imported',
        ];
    }

    /**
     * Gets query for [[Tables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTables()
    {
        return $this->hasMany(Table::className(), ['database_id' => 'id']);
    }
}
