<?php

namespace App\Telegram;

use MyCLabs\Enum\Enum;

/**
 * User answer received from Telegram client, interpolated to reserved ones
 * @method static Answer START()
 * @method static Answer LESS()
 * @method static Answer GREATER()
 * @method static Answer UNKNOWN()
 * @method static Answer HELP()
 */
class Answer extends Enum
{
    // Start guessing or restart
    public const START = 'start';

    // Help command
    public const HELP = 'help';

    // User answered, that his number is less, then we suggested
    public const LESS = 'less';

    // User answered, that his number is greater, then we suggested
    public const GREATER = 'greater';

    // We didn't recognize, what user wanted to say
    public const UNKNOWN = 'unknown';

    /**
     * Answer using in guessing algorithm
     */
    public function isGuessing(): bool
    {
        return $this->equals(self::START()) || $this->equals(self::LESS()) || $this->equals(self::GREATER());
    }
}
