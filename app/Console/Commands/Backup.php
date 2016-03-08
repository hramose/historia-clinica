<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup_db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->backup();
    }

    public function backup($tables = '*')
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

        Storage::put(
            'db-backup-' . time() . '-' . (md5(implode(',', $tables))) . '.sql',
            $encriptedValue
        );

        $bk = new \App\Backup();
        $bk->date_of_backup = Carbon::now();
        $bk->user_id = 'system:cron';
        $bk->save();

        Log::info($this->signature . ': Backup generado');
    }
}