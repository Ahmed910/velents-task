<?php

namespace App\Traits;
use Str;

trait EnumTrait
{

    public static function toArray(
        bool $formatKey = false,
        bool $translatableKey = false,
        bool $flip = false,
        bool $toLower = false,
        mixed $defaultValue = null
    ): array {
        $reflectionClass = new \ReflectionClass(static::class);

        $enums = $reflectionClass->getConstants();
        $formattedEnums = [];
        foreach ($enums as $key => $value) {
            if ($formatKey) {
                $key = ucwords(Str::of($key)->replace('_', ' ')->lower()->toString());
            }

            if ($translatableKey) {
                $key = ___($key);
            }

            if ($flip) {
                $formattedEnums[$value] = $key;

                continue;
            }

            if ($toLower) {
                $formattedEnums[strtolower((string) $key)] = $defaultValue;

                continue;
            }

            $formattedEnums[(string) $key] = $value;
        }

        return $formattedEnums;
    }

    public static function values()
    {
        return array_map(function ($obj) {
            return $obj->value;
        }, static::class::cases());
    }


    public static function value(?string $key): string|int|bool|null
    {
        if (is_null($key)) {
            return $key;
        }

        return self::toArray()[strtoupper($key)]->value ?? null;
    }

    public static function keys(bool $trans = false): array
    {
        $reflectionClass = new \ReflectionClass(static::class);

        return array_map(
            fn ($key) => $trans ? __(strtolower($key)) : $key,
            array_keys($reflectionClass->getConstants())
        );
    }

    public static function key(string|int|bool|null $value, bool $toLower = false, bool $trans = false): ?string
    {
        if (is_null($value)) {
            return $value;
        }

        $key = self::keys()[$value] ?? null;

        if ($trans) {
            $key = __(strtolower(self::keys()[$value]));
        }

        if ($toLower && $key) {
            return strtolower($key);
        }

        return $key;
    }
}
