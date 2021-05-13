<?php

namespace App\Telegram;

use App\Models\Guess;
use Closure;

class Guesser
{
    /**
     * @var Closure[]
     */
    private array $strategies;

    private int $valueMin;

    private int $valueMax;

    public function __construct(int $valueMin, int $valueMax)
    {
        $this->valueMin = $valueMin;
        $this->valueMax = $valueMax;

        $this->strategies = [
            Answer::LESS => fn(Guess $guess) => $guess->right = $guess->value - 1,
            Answer::GREATER => fn(Guess $guess) => $guess->left = $guess->value + 1,
        ];
    }

    public function guess(int $chatId, Answer $answer): Guess
    {
        if (isset($this->strategies[$answer->getValue()])) {
            return $this->createOrUpdateGuess($chatId, $this->strategies[$answer->getValue()]);
        }

        return $this->create($chatId);
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

    private function createOrUpdateGuess(int $chatId, callable $strategy): Guess
    {
        $guess = Guess::where('chat_id', $chatId)->where('guessed', false)->firstOrFail();
        $strategy($guess);
        $guess->value = $this->getMiddle($guess->left, $guess->right);

        if ($guess->left >= $guess->right) {
            $guess->guessed = true;
        }

        $guess->save();

        return $guess;
    }

    private function getMiddle(int $left, int $right): int
    {
        return $left + round(($right - $left) / 2);
    }
}
