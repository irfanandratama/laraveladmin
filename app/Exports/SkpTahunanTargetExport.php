<?php

namespace App\Exports;

use App\SkpTahunanHeader;
use App\SkpTahunanLines;
use App\SatuanKegiatan;
use App\TugasTambahan;
use App\Kreativitas;
use App\User;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;

class SkpTahunanTargetExport extends DefaultValueBinder implements FromView, WithCustomValueBinder, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $id;

    public function __construct($id)
    {   
        $this->id = $id;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function view(): View
    {
        $id = $this->id;
        $skpheader = SkpTahunanHeader::find($id);
        $skplines = SkpTahunanLines::where('skp_tahunan_header_id', $id)->get();
        $satuan = SatuanKegiatan::all();
        $user = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $skpheader->user_id)->first();
        $user_atasan = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $user->atasan_1_id)->first();
        $tugass = TugasTambahan::where('skp_tahunan_header_id', $id)->get();
        $kreativitas = Kreativitas::where('skp_tahunan_header_id', $id)->get();

        return view('exports.form-skp', compact(
            'skplines',
            'satuan',
            'user',
            'user_atasan',
            'tugass',
            'kreativitas'
        ));
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell == 'C6' || $cell == 'H6') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class  => function(BeforeExport $event) {
                $event->writer->setCreator('Sistem Informasi SKP');
            },
            BeforeSheet::class => function(BeforeSheet $event)
            {
                
            },
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                $event->sheet->mergeCells('A1:K1');
                $event->sheet->setCellValue('A1', "FORMULIR SASARAN KINERJA");

                $event->sheet->mergeCells('A2:K2');
                $event->sheet->setCellValue('A2', "PEGAWAI NEGERI SIPIL");

                $event->sheet->styleCells(
                    'A1:A2',
                    [
                        'alignment' => [
                            'horizontal' =>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                                'color' => ['argb' => 'FFFFFFFF'],
                            ],
                        ]
                    ]
                );

                $event->sheet->styleCells(
                    'F10',
                    [
                        'alignment' => [
                            'horizontal' =>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ]
                    ]
                );
                // $lastColumn = chr(ord($event->sheet->getDelegate()->getHighestColumn()) - 2);

                // $event->sheet->mergeCells('A' . ($event->sheet->getDelegate()->getHighestRow() + 1) . ':' . $lastColumn . ($event->sheet->getDelegate()->getHighestRow() + 1));
                // $event->sheet->setCellValue('A' . $event->sheet->getDelegate()->getHighestRow(), 'Total Pemasukan');
                // $event->sheet->setCellValue('E' . $event->sheet->getDelegate()->getHighestRow(), "=SUM(E2:E{$event->sheet->getDelegate()->getHighestRow()})");
            },
        ];
    }

}
