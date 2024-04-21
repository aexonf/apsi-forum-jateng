<?php

namespace App\Http\Controllers\Back;

use App\Exports\AdminExport;
use App\Exports\FormatImportAdminExport;
use App\Http\Controllers\Controller;
use App\Imports\AdminImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class AdminManagementController extends Controller
{

    // TODO: crud admin

    public function index(Request $request)
    {
        return view("pages.admin-management.index", [
            "admins" => User::where("role", "admin")->get(),
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            "username" => "required",
            "password" => "required",
        ]);

        $user = User::create([
            "role" => "admin",
            "username" => $request->username,
            "password" => Hash::make($request->password),
        ]);

        if ($user) {
            return redirect()->back()->with("success", "Berhasil membuat admin");
        }
        return redirect()->back()->with("error", "Gagal membuat admin");
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $user->update([
            "username" => $request->username,
            "password" => Hash::make($request->password),
        ]);

        if ($user) {
            return redirect()->back()->with("success", "Berhasil mengubah admin");
        }
        return redirect()->back()->with("error", "Gagal mengubah admin");
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        if ($user) {
            return redirect()->back()->with("success", "Berhasil menghapus admin");
        }
        return redirect()->back()->with("error", "Gagal menghapus admin");
    }

    public function downloadFormat()
    {
        return Excel::download(new FormatImportAdminExport, 'Apsi Forum Jateng - Format Import Admin.xlsx');
    }

    public function import()
    {
        try {
            Excel::import(new AdminImport, request()->file('admin'));

            return redirect()->back()->with('success', 'Data Admin berhasil diimport!');
        } catch (\Throwable $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', "Data Admin gagal diimport!");
        }
    }

    public function export()
    {
        return Excel::download(new AdminExport, 'Apsi Forum Jateng - Export Admin.xlsx');
    }
}
