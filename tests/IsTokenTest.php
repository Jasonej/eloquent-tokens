<?php

namespace Jasonej\EloquentTokens\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Jasonej\EloquentTokens\Tests\Models\ModifiedToken;
use Jasonej\EloquentTokens\Tests\Models\Token;

class IsTokenTest extends TestCase
{
    /** @test */
    public function claimed_at_column_name_is_accessible(): void
    {
        $this->assertSame('claimed_at', (new Token)->getClaimedAtColumnName());
    }

    /** @test */
    public function expires_at_column_name_is_accessible(): void
    {
        $this->assertSame('expires_at', (new Token)->getExpiresAtColumnName());
    }

    /** @test */
    public function selector_column_name_is_accessible(): void
    {
        $this->assertSame('selector', (new Token)->getSelectorColumnName());
    }

    /** @test */
    public function selector_column_size_is_accessible(): void
    {
        $this->assertSame(16, (new Token)->getSelectorColumnSize());
    }

    /** @test */
    public function verifier_column_name_is_accessible(): void
    {
        $this->assertSame('verifier', (new Token)->getVerifierColumnName());
    }

    /** @test */
    public function verifier_column_size_is_accessible(): void
    {
        $this->assertSame(16, (new Token)->getVerifierColumnSize());
    }

    /** @test */
    public function selector_is_defined_during_creation(): void
    {
        $token = (new Token);
        $token->save();

        $this->assertNotNull($token->selector);
    }

    /** @test */
    public function verifier_is_defined_during_creation(): void
    {
        $token = (new Token);
        $token->save();

        $this->assertNotNull($token->verifier);
    }

    /** @test */
    public function verifier_is_transiently_stored_raw_on_the_model(): void
    {
        $token = (new Token);
        $token->save();

        $this->assertNotNull($token->rawVerifierValue);
    }

    /** @test */
    public function verifier_is_hashed_during_creation(): void
    {
        $token = (new Token);
        $token->save();

        $this->assertTrue(Hash::check($token->rawVerifierValue, $token->verifier));
    }

    /** @test */
    public function token_can_be_retrieved_by_value(): void
    {
        $token = (new Token);
        $token->save();

        $token = $token->findByTokenValue("{$token->selector}{$token->rawVerifierValue}");

        $this->assertNotNull($token);
    }

    /** @test */
    public function token_is_not_found_if_claimed(): void
    {
        $token = (new Token)->fill(['claimed_at' => Carbon::yesterday()]);
        $token->save();

        $token = $token->findByTokenValue("{$token->selector}{$token->rawVerifierValue}");

        $this->assertNull($token);
    }

    /** @test */
    public function token_is_not_found_if_expired(): void
    {
        $token = (new Token)->fill(['expires_at' => Carbon::yesterday()]);
        $token->save();

        $token = $token->findByTokenValue("{$token->selector}{$token->rawVerifierValue}");

        $this->assertNull($token);
    }

    /** @test */
    public function claimed_at_column_is_configurable(): void
    {
        $token = (new ModifiedToken);
        $token->save();

        $this->assertSame('claimed', $token->getClaimedAtColumnName());
    }

    /** @test */
    public function expires_at_column_is_configurable(): void
    {
        $token = (new ModifiedToken);
        $token->save();

        $this->assertSame('expires', $token->getExpiresAtColumnName());
    }

    /** @test */
    public function selector_column_is_configurable(): void
    {
        $token = (new ModifiedToken);
        $token->save();

        $this->assertSame('public_segment', $token->getSelectorColumnName());
        $this->assertSame(8, $token->getSelectorColumnSize());
    }

    /** @test */
    public function verifier_column_is_configurable(): void
    {
        $token = (new ModifiedToken);
        $token->save();

        $this->assertSame('secret_segment', $token->getVerifierColumnName());
        $this->assertSame(8, $token->getVerifierColumnSize());
    }
}