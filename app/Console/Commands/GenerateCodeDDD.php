<?php

namespace App\Console\Commands;

use App\Helper\HelperGenerateCode;
use Illuminate\Console\Command;

class GenerateCodeDDD extends Command
{
    protected HelperGenerateCode $helper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:ddd {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make folder and file with ddd struct';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(HelperGenerateCode $helper)
    {
        parent::__construct();
        $this->helper = $helper;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        if (empty($name)) {
            echo 'Please provide name';
            return;
        }
        $results = $this->helper->generateCode($name);
        if (isset($results['isError']) && $results['isError']) {
            echo $results['message'];
        } else {
            echo 'Generate done';
        }
    }
}
