<?php

namespace Jasonej\EloquentTokens\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Jasonej\EloquentTokens\Concerns\IsToken;

class ModifiedToken extends Model
{
    use IsToken;

    const CLAIMED_AT_COLUMN_NAME = 'claimed';
    const EXPIRES_AT_COLUMN_NAME = 'expires';
    const SELECTOR_COLUMN_NAME = 'public_segment';
    const SELECTOR_COLUMN_SIZE = 8;
    const VERIFIER_COLUMN_NAME = 'secret_segment';
    const VERIFIER_COLUMN_SIZE = 8;
}