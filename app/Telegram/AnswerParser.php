<?php

namespace App\Telegram;

class AnswerParser
{
    private const TOKENS_MAP = [
        Answer::LESS => 'yes|да',
        Answer::GREATER => 'no|нет',
        Answer::GUESS_OVER => '\/guess',
        Answer::HELP => '\/help',
    ];

    public static function parse(string $message): Answer
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
