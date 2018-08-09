<?php
namespace diiimonn\modules\verification;

use Yii;

/**
 * Class Module
 * @package diiimonn\modules\verification
 */
class Module extends \yii\base\Module
{
    const TOKEN_TYPE_STRING = 0;
    const TOKEN_TYPE_INTEGER = 1;

    /**
     * Invalidate timeout in minutes
     *
     * @var int
     */
    public $invalidateTimeout = 1440;

    /**
     * @var int
     */
    public $tokenType = self::TOKEN_TYPE_STRING;

    /**
     * Count symbols
     *
     * @var int
     */
    public $tokenLength = 32;

    public function generateToken()
    {
        $token = '';

        switch($this->tokenType) {
            case self::TOKEN_TYPE_INTEGER:
                for($i = 0;$i < $this->tokenLength;$i++) {
                    $token .= mt_rand(0, 9);
                }

                break;
            case self::TOKEN_TYPE_STRING:
            default:
                $characters = 'abcdefghijklmnopqrstuvwxyz';

                for($i = 0;$i < $this->tokenLength;$i++) {
                    if (!mt_rand(0,1)) {
                        $token .= mt_rand(0, 9);
                    } else {
                        $token .= $characters{mt_rand(0, 9)};
                    }
                }

                break;
        }

        return $token;
    }
}