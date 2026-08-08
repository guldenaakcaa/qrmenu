<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

$tableName = 't_urunkart';

if (!Schema::hasColumn($tableName, 'alerjenler')) {
    Schema::table($tableName, function (Blueprint $table) {
        $table->string('alerjenler')->nullable()->after('UrunAciklama');
    });
    echo "Added 'alerjenler' to $tableName\n";
} else {
    echo "'alerjenler' already exists in $tableName\n";
}
