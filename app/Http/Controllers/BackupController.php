<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Request;

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

    public function backup_tables($tables = '*')
    {
        if ($tables == '*') {
            $tables = array();
            $result = DB::select('SHOW TABLES');
            foreach ($result as $item) {
                $tables[] = $item->Tables_in_hfisio;
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

        Storage::put(
            'db-backup-' . time() . '-' . (md5(implode(',', $tables))) . '.sql',
            $return
        );

        if (!Request::ajax()) {
            return view('backup/index', [
                'lang' => 'ca',
                'title' => 'Backup de la bd',]);
        }
    }
}
