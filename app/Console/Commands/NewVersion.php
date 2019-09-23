<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NewVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recruit:version {version}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to version the script';

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
     */
    public function handle()
    {
       $version = $this->argument('version');
       $folder = 'recruit-'.$version;
       $path = '../versions/'.$folder;
       $local = '../dev/';

       $this->info('Creating Versions....');
       $this->info('Removing Old '.$folder.' folder to create the new');
       echo  exec('rm -rf '.$path.'/');

       $this->info('Creating the directory '.$folder.'/script');
       echo  exec('mkdir -p '.$path.'/script');

       $this->info('Copying files from '.$local.' '.$path.'/script');
        echo  exec('rsync -av --progress '.$local.' '.$path.'/script --exclude=".git" --exclude=".phpintel" --exclude=".env" --exclude=".idea"');

       $this->info('Creating the directory '.$path.'/script');
       echo  exec('mkdir -p '.$path.'/script');

       $this->info('Removing installed');
       echo  exec('rm -rf '.$path.'/script/storage/installed');

       $this->info('Delete Storage Folder Files');
       echo  exec('rm -rf '.$path.'/script/public/storage');

       $this->info('Removing symlink');
       echo  exec('find '.$path.'/script/storage/app/public \! -name ".gitignore" -delete');


       $this->info('Copying .env.example to .env');
       echo  exec('cp '.$path.'/script/.env.example '.$path.'/script/.env');

       $this->info('removing old version.txt file');
       echo  exec('rm '.$path.'/script/public/version.txt');

       $this->info('Copying version to know the version to version.txt file');
       echo  exec('echo '.$version.'>> '.$path.'/script/public/version.txt');

       $this->info('Moving script/documentation to separate folder');
       echo  exec('mv '.$path.'/script/documentation '.$path.'/documentation/');

       // Zipping the folder
       $this->info('Zipping the folder');
       echo  exec('cd ../versions; zip -r '.$folder.'.zip '.$folder.'/');


        //start quick update version
        $folder = 'recruit-auto-'.$version;
        $path = '../versions/auto-update';
        $local = '../dev/';

        $this->info('Creating Auto update version....');
        $this->info('Removing Old '.$folder.' folder to create the new');
        echo  exec('rm -rf '.$path.'/'.$folder);

        $this->info('Copying files from '.$local.' to '.$path);
        echo  exec('rsync -av --progress '.$local.' '.$path.'/script --exclude=".git" --exclude=".phpintel" --exclude=".env" --exclude="public/.htaccess" --exclude="public/favicon" --exclude="public/favicon.ico" --exclude=".gitignore" --exclude=".idea"');

        $this->info('Removing installed');
//        echo  exec('rm -rf '.$path.'/storage/installed');

        $this->info('Delete Storage Folder Files');
        echo  exec('rm -rf '.$path.'/public/storage');

        $this->info('Removing symlink');
        echo  exec('find '.$path.'/storage/app/public \! -name ".gitignore" -delete');

        $this->info('removing old version.txt file');
        echo  exec('rm '.$path.'/public/version.txt');

        $this->info('Copying version to know the version to version.txt file');
        echo  exec('echo '.$version.'>> '.$path.'/public/version.txt');

        // Zipping the folder
//        $this->info('Zipping the folder');
//        echo  exec('cd ../versions/auto-update; zip -r '.$folder.'.zip .');
    }


}
