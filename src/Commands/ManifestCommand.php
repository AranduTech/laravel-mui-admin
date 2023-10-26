<?php

namespace Arandu\LaravelMuiAdmin\Commands;

use Arandu\LaravelMuiAdmin\Services\AdminService;
use Illuminate\Console\Command;
use Illuminate\Support\Traits\Macroable;

class ManifestCommand extends Command
{

    use Macroable;

    /** @var AdminService */
    private $adminService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:manifest
                            {--path= : The path to the manifest file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the manifest file for "bundle" mode.';

    /**
     * Create a new command instance.
     */
    public function __construct(AdminService $adminService)
    {
        parent::__construct();

        $this->adminService = $adminService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $manifest = [
            'models' => $this->adminService->getModelSchema(),
            'routes' => $this->adminService->getRoutes(),
        ];

        if (static::hasMacro('modifyManifest')) {
            $manifest = static::modifyManifest($manifest);
        }

        $filepath = $this->option('path') ?? resource_path('js/src/config/boot.json');

        file_put_contents($filepath, json_encode($manifest, JSON_PRETTY_PRINT));

        return 0;
    }

}