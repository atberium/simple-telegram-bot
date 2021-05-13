<?php

namespace App\Telegram;

use MyCLabs\Enum\Enum;

/**
 * @method static Answer GUESS_OVER()
 * @method static Answer LESS()
 * @method static Answer GREATER()
 * @method static Answer UNKNOWN()
 * @method static Answer HELP()
 */
class Answer  extends Enum
{
    public const GUESS_OVER = 'guessOver';
    public const HELP = 'help';
    public const LESS = 'less';
    public const GREATER = 'greater';
    public const UNKNOWN = 'unknown';

    public function isGuessing(): bool
    {
        return $this->equals(self::GUESS_OVER()) || $this->equals(self::LESS()) || $this->equals(self::GREATER());
    }
}
