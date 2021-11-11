<?php

namespace App\Exports;

use App\User;
use App\SkpTahunanHeader;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\Sheets\DataSkpSheet;

class SkpExport implements WithMultipleSheets
{

    use Exportable;

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function query(): array
    {

        $skp = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
            ->where('skp_tahunan_header.id', $this->id)
            ->first(['skp_tahunan_header.*', 'users.name']);
        $user = User::find($skp->user_id);

        $sheets = [];

        // for ($month = 1; $month <= 12; $month++) {
        // }
        $sheets[] = new DataSkpSheet($skp->user_id, $user);

        return $sheets;
    }
    
}
