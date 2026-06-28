<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::whereDoesntHave('employee')->get();
foreach($users as $u) {
    if ($u->role && !in_array($u->role->name, ['Super Admin', 'Owner', 'Customer'])) {
        echo "Deleting user: " . $u->email . " (Role: " . $u->role->name . ")" . PHP_EOL;
        $u->delete();
    }
}
