<?php

namespace App\Dogsy\Operations;

use App\Dogsy\Concerns\Operation;
use App\Dogsy\Exceptions\DogsyException;
use Carbon\Carbon;

class ReplaceDatesOperation extends Operation
{
   /**
     * @param string $delimiter
     * @return array|mixed
     * @throws DogsyException
     * @throws \League\Csv\Exception
     */
    public function run(string $delimiter)
    {
        if(!is_dir(base_path().'/output_texts'))
            mkdir(base_path().'/output_texts');

        $table = [];
        foreach ($this->getUserIterator($delimiter) as $user) {

            $total_count = 0;
            foreach ($this->getUserTextsIterator($user[0]) as $item) {
                /**
                 * @var $item \DirectoryIterator
                 */
                if(!$item->isFile() || !$item->isReadable())
                    continue;

                $file = new \SplFileObject($item->getPathname(),'r');
                $new_file = new \SplFileObject(base_path().'/output_texts/'.$item->getFilename(),'w');
                $count = 0;
                while (!$file->eof()) {
                    //Проводим замены построчно, экономим память
                    $line = preg_replace_callback('(\d{2}\/\d{2}\/\d{2})',function($matches) {
                        $date = Carbon::createFromFormat('d/m/y',$matches[0])->format('m-d-Y');
                        return $date;
                    },$file->fgets(),-1,$count);

                    if ($line !== '' && $new_file->fwrite($line) == null) {
                        throw new DogsyException("Can't write to \"output_texts\" directory");
                    }
                    $total_count += $count;
                }
                $file = null;
                $new_file = null;
            }

            $table[] = [$user[1],$total_count];
        }

        return $table;
    }

    public function getHeaders()
    {
        return ['Users', 'Replaced Dates'];
    }
}