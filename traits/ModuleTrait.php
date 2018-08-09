<?php
namespace diiimonn\modules\verification\traits;

use diiimonn\modules\verification\Module;

/**
 * Class ModuleTrait
 * @package diiimonn\modules\verification\traits
 */
trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('verification');
    }
}