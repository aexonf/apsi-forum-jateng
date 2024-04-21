<?php

namespace App\Http\Controllers\Back;

use App\Exports\FormatImportSupervissorExport;
use App\Exports\SupervissorExport;
use App\Http\Controllers\Controller;
use App\Imports\SupervissorImport;
use App\Models\Supervisors;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SupervisorController extends Controller
{
    public function index(Request $request)
    {
        $supervisorQuery = Supervisors::query();

        if ($request->has("level")) {
            $supervisorQuery->where("level", $request->level);
        }

        $supervisors = $supervisorQuery->get();

        return view("pages.supervisor.index", [
            "data" => $supervisors,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            "id_number" => "required",
            "name" => "required",
            "level" => "required",
            "username" => "required",
            "password" => "required"
        ]);

        $nameImage = null;

        $user = User::create([
            "username" => $request->username,
            "password" => Hash::make($request->password),
            "role" => "supervisor",
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $imageName = 'image-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $imageName);
            $nameImage = $imageName;
        }

        $supervisor = Supervisors::create([
            "id_number" => $request->id_number,
            "name" => $request->name,
            "email" => $request->email,
            "phone_number" => $request->phone_number,
            "label" => $request->label,
            "level" => $request->level,
            "user_id" => $user->id,
            "img_url" => $nameImage,
        ]);

        if ($supervisor) {
            return redirect()->back()->with("success", "Pengawas berhasil di buat");
        }
        return redirect()->back()->with("error", "Pengawas gagal di buat");
    }

    public function update(Request $request, $id)
    {
        $supervisor = Supervisors::findOrFail($id);
        $newImage = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $imageName = 'image-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $imageName);

            $newImage = $imageName;
        }

        $supervisor->update([
            "id_number" => $request->id_number,
            "name" => $request->name,
            "email" => $request->email,
            "phone_number" => $request->phone_number,
            "label" => $request->label,
            "level" => $request->level,
            "img_url" => $newImage,
        ]);

        $supervisor->user()->update([
            "username" => $request->username,
            "password" => Hash::make($request->password),
        ]);

        return redirect()->back()->with("success", "Informasi Pengawas berhasil diperbarui");
    }

    public function delete($id)
    {
        $supervisor = Supervisors::findOrFail($id);

        // Hapus gambar jika ada
        if ($supervisor->img_url) {
            Storage::delete('public/' . $supervisor->img_url);
        }

        // Hapus supervisor
        $deleted =  $supervisor->delete();

        if ($deleted) {
            return redirect()->back()->with("success", "Pengawas berhasil dihapus");
        }
        return redirect()->back()->with("eror", "Pengawas gagal dihapus");
    }


    public function downloadFormat()
    {
        return Excel::download(new FormatImportSupervissorExport, 'Apsi Forum Jateng - Import Format Pengawas.xlsx');
    }

    public function import()
    {
        try {
            Excel::import(new SupervissorImport, request()->file('supervissor'));

            return redirect()->back()->with('success', 'Data Pengawas berhasil diimport!');
        } catch (\Throwable $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', "Data Pengawas gagal diimport!");
        }
    }

    public function export()
    {
        return Excel::download(new SupervissorExport, 'Apsi Forum Jateng - Export Pengawas.xlsx');
    }
}
