<?php

namespace App\Exports\Sheets;

use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use Maatwebsite\Excel\Events\AfterSheet;


class DataSkpSheet implements FromQuery, WithTitle
{
    private $user_id;
    private $user;

    public function __construct(int $user_id, User $user)
    {
        $this->user_id = $user_id;
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'DATA SKP';
    }

    public function query()
    {
        $id = $this->user_id;
        $user = $this->user;

        $user_bawahan = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $id)->get();
        $user_atasan1 = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $user->atasan_1_id)->get();
        $user_atasan2 = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $user->atasan_2_id)->get();

        return $user_bawahan->merge($user_atasan1)->merge($user_atasan2);
    }

    // public function map($user): array
    // {
    //     return [
            
    //         [],
    //         [],
    //         [
    //             '1',
    //             'YANG DINILAI',
    //         ],
    //         [
    //             '',
    //             'a.',
    //             'Nama',
    //             ':',
    //             $user->name
    //         ],
    //         [
    //             '',
    //             'b.',
    //             'NIP',
    //             ':',
    //             $user->nip
    //         ],
    //         [
    //             '',
    //             'c.',
    //             'Pangkat/ Gol.Ruang',
    //             ':',
    //             $user->pangkat->pangkat . ' ' . $user->pangkat->golongan .'/'. $user->pangkat->ruang
    //         ],
    //         [
    //             '',
    //             'd.',
    //             'Jabatan',
    //             ':',
    //             $user->jabatan
    //         ],
    //         [
    //             '',
    //             'e.',
    //             'Unit Kerja',
    //             ':',
    //             $user->satuan_kerja->satuan_kerja
    //         ],
    //     ]
    //      ;
    // }
}
