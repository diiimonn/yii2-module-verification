<?php
namespace diiimonn\modules\verification\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use diiimonn\modules\verification\traits\ModuleTrait;

/**
 * Class Verification
 * @package diiimonn\modules\verification\models
 *
 * @property string $email
 * @property string $token
 * @property integer $invalidate_at
 *
 * @property \diiimonn\modules\verification\Module $module
 */
class Verification extends ActiveRecord
{
    use ModuleTrait;

    const SCENARIO_GENERATE = 'generate';
    const SCENARIO_CHECK = 'check';
    const SCENARIO_INVALIDATE = 'invalidate';

    public static function tableName()
    {
        return 'verification';
    }

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['token'], 'required', 'on' => [self::SCENARIO_CHECK, self::SCENARIO_DEFAULT]],
            [['invalidate_at'], 'required', 'on' => self::SCENARIO_DEFAULT],

            [['invalidate_at'], 'integer'],
            [['token', 'email'], 'string', 'max' => 255],

            [['email'], 'email'],
            [['email'], 'unique', 'on' => self::SCENARIO_GENERATE, 'skipOnError' => true],
            [['email'], 'exist', 'skipOnError' => true, 'on' => [self::SCENARIO_CHECK, self::SCENARIO_INVALIDATE]],
            [['email'], 'validatorUser', 'skipOnError' => true, 'on' => [self::SCENARIO_DEFAULT, self::SCENARIO_GENERATE]],
            [['token'], 'match', 'pattern' => '~^[a-z\d]+$~i'],
        ];
    }

    public function validatorUser($attribute)
    {
        if (!$this->hasErrors()) {
            /** @var \yii\db\ActiveRecord $identityClass */
            $identityClass = new Yii::$app->user->identityClass();
            $query = $identityClass->find();
            $query->where(['email' => $this->email]);

            if (!$query->count()) {
                $this->addError($attribute, Yii::t('app', 'User not found.'));
            }
        }
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_GENERATE => ['email'],
            self::SCENARIO_CHECK => ['email', 'token'],
            self::SCENARIO_INVALIDATE => ['email'],
        ]);
    }

    /**
     * @return bool
     */
    public function generate(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $model = clone $this;
        $model->scenario = self::SCENARIO_DEFAULT;
        $model->token = $this->module->generateToken();
        $model->invalidate_at = time() + $this->module->invalidateTimeout;

        //TODO: send toke to email

        return $model->save();
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $model = self::findOne($this->email);

        return strcmp($model->token, $this->token) === 0 && $model->invalidate_at > time();
    }

    /**
     * @return bool
     */
    public function invalidate(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        return !!self::deleteAll([
            'email' => $this->email
        ]);
    }
}