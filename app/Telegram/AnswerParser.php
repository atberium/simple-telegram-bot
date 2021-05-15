<?php

namespace App\Telegram;

/**
 * Parser of user message received from Telegram. Gets first word and tries to determine, what user wanted to say
 */
class AnswerParser
{
    private const TOKENS_MAP = [
        Answer::LESS => 'yes|да|\+|д+|yep|y',
        Answer::GREATER => 'no|нет|-|н|not|n',
        Answer::START => '\/start',
        Answer::HELP => '\/help',
    ];

    public function parse(string $message): Answer
    {
        $trimmed = ltrim($message);

        foreach (self::TOKENS_MAP as $answer => $regexp) {
            if (preg_match("/^($regexp)(\s|$)/iu", $trimmed)) {
                return new Answer($answer);
            }
        }

        return Answer::UNKNOWN();
    }
}
