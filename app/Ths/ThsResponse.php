<?php

namespace App\Ths;

/**
 * A normalized THS (e-Nabız / SGK) service response.
 */
class ThsResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly string $code,
        public readonly string $message,
        /** @var array<string, mixed> */
        public readonly array $raw = [],
    ) {}

    /**
     * @param  array<string, mixed>  $raw
     */
    public static function success(string $code, string $message, array $raw = []): self
    {
        return new self(true, $code, $message, $raw);
    }

    /**
     * @param  array<string, mixed>  $raw
     */
    public static function failure(string $code, string $message, array $raw = []): self
    {
        return new self(false, $code, $message, $raw);
    }
}
