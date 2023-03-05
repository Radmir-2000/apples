<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "apples".
 *
 * @property int $id
 * @property string $color
 * @property int $status
 * @property int $rest
 * @property string $birthdate
 * @property string $felldate
 */
class Apples extends \yii\db\ActiveRecord
{
    const STATUS_HANGING = 0;
    const STATUS_LYING = 1;
    const STATUS_ROTS = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apples';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'rest'], 'integer'],
            [['birthdate', 'felldate'], 'safe'],
            [['color'], 'string', 'max' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Код',
            'color' => 'Цвет',
            'status' => 'Статус',
            'rest' => 'Остаток',
            'birthdate' => 'Дата появления',
            'felldate' => 'Дата падения',
        ];
    }

    private function setColor()
    {
        $green = mt_rand(0x66, 0xFF);
        $red = $green < 0x99 ? mt_rand(0xBB, 0xFF) : mt_rand(0x66, 0xFF);
        $this->color = sprintf('%02X', $red)
            . sprintf('%02X', $green)
            . sprintf('%02X', mt_rand(0, 0x33));
    }

    private function setBirthDate()
    {
        $this->birthdate = date('Y-m-d H:i:s', strtotime('-' . mt_rand(0, 300) . ' SECONDS'));
    }

    public static function create()
    {
        $apple = new self();
        $apple->setColor();
        $apple->setBirthDate();
        $apple->save();

        return $apple;
    }

    public function eat($percent)
    {

    }

    public function fall()
    {

    }
}
