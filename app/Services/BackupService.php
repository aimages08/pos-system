<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    protected string $folder = 'backups';

    public function create(): string
    {
        $filename = 'backup-' . now()->format('Y-m-d_H-i-s') . '.sql';
        $path     = storage_path('app/' . $this->folder . '/' . $filename);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $handle = fopen($path, 'w');
        if (!$handle) {
            throw new \Exception('Could not create backup file.');
        }

        fwrite($handle, "-- POS Backup\n");
        fwrite($handle, "-- Date: " . now()->toDateTimeString() . "\n");
        fwrite($handle, "-- Database: " . config('database.connections.mysql.database') . "\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $key    = 'Tables_in_' . $dbName;

        foreach ($tables as $tableRow) {
            $table = $tableRow->$key;

            $create    = DB::select("SHOW CREATE TABLE `$table`")[0];
            $createSql = $create->{'Create Table'};

            fwrite($handle, "--\n-- Table: $table\n--\n");
            fwrite($handle, "DROP TABLE IF EXISTS `$table`;\n");
            fwrite($handle, $createSql . ";\n\n");

            $rows = DB::table($table)->get();
            if ($rows->count() > 0) {
                foreach ($rows as $row) {
                    $values = [];
                    foreach ((array) $row as $value) {
                        if (is_null($value)) {
                            $values[] = 'NULL';
                        } else {
                            $escaped  = addslashes((string) $value);
                            $values[] = "'" . $escaped . "'";
                        }
                    }
                    fwrite($handle, "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n");
                }
                fwrite($handle, "\n");
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);

        return $filename;
    }

    public function list(): array
    {
        $folder = storage_path('app/' . $this->folder);
        if (!is_dir($folder)) return [];

        $files = [];
        foreach (scandir($folder) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'sql') continue;

            $path    = $folder . '/' . $file;
            $files[] = [
                'name' => $file,
                'size' => filesize($path),
                'date' => date('Y-m-d H:i:s', filemtime($path)),
            ];
        }

        usort($files, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
        return $files;
    }

    public function delete(string $filename): bool
    {
        $path = storage_path('app/' . $this->folder . '/' . $filename);
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    public function cleanup(int $days = 30): int
    {
        $cutoff = strtotime("-$days days");
        $count  = 0;

        foreach ($this->list() as $file) {
            if (strtotime($file['date']) < $cutoff) {
                if ($this->delete($file['name'])) $count++;
            }
        }

        return $count;
    }

    public function restore(string $filename): void
    {
        $path = storage_path('app/' . $this->folder . '/' . $filename);

        if (!file_exists($path)) {
            throw new \Exception('Backup file not found.');
        }

        $sql = file_get_contents($path);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach (explode(";\n", $sql) as $query) {
            $query = trim($query);
            if (!empty($query) && !str_starts_with($query, '--') && !str_starts_with($query, '/*')) {
                try {
                    DB::statement($query);
                } catch (\Exception $e) {
                    // Skip errors silently
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}