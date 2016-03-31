<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function checkIfBackup()
    {
        $files = Storage::files();
        foreach ($files as $file) {

        }
    }

    public function backup_tables(Request $request, $tables = '*')
    {
        if ($tables == '*') {
            $tables = array();
            $result = DB::select('SHOW TABLES');
            $stringTable = "Tables_in_" . env('DB_DATABASE');
            foreach ($result as $item) {
                $tables[] = $item->{$stringTable};
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        $return = "";
        foreach ($tables as $table) {
            $result = DB::table($table)->get();

            $return .= 'DROP TABLE ' . $table . ';';
            $row2 = DB::select('SHOW CREATE TABLE ' . $table);
            $showTable = $row2[0]->{"Create Table"};
            $return .= "\n\n" . $showTable . ";\n\n";

            $list = Schema::getColumnListing($table);
            foreach ($result as $item) {
                $arr = (array)$item;
                $keys = array_keys($arr);
                $return .= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < count($keys); $j++) {
                    $value = $item->{$keys[$j]};
                    $value = addslashes($value);
                    $value = str_replace("\n", "\\n", $value);
                    if (isset($value)) {
                        $return .= '"' . $value . '"';
                    } else {
                        $return .= '""';
                    }
                    if ($j < (count($list) - 1)) {
                        $return .= ',';
                    }
                }
                $return .= ");\n";
            }
        }
        $return .= "\n\n\n";

        $encriptedValue = Crypt::encrypt($return);

        $time = time();
        Storage::put(
            'db-backup-' . $time . '-' . (md5(implode(',', $tables))) . '.sql',
            $encriptedValue
        );

        $bk = new \App\Backup();
        $bk->date_of_backup = Carbon::now();
        $bk->user_id = Auth::user()->id;
        $bk->save();

        $date = new Carbon();
        $date = $date->setTimestamp($time)->format('d M Y H:i:s');

        Log::info('BackupController ' . $date . ': Backup generado');

        if (!$request->ajax()) {
            return view('backup/index', [
                'lang' => 'ca',
                'title' => 'Backup de la bd',]);
        }
    }

    public function listBackups(\Illuminate\Http\Request $request)
    {
        $path = storage_path('app');
        $files = File::files($path);

        $dbFiles = [];
        foreach ($files as $file) {
            if (str_contains($file, 'db-')) {
                $name = basename($file);
                $nameExploded = explode('-', $name);
                $date = new Carbon();
                $date = $date->setTimestamp($nameExploded[2])->format('d M Y H:i:s');

                $dbFiles[] = [
                    'path' => $file,
                    'name' => $name,
                    'date' => $date,
                    'size' => $this->humanFileSize(File::size($file))
                ];
            }
        }

        return view('backup/list', [
            'lang' => 'ca',
            'title' => 'Llistat de backups',
            'files' => $dbFiles
        ]);
    }

    public function decryptBackup(\Illuminate\Http\Request $request, $file)
    {
        $file = base64_decode($file);
        $content = Storage::get($file);
        try {
            $string = Crypt::decrypt($content);
        } catch (DecryptException $e) {
            $string = $content;
        }

        $nameExploded = explode('-', $file);
        $time = $nameExploded[2];
        $zip = new \ZipArchive();
        $tempName = $time . '-backup.zip';
        $zipName = storage_path() . '/app/zip/' . $tempName;
        $zip->open($zipName, ZIPARCHIVE::CREATE);
        $zip->addFromString($file, $string);
        $zip->close();

        return response()->download($zipName, $tempName);
    }

    private function humanFileSize($size, $unit = "")
    {
        if ((!$unit && $size >= 1 << 30) || $unit == "GB")
            return number_format($size / (1 << 30), 2) . "GB";
        if ((!$unit && $size >= 1 << 20) || $unit == "MB")
            return number_format($size / (1 << 20), 2) . "MB";
        if ((!$unit && $size >= 1 << 10) || $unit == "KB")
            return number_format($size / (1 << 10), 2) . "KB";
        return number_format($size) . " bytes";
    }
}
