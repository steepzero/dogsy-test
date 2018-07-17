<?php

namespace App\Dogsy\Operations;

use App\Dogsy\Concerns\Operation;

class CountAverageLineCountOperation extends Operation
{
    public function run(string $delimiter) {
        $table = [];
        foreach ($this->getUserIterator($delimiter) as $user) {
            $total_lines = 0;
            $total_files = 0;
            foreach ($this->getUserTextsIterator($user[0]) as $item) {
                /**
                 * @var $item  \DirectoryIterator
                 */
                if(!$item->isFile() || !$item->isReadable())
                    continue;

                ++$total_files;
                $file = new \SplFileObject($item->getPathname(),'r');
                while (!$file->eof()) {
                    $line = $file->fgets();
                    ++$total_lines;
                }
                unset($line);
                unset($file);
            }

            $average = 0;
            if ($total_files > 0)
                $average = $total_lines / $total_files;

            $table[] = [$user[1],$average];
        }

        return $table;
    }
}