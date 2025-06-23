<?php

namespace NhanDev\Rbac\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeSeedersCommand extends Command // Changed class name
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rbac:make-seeders'; // Changed signature

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the database seeders required for the RBAC package.'; // Changed description

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->comment('Creating RBAC Seeders...');
        $this->createSeeders();

        $this->info('RBAC seeders created successfully.');
        $this->warn('Please add the seeder calls to your DatabaseSeeder.php file and run "php artisan db:seed".');
    }

    /**
     * Create the seeder files from stubs.
     */
    protected function createSeeders()
    {
        $files = new Filesystem();

        $seeders = [
            'PermissionSeeder.php' => __DIR__.'/../../../stubs/seeders/PermissionSeeder.stub',
            'RoleSeeder.php' => __DIR__.'/../../../stubs/seeders/RoleSeeder.stub',
            'SuperAdminSeeder.php' => __DIR__.'/../../../stubs/seeders/SuperAdminSeeder.stub',
        ];

        foreach ($seeders as $fileName => $stubPath) {
            $destinationPath = database_path('seeders/' . $fileName);

            if ($files->exists($destinationPath) && !$this->confirm("The [{$fileName}] seeder already exists. Do you want to replace it?")) {
                continue;
            }

            $stubContent = $files->get($stubPath);

            // Replace placeholders
            $finalContent = str_replace(
                '{{ namespace }}',
                'Database\\Seeders',
                $stubContent
            );

            $files->put($destinationPath, $finalContent);
            $this->info("Seeder [{$fileName}] created successfully.");
        }
    }
}
