<?php

namespace App\Exports;

use App\Models\CommunicationList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentExport implements FromCollection,WithHeadings
{

    public function __construct()
    {

    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'id',
            'question_name',
            'answers',
        ];
    } 
    public function collection()
    {
        return CommunicationList::select('id','question_name','answers')->where('deleted_at','0')->get();
    }
}
