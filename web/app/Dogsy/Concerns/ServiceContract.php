<?php

namespace App\Dogsy\Concerns;

interface ServiceContract
{

    /**
     * Запускает операцию
     * @param string $operation
     * @param string $delimiter
     * @return mixed
     */
    public function run(string $operation, string $delimiter);

    /**
     * Проверяет поддерживается операция
     * @param string $operation
     * @return bool
     */
    public function isOperationSupported(string $operation);

    /**
     * Проверяет поддерживается ли CSV-разделитель
     * @param string $delimiter
     * @return bool
     */
    public function isDelimiterSupported(string $delimiter);

    /**
     * Возвращает список поддерживаемых операций
     * @return array
     */
    public function getSupportedOperations();

    /**
     * Возвращает список поддерживаемых разделителей
     * @return array
     */
    public function getSupportedDelimiters();
}