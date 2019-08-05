<?php

namespace Jasonej\EloquentTokens\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait IsToken
{
    /** @var string */
    public $rawVerifierValue;

    public static function bootIsToken(): void
    {
        static::creating(function (self $model) {
            $model->rawVerifierValue = Str::random($model->getVerifierColumnSize());

            $model->forceFill([
                $model->getSelectorColumnName() => Str::random($model->getSelectorColumnSize()),
                $model->getVerifierColumnName() => $model->hashVerifierValue($model->rawVerifierValue)
            ]);
        });
    }

    public function checkVerifierValue(string $value, string $hashedValue): bool
    {
        return Hash::check($value, $hashedValue);
    }

    public function findByTokenValue(string $value): ?self
    {
        $selector = Str::substr($value, 0, $this->getSelectorColumnSize());
        $verifier = Str::substr($value, $this->getSelectorColumnSize());

        return static::query()
            ->whereNull($this->getClaimedAtColumnName())
            ->where(function (Builder $builder) {
                return $builder->where($this->getExpiresAtColumnName(), '>', Carbon::now())
                    ->orWhereNull($this->getExpiresAtColumnName());
            })
            ->where($this->getSelectorColumnName(), '=', $selector)
            ->get()
            ->first(function (self $model) use ($verifier) {
                return $model->checkVerifierValue($verifier, $model->verifier);
            });
    }

    public function getClaimedAtColumnName(): string
    {
        return defined(static::class . '::CLAIMED_AT_COLUMN_NAME')
            ? static::CLAIMED_AT_COLUMN_NAME
            : 'claimed_at';
    }

    public function getExpiresAtColumnName(): string
    {
        return defined(static::class . '::EXPIRES_AT_COLUMN_NAME')
            ? static::EXPIRES_AT_COLUMN_NAME
            : 'expires_at';
    }

    public function getSelectorColumnName(): string
    {
        return defined(static::class . '::SELECTOR_COLUMN_NAME')
            ? static::SELECTOR_COLUMN_NAME
            : 'selector';
    }

    public function getSelectorColumnSize(): int
    {
        return defined(static::class . '::SELECTOR_COLUMN_SIZE')
            ? static::SELECTOR_COLUMN_SIZE
            : 16;
    }

    public function getVerifierColumnName(): string
    {
        return defined(static::class . '::VERIFIER_COLUMN_NAME')
            ? static::VERIFIER_COLUMN_NAME
            : 'verifier';
    }

    public function getVerifierColumnSize(): int
    {
        return defined(static::class . '::VERIFIER_COLUMN_SIZE')
            ? static::VERIFIER_COLUMN_SIZE
            : 16;
    }

    public function hashVerifierValue($value): string
    {
        return Hash::make($value);
    }
}