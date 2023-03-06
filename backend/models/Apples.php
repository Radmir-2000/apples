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
 * @property int $wormy
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
            [['status', 'rest', 'wormy'], 'integer'],
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
            'wormy' => 'Червивое',
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
        $this->birthdate = date('Y-m-d H:i:s', strtotime('-' . mt_rand(0, 300) . ' MINUTES'));
    }

    private function setWormy()
    {
        $this->wormy = mt_rand(0, 100) <= 20 ? 1 : 0;
    }

    public function getStatusTitle()
    {
        $result = 'Висит';
        $rots = '';
        $wormy = '';
        if ($this->status > 0) {
            $result = 'Лежит';

            if ($this->felldate < date('Y-m-d H:i:s', strtotime('-5 HOURS'))) {
                $rots = ' и начало гнить';
            }
        }
        if ($this->wormy == 1) {
            $wormy = ($rots ? ', да ещё и ' : ', но ') . 'червивое';
        }

        return $result . $rots . $wormy;
    }

    public static function create()
    {
        $apple = new self();
        $apple->setColor();
        $apple->setBirthDate();
        $apple->setWormy();
        $apple->save();

        return $apple;
    }

    public function eat($percent)
    {
        if ($this->rest < $percent) {
            return 'Нельзя скушать больше, чем есть';
        }
        if ($this->status == 0) {
            return 'Яблоко необходимо сначала сорвать';
        }
        if (($this->status == 1) && ($this->felldate < date('Y-m-d H:i:s', strtotime('-5 HOURS')))) {
            return 'Яблоко уже начало гнить';
        }
        if ($this->wormy == 1) {
            return 'Яблоко червивое';
        }

        $this->rest = $this->rest - $percent;
        $this->rest <= 0 ? $this->delete() : $this->save();

        return '';
    }

    public function fall()
    {
        if ($this->status > 0) {
            if ($this->felldate) {
                return 'Яблоко уже сорвано';
            } else {
                return 'Яблоко уже упало';
            }
        }

        $this->status = 1;
        $this->felldate = date('Y-m-d H:i:s');
        $this->save();

        return '';
    }
}
