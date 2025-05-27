<?php

namespace App\Console\Commands;

use App\Helper\HelperGenerateCode;
use Illuminate\Console\Command;

class RemoveCodeDDD extends Command
{
    protected HelperGenerateCode $helper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:ddd {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove folder and file maked with ddd struct';

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
        $results = $this->helper->resetFolder($name);
        if (isset($results['isError']) && $results['isError']) {
            echo $results['message'];
        } else {
            echo 'Remove done';
        }
    }
}
