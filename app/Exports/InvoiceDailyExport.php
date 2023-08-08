<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Model\Product;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class InvoiceDailyExport implements FromView, WithEvents, ShouldAutoSize,WithColumnFormatting
{
    public function __construct($view, $records, $search, $date_range)
    {
        $this->view = $view;
        $this->records = $records;
        $this->search = $search;
        $this->date_range = $date_range;
    }

    public function view(): View
    {
        return view($this->view, [
            'records' => $this->records,
            'dateRange' => $this->date_range,
            'search' => $this->search,
            'product' => Product::get_product_name_list(),
        ]);
    }

    public function columnFormats(): array
    {
        return[
            'B' => NumberFormat::FORMAT_NUMBER_00,
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_NUMBER_00,
            'M' => NumberFormat::FORMAT_NUMBER_00,
            'N' => NumberFormat::FORMAT_NUMBER_00,
            'O' => NumberFormat::FORMAT_NUMBER_00,
            'P' => NumberFormat::FORMAT_NUMBER_00,
            'Q' => NumberFormat::FORMAT_NUMBER_00,
            'R' => NumberFormat::FORMAT_NUMBER_00,
            'S' => NumberFormat::FORMAT_NUMBER_00,
            'T' => NumberFormat::FORMAT_NUMBER_00,
            'U' => NumberFormat::FORMAT_NUMBER_00,
            'V' => NumberFormat::FORMAT_NUMBER_00,
            'W' => NumberFormat::FORMAT_NUMBER_00,
            'X' => NumberFormat::FORMAT_NUMBER_00,
            'Y' => NumberFormat::FORMAT_NUMBER_00,
            'Z' => NumberFormat::FORMAT_NUMBER_00,
            'AA' => NumberFormat::FORMAT_NUMBER_00,
            'AB' => NumberFormat::FORMAT_NUMBER_00,
            'AC' => NumberFormat::FORMAT_NUMBER_00,
            'AD' => NumberFormat::FORMAT_NUMBER_00,
            'AE' => NumberFormat::FORMAT_NUMBER_00,
            'AF' => NumberFormat::FORMAT_NUMBER_00,
            'AG' => NumberFormat::FORMAT_NUMBER_00,
            'AH' => NumberFormat::FORMAT_NUMBER_00,
            'AI' => NumberFormat::FORMAT_NUMBER_00,
            'AJ' => NumberFormat::FORMAT_NUMBER_00,
            'AK' => NumberFormat::FORMAT_NUMBER_00,
            'AL' => NumberFormat::FORMAT_NUMBER_00,
            'AM' => NumberFormat::FORMAT_NUMBER_00,
            'AN' => NumberFormat::FORMAT_NUMBER_00,
            'AO' => NumberFormat::FORMAT_NUMBER_00,
            'AP' => NumberFormat::FORMAT_NUMBER_00,
            'AQ' => NumberFormat::FORMAT_NUMBER_00,
            'AR' => NumberFormat::FORMAT_NUMBER_00,
            'AS' => NumberFormat::FORMAT_NUMBER_00,
            'AT' => NumberFormat::FORMAT_NUMBER_00,
            'AU' => NumberFormat::FORMAT_NUMBER_00,
            'AV' => NumberFormat::FORMAT_NUMBER_00,
            'AW' => NumberFormat::FORMAT_NUMBER_00,
            'AX' => NumberFormat::FORMAT_NUMBER_00,
            'AY' => NumberFormat::FORMAT_NUMBER_00,
            'AZ' => NumberFormat::FORMAT_NUMBER_00,
            'BA' => NumberFormat::FORMAT_NUMBER_00,
            'BB' => NumberFormat::FORMAT_NUMBER_00,
            'BC' => NumberFormat::FORMAT_NUMBER_00,
            'BD' => NumberFormat::FORMAT_NUMBER_00,
            'BE' => NumberFormat::FORMAT_NUMBER_00,
            'BF' => NumberFormat::FORMAT_NUMBER_00,
            'BG' => NumberFormat::FORMAT_NUMBER_00,
            'BH' => NumberFormat::FORMAT_NUMBER_00,
            'BI' => NumberFormat::FORMAT_NUMBER_00,
            'BJ' => NumberFormat::FORMAT_NUMBER_00,
            'BK' => NumberFormat::FORMAT_NUMBER_00,
            'BL' => NumberFormat::FORMAT_NUMBER_00,
            'BM' => NumberFormat::FORMAT_NUMBER_00,
            'BN' => NumberFormat::FORMAT_NUMBER_00,
            'BO' => NumberFormat::FORMAT_NUMBER_00,
            'BP' => NumberFormat::FORMAT_NUMBER_00,
            'BQ' => NumberFormat::FORMAT_NUMBER_00,
            'BR' => NumberFormat::FORMAT_NUMBER_00,
            'BS' => NumberFormat::FORMAT_NUMBER_00,
            'BT' => NumberFormat::FORMAT_NUMBER_00,
            'BU' => NumberFormat::FORMAT_NUMBER_00,
            'BV' => NumberFormat::FORMAT_NUMBER_00,
            'BW' => NumberFormat::FORMAT_NUMBER_00,
            'BX' => NumberFormat::FORMAT_NUMBER_00,
            'BY' => NumberFormat::FORMAT_NUMBER_00,
            'BZ' => NumberFormat::FORMAT_NUMBER_00,
            'CA' => NumberFormat::FORMAT_NUMBER_00,
            'CB' => NumberFormat::FORMAT_NUMBER_00,
            'CC' => NumberFormat::FORMAT_NUMBER_00,
            'CD' => NumberFormat::FORMAT_NUMBER_00,
            'CE' => NumberFormat::FORMAT_NUMBER_00,
            'CF' => NumberFormat::FORMAT_NUMBER_00,
            'CG' => NumberFormat::FORMAT_NUMBER_00,
            'CH' => NumberFormat::FORMAT_NUMBER_00,
            'CI' => NumberFormat::FORMAT_NUMBER_00,
            'CJ' => NumberFormat::FORMAT_NUMBER_00,
            'CK' => NumberFormat::FORMAT_NUMBER_00,
            'CL' => NumberFormat::FORMAT_NUMBER_00,
            'CM' => NumberFormat::FORMAT_NUMBER_00,
            'CN' => NumberFormat::FORMAT_NUMBER_00,
            'CO' => NumberFormat::FORMAT_NUMBER_00,
            'CP' => NumberFormat::FORMAT_NUMBER_00,
            'CQ' => NumberFormat::FORMAT_NUMBER_00,
            'CR' => NumberFormat::FORMAT_NUMBER_00,
            'CS' => NumberFormat::FORMAT_NUMBER_00,
            'CT' => NumberFormat::FORMAT_NUMBER_00,
            'CU' => NumberFormat::FORMAT_NUMBER_00,
            'CV' => NumberFormat::FORMAT_NUMBER_00,
            'CW' => NumberFormat::FORMAT_NUMBER_00,
            'CX' => NumberFormat::FORMAT_NUMBER_00,
            'CY' => NumberFormat::FORMAT_NUMBER_00,
            'CZ' => NumberFormat::FORMAT_NUMBER_00,
            'DA' => NumberFormat::FORMAT_NUMBER_00,
            'DB' => NumberFormat::FORMAT_NUMBER_00,
            'DC' => NumberFormat::FORMAT_NUMBER_00,
            'DD' => NumberFormat::FORMAT_NUMBER_00,
            'DE' => NumberFormat::FORMAT_NUMBER_00,
            'DF' => NumberFormat::FORMAT_NUMBER_00,
            'DG' => NumberFormat::FORMAT_NUMBER_00,
            'DH' => NumberFormat::FORMAT_NUMBER_00,
            'DI' => NumberFormat::FORMAT_NUMBER_00,
            'DJ' => NumberFormat::FORMAT_NUMBER_00,
            'DK' => NumberFormat::FORMAT_NUMBER_00,
            'DL' => NumberFormat::FORMAT_NUMBER_00,
            'DM' => NumberFormat::FORMAT_NUMBER_00,
            'DN' => NumberFormat::FORMAT_NUMBER_00,
            'DO' => NumberFormat::FORMAT_NUMBER_00,
            'DP' => NumberFormat::FORMAT_NUMBER_00,
            'DQ' => NumberFormat::FORMAT_NUMBER_00,
            'DR' => NumberFormat::FORMAT_NUMBER_00,
            'DS' => NumberFormat::FORMAT_NUMBER_00,
            'DT' => NumberFormat::FORMAT_NUMBER_00,
            'DU' => NumberFormat::FORMAT_NUMBER_00,
            'DV' => NumberFormat::FORMAT_NUMBER_00,
            'DW' => NumberFormat::FORMAT_NUMBER_00,
            'DX' => NumberFormat::FORMAT_NUMBER_00,
            'DY' => NumberFormat::FORMAT_NUMBER_00,
            'DZ' => NumberFormat::FORMAT_NUMBER_00,
            'EA' => NumberFormat::FORMAT_NUMBER_00,
            'EB' => NumberFormat::FORMAT_NUMBER_00,
            'EC' => NumberFormat::FORMAT_NUMBER_00,
            'ED' => NumberFormat::FORMAT_NUMBER_00,
            'EE' => NumberFormat::FORMAT_NUMBER_00,
            'EF' => NumberFormat::FORMAT_NUMBER_00,
            'EG' => NumberFormat::FORMAT_NUMBER_00,
            'EH' => NumberFormat::FORMAT_NUMBER_00,
            'EI' => NumberFormat::FORMAT_NUMBER_00,
            'EJ' => NumberFormat::FORMAT_NUMBER_00,
            'EK' => NumberFormat::FORMAT_NUMBER_00,
            'EL' => NumberFormat::FORMAT_NUMBER_00,
            'EM' => NumberFormat::FORMAT_NUMBER_00,
            'EN' => NumberFormat::FORMAT_NUMBER_00,
            'EO' => NumberFormat::FORMAT_NUMBER_00,
            'EP' => NumberFormat::FORMAT_NUMBER_00,
            'EQ' => NumberFormat::FORMAT_NUMBER_00,
            'ER' => NumberFormat::FORMAT_NUMBER_00,
            'ES' => NumberFormat::FORMAT_NUMBER_00,
            'ET' => NumberFormat::FORMAT_NUMBER_00,
            'EU' => NumberFormat::FORMAT_NUMBER_00,
            'EV' => NumberFormat::FORMAT_NUMBER_00,
            'EW' => NumberFormat::FORMAT_NUMBER_00,
            'EX' => NumberFormat::FORMAT_NUMBER_00,
            'EY' => NumberFormat::FORMAT_NUMBER_00,
            'EZ' => NumberFormat::FORMAT_NUMBER_00,
            'FA' => NumberFormat::FORMAT_NUMBER_00,
            'FB' => NumberFormat::FORMAT_NUMBER_00,
            'FC' => NumberFormat::FORMAT_NUMBER_00,
            'FD' => NumberFormat::FORMAT_NUMBER_00,
            'FE' => NumberFormat::FORMAT_NUMBER_00,
            'FF' => NumberFormat::FORMAT_NUMBER_00,
            'FG' => NumberFormat::FORMAT_NUMBER_00,
            'FH' => NumberFormat::FORMAT_NUMBER_00,
            'FI' => NumberFormat::FORMAT_NUMBER_00,
            'FJ' => NumberFormat::FORMAT_NUMBER_00,
            'FK' => NumberFormat::FORMAT_NUMBER_00,
            'FL' => NumberFormat::FORMAT_NUMBER_00,
            'FM' => NumberFormat::FORMAT_NUMBER_00,
            'FN' => NumberFormat::FORMAT_NUMBER_00,
            'FO' => NumberFormat::FORMAT_NUMBER_00,
            'FP' => NumberFormat::FORMAT_NUMBER_00,
            'FQ' => NumberFormat::FORMAT_NUMBER_00,
            'FR' => NumberFormat::FORMAT_NUMBER_00,
            'FS' => NumberFormat::FORMAT_NUMBER_00,
            'FT' => NumberFormat::FORMAT_NUMBER_00,
            'FU' => NumberFormat::FORMAT_NUMBER_00,
            'FV' => NumberFormat::FORMAT_NUMBER_00,
            'FW' => NumberFormat::FORMAT_NUMBER_00,
            'FX' => NumberFormat::FORMAT_NUMBER_00,
            'FY' => NumberFormat::FORMAT_NUMBER_00,
            'FZ' => NumberFormat::FORMAT_NUMBER_00,
            'GA' => NumberFormat::FORMAT_NUMBER_00,
            'GB' => NumberFormat::FORMAT_NUMBER_00,
            'GC' => NumberFormat::FORMAT_NUMBER_00,
            'GD' => NumberFormat::FORMAT_NUMBER_00,
            'GE' => NumberFormat::FORMAT_NUMBER_00,
            'GF' => NumberFormat::FORMAT_NUMBER_00,
            'GG' => NumberFormat::FORMAT_NUMBER_00,
            'GH' => NumberFormat::FORMAT_NUMBER_00,
            'GI' => NumberFormat::FORMAT_NUMBER_00,
            'GJ' => NumberFormat::FORMAT_NUMBER_00,
            'GK' => NumberFormat::FORMAT_NUMBER_00,
            'GL' => NumberFormat::FORMAT_NUMBER_00,
            'GM' => NumberFormat::FORMAT_NUMBER_00,
            'GN' => NumberFormat::FORMAT_NUMBER_00,
            'GO' => NumberFormat::FORMAT_NUMBER_00,
            'GP' => NumberFormat::FORMAT_NUMBER_00,
            'GQ' => NumberFormat::FORMAT_NUMBER_00,
            'GR' => NumberFormat::FORMAT_NUMBER_00,
            'GS' => NumberFormat::FORMAT_NUMBER_00,
            'GT' => NumberFormat::FORMAT_NUMBER_00,
            'GU' => NumberFormat::FORMAT_NUMBER_00,
            'GV' => NumberFormat::FORMAT_NUMBER_00,
            'GW' => NumberFormat::FORMAT_NUMBER_00,
            'GX' => NumberFormat::FORMAT_NUMBER_00,
            'GY' => NumberFormat::FORMAT_NUMBER_00,
            'GZ' => NumberFormat::FORMAT_NUMBER_00,
            'HA' => NumberFormat::FORMAT_NUMBER_00,
            'HB' => NumberFormat::FORMAT_NUMBER_00,
            'HC' => NumberFormat::FORMAT_NUMBER_00,
            'HD' => NumberFormat::FORMAT_NUMBER_00,
            'HE' => NumberFormat::FORMAT_NUMBER_00,
            'HF' => NumberFormat::FORMAT_NUMBER_00,
            'HG' => NumberFormat::FORMAT_NUMBER_00,
            'HH' => NumberFormat::FORMAT_NUMBER_00,
            'HI' => NumberFormat::FORMAT_NUMBER_00,
            'HJ' => NumberFormat::FORMAT_NUMBER_00,
            'HK' => NumberFormat::FORMAT_NUMBER_00,
            'HL' => NumberFormat::FORMAT_NUMBER_00,
            'HM' => NumberFormat::FORMAT_NUMBER_00,
            'HN' => NumberFormat::FORMAT_NUMBER_00,
            'HO' => NumberFormat::FORMAT_NUMBER_00,
            'HP' => NumberFormat::FORMAT_NUMBER_00,
            'HQ' => NumberFormat::FORMAT_NUMBER_00,
            'HR' => NumberFormat::FORMAT_NUMBER_00,
            'HS' => NumberFormat::FORMAT_NUMBER_00,
            'HT' => NumberFormat::FORMAT_NUMBER_00,
            'HU' => NumberFormat::FORMAT_NUMBER_00,
            'HV' => NumberFormat::FORMAT_NUMBER_00,
            'HW' => NumberFormat::FORMAT_NUMBER_00,
            'HX' => NumberFormat::FORMAT_NUMBER_00,
            'HY' => NumberFormat::FORMAT_NUMBER_00,
            'HZ' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }


    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach ($event->sheet->getDelegate()->getColumnDimensions() as $key => $value) {
                        $event->sheet->getDelegate()->getColumnDimension($key)->setAutoSize(true);
                }
            }
        ];
    }
}
