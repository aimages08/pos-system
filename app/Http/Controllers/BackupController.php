<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    protected BackupService $backup;

    public function __construct(BackupService $backup)
    {
        $this->backup = $backup;
    }

    public function index()
    {
        $backups = $this->backup->list();
        return view('backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            $filename = $this->backup->create();
            return redirect('/backups')->with('success', "Backup created: $filename");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Backup failed: ' . $e->getMessage()]);
        }
    }

    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        if (!file_exists($path)) abort(404);

        return response()->download($path);
    }

    public function destroy($filename)
    {
        if ($this->backup->delete($filename)) {
            return redirect('/backups')->with('success', 'Backup deleted.');
        }
        return back()->withErrors(['error' => 'File not found.']);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'confirm'  => 'required|accepted',
        ]);

        try {
            $this->backup->restore($request->filename);
            return redirect('/backups')->with('success', 'Database restored from backup.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Restore failed: ' . $e->getMessage()]);
        }
    }

    public function cleanup()
    {
        $days = (int) setting('backup_retention_days', 30);
        $count = $this->backup->cleanup($days);

        return redirect('/backups')->with('success',
            $count > 0 ? "$count old backups deleted." : "No old backups to delete."
        );
    }
}