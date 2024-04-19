<?php

namespace App\Exports;

use Illuminate\Contracts\View\View as ViewView;
use Maatwebsite\Excel\Concerns\FromView;

class FormatImportAdminExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): ViewView
    {
        return view("pages.admin-management.format-import");
    }
}
