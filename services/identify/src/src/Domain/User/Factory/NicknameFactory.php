<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\Policy\StringPolicyInterface;
use App\Domain\User\ValueObject\Nickname;

class NicknameFactory extends StringPolicyFactory
{
    private string $canonical;

    public function __construct(protected StringPolicyInterface $policy) {}

    protected function transformOriginal(string $original): string
    {
        $normalized = preg_replace('/\p{Cf}|[\x00-\x1F\x7F]/u', '', $original) ?? '';
        $normalized = trim($normalized);
        $this->canonical = mb_strtolower($normalized, 'UTF-8');

        return $normalized;
    }

    /**
     * @return Nickname
     */
    protected function make(string $original): mixed
    {
        return new Nickname($original, $this->canonical);
    }

    protected function attributeName(string $name): string
    {
        return 'nickname';
    }
}
