<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FormatImportSupervissorExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view("pages.admin-management.format-export");
    }
}
