<?php

namespace Jasonej\EloquentTokens\Tests\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jasonej\EloquentTokens\Concerns\IsToken;

/**
 * Class Token
 * @package Jasonej\EloquentTokens\Tests\Models
 * @property Carbon|null claimed_at
 * @property Carbon created_at
 * @property Carbon|null expires_at
 * @property int id
 * @property string selector
 * @property Carbon updated_at
 * @property string verifier
 */
class Token extends Model
{
    use IsToken;

    /** @inheritDoc */
    protected $guarded = [];
}