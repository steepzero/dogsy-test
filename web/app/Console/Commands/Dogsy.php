<?php

namespace App\Console\Commands;

use App\Dogsy\Exceptions\DogsyException;
use App\Dogsy\Facades\Dogsy as DogsyFacade;
use Carbon\Carbon;
use Illuminate\Console\Command;
use League\Csv\Reader;
use Symfony\Component\Console\Input\InputArgument;

class Dogsy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dogsy
                {separator : comma|semicolon}
                {operation : countAverageLineCount|replaceDates}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dogsy test case command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \League\Csv\Exception
     */
    public function handle()
    {
        try {
            $this->checkArguments();
        }catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());
            return;
        }

        try {
            $table = DogsyFacade::run($this->argument('operation'), $this->argument('separator'));
        }catch (DogsyException $e) {
            $this->error($e->getMessage());
            return;
        }catch (\Exception $e) {
            report($e);
            $this->error("An error occurred, see logs for details");
            return;
        }

        $this->table(DogsyFacade::getOperationHeaders($this->argument('operation')), $table);
        return;
    }

    protected function checkArguments()
    {

        if (!DogsyFacade::isDelimiterSupported($this->argument('separator')))
            throw new \InvalidArgumentException('Wrong separator value. Supported separators: '.implode(', ', DogsyFacade::getSupportedDelimiters()));

        if (!DogsyFacade::isOperationSupported($this->argument('operation')))
            throw new \InvalidArgumentException('Wrong operation type. Supported operations: '.implode(', ', DogsyFacade::getSupportedOperations()));
    }

    protected function getArguments()
    {
        return [
            ['separator', InputArgument::REQUIRED, 'CSV-separator: '.implode('|',DogsyFacade::getSupportedDelimiters())],
            ['operation', InputArgument::REQUIRED, 'Operation: '.implode('|',DogsyFacade::getSupportedOperations())]
        ];
    }
}
