<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 * @property int left
 * @property int right
 * @property int value
 * @property int chat_id
 * @property int id
 * @property boolean guessed
 */
class Guess extends Model
{
    protected $fillable = ['chat_id', 'value', 'left', 'right'];
}
