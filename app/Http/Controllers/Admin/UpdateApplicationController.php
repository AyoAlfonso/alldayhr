<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use ZanySoft\Zip\Zip;

class UpdateApplicationController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.updateApplication');
        $this->pageIcon = __('ti-settings');
    }

    public function index(){
        $client = new Client();
        $res = $client->request('GET', config('laraupdater.update_baseurl').'/laraupdater.json');
        $lastVersion = $res->getBody();
        $lastVersion = json_decode($lastVersion, true);

        if (version_compare($lastVersion['version'], File::get('version.txt')) > 0)
        {
            $this->lastVersion = $lastVersion['version'];
            $this->updateInfo = $lastVersion['description'];
        }

        $this->appVersion = File::get('version.txt');
        $laravel = app();
        $this->laravelVersion = $laravel::VERSION;

        return view('admin.update-application.index', $this->data);
    }


    private $tmp_backup_dir = null;

    private function checkPermission(){

        if( config('laraupdater.allow_users_id') !== null ){

            // 1
            if( config('laraupdater.allow_users_id') === false ) return true;

            // 2
            if( in_array(Auth::User()->id, config('laraupdater.allow_users_id')) === true ) return true;
        }

        return false;
    }
    /*
    * Download and Install Update.
    */
    public function update()
    {
        if( ! $this->checkPermission() ){
            return Reply::error("ACTION NOT ALLOWED.");
        }

        $lastVersionInfo = $this->getLastVersion();

        if ( $lastVersionInfo['version'] <= $this->getCurrentVersion() ){
            return Reply::error("Your System IS ALREADY UPDATED to latest version !");
        }

        try{
            $this->tmp_backup_dir = base_path().'/backup_'.date('Ymd');

            $lastVersionInfo = $this->getLastVersion();

            $update_name = $lastVersionInfo['archive'];

            $filename_tmp = config('laraupdater.tmp_path').'/'.$update_name;


            if(file_exists($filename_tmp)){
                File::delete($filename_tmp); //delete old file if exist
            }

            File::put(public_path().'/install-version.txt', 'complete');

            return Reply::successWithData('Starting Download...', ['description' => $lastVersionInfo['description']]);


            $status = $this->install($lastVersionInfo['version'], $update_path, $lastVersionInfo['archive']);

            if($status){

                echo '<p>&raquo; SYSTEM Mantence Mode => OFF</p>';
                echo '<p class="text-success">SYSTEM IS NOW UPDATED TO VERSION: '.$lastVersionInfo['version'].'</p>';
                echo '<p style="font-weight: bold;">RELOAD YOUR BROWSER TO SEE CHANGES</p>';
            }else
                throw new \Exception("Error during updating.");

        }catch (\Exception $e) {
            echo '<p>ERROR DURING UPDATE (!!check the update archive!!) --TRY to restore OLD status ........... ';

            $this->restore();

            echo '</p>';
        }
    }

    public function install()
    {
        $lastVersionInfo = $this->getLastVersion();
        $archive = $lastVersionInfo['archive'];
        $update_path = config('laraupdater.tmp_path').'/'.$archive;

        $zip = Zip::open($update_path);

        // extract whole archive
        $zip->extract(base_path());
    }

    /*
    * Download Update from $update_baseurl to $tmp_path (local folder).
    */
    public function download(Request $request)
    {        
        File::put(public_path().'/percent-download.txt', '');

        $lastVersionInfo = $this->getLastVersion();

        $update_name = $lastVersionInfo['archive'];

        $filename_tmp = config('laraupdater.tmp_path').'/'.$update_name;

        $downloadRemoteUrl = config('laraupdater.update_baseurl').'/'.$update_name;

        $dlHandler = fopen($filename_tmp, 'w');

        $client = new Client();
        $client->request('GET', $downloadRemoteUrl, [
            'sink' => $dlHandler,
            'progress' => function ($dl_total_size, $dl_size_so_far, $ul_total_size, $ul_size_so_far) {
                $percentDownloaded = ($dl_total_size > 0) ? (($dl_size_so_far/$dl_total_size)*100) : 0;
                File::put(public_path().'/percent-download.txt', $percentDownloaded);
            }
        ]);

        return Reply::success('Download complete. Now Installing...');

    }

    /*
    * Return current version (as plain text).
    */
    public function getCurrentVersion(){
        $version = File::get(public_path().'/version.txt');
        return $version;
    }

    /*
    * Check if a new Update exist.
    */
    public function check()
    {
        $lastVersionInfo = $this->getLastVersion();
        if ( $lastVersionInfo['version'] > $this->getCurrentVersion() )
            return $lastVersionInfo['version'];

        return '';
    }

    private function setCurrentVersion($last){
        File::put(public_path().'/version.txt', $last); //UPDATE $current_version to last version
    }

    private function getLastVersion(){
        $client = new Client();
        $res = $client->request('GET', config('laraupdater.update_baseurl').'/laraupdater.json');
        $lastVersion = $res->getBody();

        $content = json_decode($lastVersion, true);
        return $content; //['version' => $v, 'archive' => 'RELEASE-$v.zip', 'description' => 'plain text...'];
    }

    private function backup($filename){
        $backup_dir = $this->tmp_backup_dir;

        if ( !is_dir($backup_dir) ) File::makeDirectory($backup_dir, $mode = 0755, true, true);
        if ( !is_dir($backup_dir.'/'.dirname($filename)) ) File::makeDirectory($backup_dir.'/'.dirname($filename), $mode = 0755, true, true);

        File::copy(base_path().'/'.$filename, $backup_dir.'/'.$filename); //to backup folder
    }

    private function restore(){
        if( !isset($this->tmp_backup_dir) )
            $this->tmp_backup_dir = base_path().'/backup_'.date('Ymd');

        try{
            $backup_dir = $this->tmp_backup_dir;
            $backup_files = File::allFiles($backup_dir);

            foreach ($backup_files as $file){
                $filename = (string)$file;
                $filename = substr($filename, (strlen($filename)-strlen($backup_dir)-1)*(-1));
                echo $backup_dir.'/'.$filename." => ".base_path().'/'.$filename;
                File::copy($backup_dir.'/'.$filename, base_path().'/'.$filename); //to respective folder
            }

        }catch(\Exception $e) {
            echo "Exception => ".$e->getMessage();
            echo "<BR>[ FAILED ]";
            echo "<BR> Backup folder is located in: <i>".$backup_dir."</i>.";
            echo "<BR> Remember to restore System UP-Status through shell command: <i>php artisan up</i>.";
            return false;
        }

        echo "[ RESTORED ]";
        return true;
    }

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function downloadPercent(Request $request){
        $percent =  File::get(public_path().'/percent-download.txt');
        return $percent;
    }

    public function checkIfFileExtracted(){
        $status =  File::get(public_path().'/install-version.txt');

        if($status == 'pending'){
            Artisan::call('view:clear'); //clear compiled files

            Artisan::call('migrate', array('--force' => true)); //migrate database

            $lastVersionInfo = $this->getLastVersion();
            $this->setCurrentVersion($lastVersionInfo['version']); //update system version

            return Reply::success('Installed successfully.');
        }
    }
}
