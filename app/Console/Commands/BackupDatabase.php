<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File; // مهم للاستعمال

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup database to storage folder';

    public function handle()
    {
        $dbHost = env('DB_HOST', 'localhost');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        //    $backupFolder = 'F:\\backups';
        $backupFolder = 'E:\backups';


        // إنشاء المجلد لو مش موجود
        if (!file_exists($backupFolder)) {
            mkdir($backupFolder, 0777, true);
        }

        // مسح كل النسخ القديمة
        $files = glob($backupFolder . '/*.sql'); // كل ملفات SQL
        foreach ($files as $file) {
            @unlink($file);
        }

        // اسم الملف الجديد
        $filename = $backupFolder . '/' . $dbName . '-' . date('Y-m-d_H-i-s') . '.sql';

        $mysqldumpPath = 'D:\\xampp\\mysql\\bin\\mysqldump.exe'; // مسار mysqldump

        // على Windows لازم نستخدم cmd /c
        $command = "cmd /c \"{$mysqldumpPath} --user={$dbUser} --password={$dbPass} --host={$dbHost} {$dbName} > \"{$filename}\"\"";

        exec($command, $output, $return_var);

        if ($return_var !== 0) {
            $this->error('Backup failed!');
        } else {
            $this->info("Backup completed successfully: {$filename}");
        }
    }
}
