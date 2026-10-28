<?php

namespace App\Exports;

use App\Models\DailyRaasiPalanList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportDailyRaasiPalanList implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DailyRaasiPalanList::select(
            [
                'id',
                'date',
                'lakknam',
                'content',
                'langugae_code',
                'langugae_id',
            ]
            )->where('deleted_at',0)->get();
    }

    public function headings(): array
    {
        return [
                'id',
                'date',
                'lakknam',
                'content',
                'langugae_code',
                'langugae_id',
            ];
    }
}
?>
