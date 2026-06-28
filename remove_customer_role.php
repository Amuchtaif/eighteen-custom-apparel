<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$role = App\Models\Role::where('name', 'Customer')->first();

if ($role) {
    echo "Found Customer role. Deleting users...\n";
    $users = App\Models\User::where('role_id', $role->id)->get();
    foreach ($users as $user) {
        echo "Deleting user: " . $user->email . "\n";
        $user->delete();
    }
    echo "Deleting Customer role...\n";
    $role->delete();
    echo "Done.\n";
} else {
    echo "Customer role not found.\n";
}
