<?php

namespace App\Exports;

use App\Models\Organization;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;

class OrgExport implements FromCollection, WithHeadings, WithMapping,WithEvents
{
    use Exportable;

    protected $org;
    protected $title;
    protected $headings;
    


    public function __construct($org, $title)
    {
        $this->org = $org;
        $this->title = $title;
    }

    public function headings():array{
        return[
        ['Office of Student Affairs'],

        ['Accredited Campus Organizations in University of Southern Mindanao'],
        [$this->title],
        [''],
        [
            'REGISTRATION #',
            'CAMPUS ORGANIZATION NAME',
            'CATEGORY',
            'ORG. 1ST REG.',
            'ACRONYM',
            'ADVISER',
            'FILES',
        ]
    ];
    } 
    public function map($data): array
    {
        // dd($data);
        return [
          $data->registration_number,
          $data->name,
          $data->category->description,
          $data->organization_first_registered,
          $data->abbreviation,
          $data->adviser,
          $data->docs->count().' doc/s.',
           
        ];
    }

    public function collection()
    {
        return collect($this->org);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
               //Set the area cell to be vertically centered
                $event->sheet->getDelegate()->getStyle('A1:G1')->getAlignment()->setVertical('center');

                foreach (['A'=>20, 'B' => 40,'C' => 20,'D'=>20,'E'=>20,'F'=>20,'G'=>20] as $column => $width) {
                    $event->sheet->getDelegate()
                        ->getColumnDimension($column)
                        ->setWidth($width);
                }
                foreach (['A5:G5' =>'#000000'] as $region => $item) {
                    $event->sheet->getDelegate()->getStyle($region)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>Border::BORDER_THIN,
                                'color' => ['argb' => $item],
                            ],
                        ],
                    ]);
                }
                foreach (['A5:G5' =>true] as $region => $bool) {
                    $event->sheet->getDelegate()
                        ->getStyle($region)
                        ->getFont()
                        ->setBold($bool);
                }
                foreach (['A1:G1'=>16,'A2:G2'=>16,'A3:G3'=>14, 'A5:G5' => 12,'A5:Z1265' => 11] as $region => $value) {
                    $event->sheet->getDelegate()
                        ->getStyle($region)
                        ->getFont()
                        ->setSize($value);
                }
                $event->sheet->getDelegate()->setMergeCells(['A1:G1','A2:G2','A3:G3']);
                // $event->sheet->getDelegate()->setMergeCells(['A2:G2']);

                foreach (['A1:G1','A2:G2','A3:G3'] as $region => $item) {
                    $event->sheet->getDelegate()->getStyle($region)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            }

           
        ];
    }
}
