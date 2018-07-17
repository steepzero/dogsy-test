<?php

namespace App\Dogsy\Facades;

use App\Dogsy\Concerns\ServiceContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class Dogsy
 * @package App\Dogsy\Facades
 * @method static run(string $operation, string $delimiter)
 * @method static getSupportedOperations()
 * @method static getSupportedDelimiters()
 * @method static isOperationSupported(string $operation)
 * @method static isDelimiterSupported(string $delimiter)
 */
class Dogsy extends Facade
{
    protected static function getFacadeAccessor()
    {
        /**
         * Реализация присвоена в App\Providers\AppServiceProvider
         */
        return ServiceContract::class;
    }
}