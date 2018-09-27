<?php

namespace App\Dogsy\Services;

use App\Dogsy\Concerns\OperationContract;
use App\Dogsy\Concerns\ServiceContract;
use App\Dogsy\Operations\CountAverageLineCountOperation;
use App\Dogsy\Operations\ReplaceDatesOperation;

class DogsyOperationsService implements ServiceContract
{
    /**
     * Маппинг кода операции и ее класса
     * @var array
     */
    protected $operations = [
        'countAverageLineCount' => CountAverageLineCountOperation::class,
        'replaceDates' => ReplaceDatesOperation::class
    ];

    /**
     * Маппинг кода разделителя и его символа
     * @var array
     */
    protected $delimiters = [
        'comma' => ',',
        'semicolon' => ';'
    ];

    /**
     * Запускает операцию
     * @param string $operation
     * @param string $delimiter
     * @return mixed
     */
    public function run(string $operation, string $delimiter)
    {
        // TODO replicates lines 78-86 of Commands\Dogsy.php a bit of overhead when using together
        if(!$this->isOperationSupported($operation))
            throw new \InvalidArgumentException('Unsupported operation');

        if(!$this->isDelimiterSupported($delimiter))
            throw new \InvalidArgumentException('Unsupported delimiter');

        $_operation = $this->getOperationObject($operation);
        $_delimiter = $this->getDelimiterCharacter($delimiter);

        return $_operation->run($_delimiter);
    }

    /**
     * Проверяет поддерживается операция
     * @param string $operation
     * @return bool
     */
    public function isOperationSupported(string $operation)
    {
        return isset($this->operations[$operation]);
    }

    /**
     * Проверяет поддерживается ли CSV-разделитель
     * @param string $delimiter
     * @return bool
     */
    public function isDelimiterSupported(string $delimiter)
    {
        return isset($this->delimiters[$delimiter]);
    }

    /**
     * Возвращает символ CSV-разделителя
     * @param string $delimiter
     * @return string
     */
    protected function getDelimiterCharacter(string $delimiter)
    {
        return $this->delimiters[$delimiter];
    }

    /**
     * Возвращает объект класса операции
     * @param string $operation
     * @return OperationContract
     */
    protected function getOperationObject(string $operation)
    {
        return app($this->operations[$operation]);
    }

    /**
     * Возвращает список поддерживаемых операций
     * @return array
     */
    public function getSupportedOperations()
    {
        return array_keys($this->operations);
    }

    /**
     * Возвращает список поддерживаемых разделителей
     * @return array
     */
    public function getSupportedDelimiters()
    {
        return array_keys($this->delimiters);
    }

    public function getOperationHeaders($operation)
    {
        return ($this->getOperationObject($operation))->getHeaders();
    }
}