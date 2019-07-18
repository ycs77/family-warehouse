<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ImageTempClear extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'warehouse:items-img:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the items images';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new config clear command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->files->deleteDirectory(storage_path('app/print_a4'));
        $this->files->deleteDirectory(storage_path('app/print_a4_qrcode'));

        $this->info('Items images cleared!');
    }
}
