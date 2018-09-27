<?php

namespace App\Dogsy\Concerns;

use App\Dogsy\Exceptions\DogsyException;
use League\Csv\Exception;
use League\Csv\Reader;

abstract class Operation implements OperationContract
{
    /**
     * @param string $delimiter
     * @return \Iterator
     * @throws DogsyException
     * @throws Exception
     */
    protected function getUserIterator(string $delimiter)
    {
        $file = base_path() . '/people.csv';
        if (!is_file($file) || !is_readable($file))
            throw new DogsyException("File $file should be present and readable");

        $user_reader = Reader::createFromPath($file, 'r');
        $user_reader->setDelimiter($delimiter);
        return $user_reader->getIterator();
    }

    /**
     * @param string $user_id
     * @return \RegexIterator
     * @throws DogsyException
     */
    protected function getUserTextsIterator(string $user_id)
    {
        $dir = base_path() . '/texts';
        if (!is_dir($dir))
            mkdir($dir);

        if (!is_dir($dir))
            throw new DogsyException("Cant't create texts directory");

        $iterator = new \DirectoryIterator($dir);
        return new \RegexIterator($iterator, "/{$user_id}-(\d{3}).txt/", \RegexIterator::MATCH);
    }

    abstract public function run(string $delimiter);

    abstract public function getHeaders();
}