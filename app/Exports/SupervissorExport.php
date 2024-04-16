<?php

namespace App\Exports;

use App\Models\Supervisors;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SupervissorExport implements FromView, ShouldAutoSize
{



    public function view(): View
    {
        return view("pages.supervisor.format-export", [
            "data" => Supervisors::with("user")->get(),
        ]);
    }
}
