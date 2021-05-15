<?php

namespace App\Telegram;

use App\Models\Guess;

/**
 * Determines guessed number, using binary search algorithm
 * (@link https://leetcode.com/explore/learn/card/binary-search/135/template-iii/936/}
 */
class Guesser
{
    private int $valueMin;

    private int $valueMax;

    public function __construct(int $valueMin, int $valueMax)
    {
        $this->valueMin = $valueMin;
        $this->valueMax = $valueMax;
    }

    public function guess(int $chatId, Answer $answer): Guess
    {
        if ($answer->equals(Answer::START())) {
            return $this->create($chatId);
        }

        $guess = Guess::where('chat_id', $chatId)->where('guessed', false)->firstOrFail();

        if ($answer->equals(Answer::GREATER())) {
            $guess->left = $guess->value;
        } else {
            $guess->right = $guess->value;
        }

        if ($guess->left + 1 >= $guess->right) {
            $guess->guessed = true;
            $guess->value = $guess->left;
        } else {
            $guess->value = $this->getMiddle($guess->left, $guess->right);
        }

        $guess->save();

        return $guess;
    }

    private function create(int $chatId): Guess
    {
        $left = $this->valueMin - 1;
        $right = $this->valueMax + 1;

        Guess::where('chat_id', $chatId)->where('guessed', false)->update(['guessed' => true]);

        return Guess::create([
            'chat_id' => $chatId,
            'guessed' => false,
            'value' => $this->getMiddle($left, $right),
            'left' => $left,
            'right' => $right,
        ]);
    }

    private function getMiddle(int $left, int $right): int
    {
        return $left + round(($right - $left) / 2);
    }
}
