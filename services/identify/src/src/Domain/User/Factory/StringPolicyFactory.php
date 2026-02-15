<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\Exception\CharactersException;
use App\Domain\User\Exception\LengthException;
use App\Domain\User\Exception\ValidationException;
use App\Domain\User\Policy\StringPolicyInterface;

/**
 * @template
 */
abstract class StringPolicyFactory
{
    protected StringPolicyInterface $policy;

    /**
     * @throws ValidationException
     */
    public function create(string $original): mixed
    {
        $this->beforePolicyCheck($original);

        $attributeName = $this->attributeName($original);
        $transformed = $this->transformOriginal($original);
        $len = mb_strlen($transformed);

        if ($len < $this->policy->min() || $len > $this->policy->max()) {
            throw new LengthException(
                min: $this->policy->min(),
                max: $this->policy->max(),
                codeName: "{$attributeName}_length",
            );
        }

        if ($this->policy->regex() && preg_match($this->policy->regex(), $transformed) === false) {
            throw new CharactersException(codeName: "{$attributeName}_characters");
        }

        $this->afterPolicyCheck($transformed);

        return $this->make($transformed);
    }

    abstract protected function make(string $original): mixed;

    abstract protected function attributeName(string $name): string;

    protected function beforePolicyCheck(string $original): void {}

    protected function afterPolicyCheck(string $original): void {}

    protected function transformOriginal(string $original): string
    {
        return $original;
    }
}
