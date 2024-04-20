<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PublicationController extends Controller
{

    public function index(Request $request)
    {
        return view("pages.publication.index", [
            "data" => Publication::all()
        ]);
    }

    public function create(Request $request)
    {

        $file = $request->file('file');

        $fileExtension = $file->getClientOriginalExtension();

        $fileName = 'file-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $fileExtension;

        $file->storeAs('public', $fileName);

        $publication = Publication::create([
            "title" => $request->title,
            "file_url" => $fileName,
            "download_count" => 0
        ]);

        if ($publication) {
            return redirect()->back()->with("success", "Berhasil membuat data publikasi");
        }
        return redirect()->back()->with("error", "Gagal membuat data publikasi");
    }

    public function update($id, Request $request)
    {
        $publication = Publication::findOrFail($id);

        $newFile = null;

        if ($request->hasFile('file')) {
            Storage::delete($publication->file_url);

            $file = $request->file('file');

            $fileExtension = $file->getClientOriginalExtension();

            $fileName = 'file-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $fileExtension;

            $file->storeAs('public', $fileName);

            $newFile = $fileName;

            $publication->update([
                "title" => $request->title,
                "file_url" => $newFile,
            ]);
        } else {
            $publication->update([
                "title" => $request->title,
            ]);
        }


        if ($publication) {
            return redirect()->back()->with("success", "Berhasil mengubah data publikasi");
        }
        return redirect()->back()->with("error", "Gagal mengubah data publikasi");
    }



    public function delete($id)
    {
        $publication = Publication::findOrFail($id);

        if ($publication->file_url) {
            Storage::delete('public/' . $publication->file_url);
        }

        $deleted = $publication->delete();

        if ($deleted) {
            return redirect()->back()->with("success", "Berhasil menghapus data publikasi");
        }
        return redirect()->back()->with("error", "Gagal menghapus data publikasi");
    }
}
