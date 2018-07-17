<?php

namespace App\Dogsy\Concerns;

interface OperationContract
{
    /**
     * Запуск операции
     * @param string $delimiter
     * @return mixed
     */
    public function run(string $delimiter);
}