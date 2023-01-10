<?php

namespace App\Http\Controllers;

use Spatie\DbDumper\Databases\MySql;

class ExportController extends Controller
{
    public function __invoke()
    {
        MySql::create()
            ->setDbName(config('database.connections.mysql.database'))
            ->setUserName(config('database.connections.mysql.username'))
            ->setPassword(config('database.connections.mysql.password'))
            ->dumpToFile('dump.sql');

        return response()
            ->download(storage_path('dump.sql'), 'dump.sql')
            ->deleteFileAfterSend();
    }
}
