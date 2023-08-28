<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyExpenseLandTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_expense_land')->delete();
        
        \DB::table('tbl_company_expense_land')->insert(array (
            0 => 
            array (
                'company_expense_land_id' => 1,
                'company_expense_id' => 1,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '100.00',
            ),
            1 => 
            array (
                'company_expense_land_id' => 2,
                'company_expense_id' => 2,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '150.00',
            ),
            2 => 
            array (
                'company_expense_land_id' => 3,
                'company_expense_id' => 3,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '60.00',
            ),
            3 => 
            array (
                'company_expense_land_id' => 1761,
                'company_expense_id' => 1345,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '150.00',
            ),
            4 => 
            array (
                'company_expense_land_id' => 1763,
                'company_expense_id' => 1346,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '143.00',
            ),
            5 => 
            array (
                'company_expense_land_id' => 1764,
                'company_expense_id' => 1347,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '16.01',
            ),
            6 => 
            array (
                'company_expense_land_id' => 1765,
                'company_expense_id' => 1347,
                'company_land_id' => 4,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '0.00',
            ),
            7 => 
            array (
                'company_expense_land_id' => 1766,
                'company_expense_id' => 1347,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '3.32',
            ),
            8 => 
            array (
                'company_expense_land_id' => 1767,
                'company_expense_id' => 1347,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '6.13',
            ),
            9 => 
            array (
                'company_expense_land_id' => 1776,
                'company_expense_id' => 1350,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '31.65',
            ),
            10 => 
            array (
                'company_expense_land_id' => 1777,
                'company_expense_id' => 1350,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '0.00',
            ),
            11 => 
            array (
                'company_expense_land_id' => 1778,
                'company_expense_id' => 1350,
                'company_land_id' => 11,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '6.56',
            ),
            12 => 
            array (
                'company_expense_land_id' => 1779,
                'company_expense_id' => 1350,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '12.12',
            ),
            13 => 
            array (
                'company_expense_land_id' => 1780,
                'company_expense_id' => 1351,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '173.78',
            ),
            14 => 
            array (
                'company_expense_land_id' => 1781,
                'company_expense_id' => 1351,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '0.00',
            ),
            15 => 
            array (
                'company_expense_land_id' => 1782,
                'company_expense_id' => 1351,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '36.02',
            ),
            16 => 
            array (
                'company_expense_land_id' => 1783,
                'company_expense_id' => 1351,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '66.56',
            ),
            17 => 
            array (
                'company_expense_land_id' => 1784,
                'company_expense_id' => 1352,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 270,
                'company_expense_land_total_price' => '19.54',
            ),
            18 => 
            array (
                'company_expense_land_id' => 1785,
                'company_expense_id' => 1352,
                'company_land_id' => 13,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '30.93',
            ),
            19 => 
            array (
                'company_expense_land_id' => 1786,
                'company_expense_id' => 1352,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '82.44',
            ),
            20 => 
            array (
                'company_expense_land_id' => 1787,
                'company_expense_id' => 1352,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            21 => 
            array (
                'company_expense_land_id' => 1788,
                'company_expense_id' => 1353,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '6.83',
            ),
            22 => 
            array (
                'company_expense_land_id' => 1789,
                'company_expense_id' => 1353,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '0.00',
            ),
            23 => 
            array (
                'company_expense_land_id' => 1790,
                'company_expense_id' => 1353,
                'company_land_id' => 11,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '1.42',
            ),
            24 => 
            array (
                'company_expense_land_id' => 1791,
                'company_expense_id' => 1353,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '2.61',
            ),
            25 => 
            array (
                'company_expense_land_id' => 1792,
                'company_expense_id' => 1349,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '487.95',
            ),
            26 => 
            array (
                'company_expense_land_id' => 1793,
                'company_expense_id' => 1349,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '101.13',
            ),
            27 => 
            array (
                'company_expense_land_id' => 1794,
                'company_expense_id' => 1349,
                'company_land_id' => 13,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            28 => 
            array (
                'company_expense_land_id' => 1795,
                'company_expense_id' => 1349,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '186.88',
            ),
            29 => 
            array (
                'company_expense_land_id' => 1796,
                'company_expense_id' => 1348,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '72.42',
            ),
            30 => 
            array (
                'company_expense_land_id' => 1797,
                'company_expense_id' => 1348,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '15.01',
            ),
            31 => 
            array (
                'company_expense_land_id' => 1798,
                'company_expense_id' => 1348,
                'company_land_id' => 9,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            32 => 
            array (
                'company_expense_land_id' => 1799,
                'company_expense_id' => 1348,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '27.74',
            ),
            33 => 
            array (
                'company_expense_land_id' => 1804,
                'company_expense_id' => 1354,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '1197.15',
            ),
            34 => 
            array (
                'company_expense_land_id' => 1805,
                'company_expense_id' => 1354,
                'company_land_id' => 9,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '248.13',
            ),
            35 => 
            array (
                'company_expense_land_id' => 1806,
                'company_expense_id' => 1354,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            36 => 
            array (
                'company_expense_land_id' => 1807,
                'company_expense_id' => 1354,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '458.51',
            ),
            37 => 
            array (
                'company_expense_land_id' => 1820,
                'company_expense_id' => 1364,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '63.34',
            ),
            38 => 
            array (
                'company_expense_land_id' => 1821,
                'company_expense_id' => 1364,
                'company_land_id' => 8,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '0.00',
            ),
            39 => 
            array (
                'company_expense_land_id' => 1822,
                'company_expense_id' => 1364,
                'company_land_id' => 11,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '13.13',
            ),
            40 => 
            array (
                'company_expense_land_id' => 1823,
                'company_expense_id' => 1364,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '24.26',
            ),
            41 => 
            array (
                'company_expense_land_id' => 1824,
                'company_expense_id' => 1365,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '111.72',
            ),
            42 => 
            array (
                'company_expense_land_id' => 1825,
                'company_expense_id' => 1365,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '0.00',
            ),
            43 => 
            array (
                'company_expense_land_id' => 1826,
                'company_expense_id' => 1365,
                'company_land_id' => 12,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '23.15',
            ),
            44 => 
            array (
                'company_expense_land_id' => 1827,
                'company_expense_id' => 1365,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '42.79',
            ),
            45 => 
            array (
                'company_expense_land_id' => 1832,
                'company_expense_id' => 1366,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '36.00',
            ),
            46 => 
            array (
                'company_expense_land_id' => 1833,
                'company_expense_id' => 1366,
                'company_land_id' => 14,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '7.46',
            ),
            47 => 
            array (
                'company_expense_land_id' => 1834,
                'company_expense_id' => 1366,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            48 => 
            array (
                'company_expense_land_id' => 1835,
                'company_expense_id' => 1366,
                'company_land_id' => 4,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '13.79',
            ),
            49 => 
            array (
                'company_expense_land_id' => 1840,
                'company_expense_id' => 1367,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '8.56',
            ),
            50 => 
            array (
                'company_expense_land_id' => 1841,
                'company_expense_id' => 1367,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '1.78',
            ),
            51 => 
            array (
                'company_expense_land_id' => 1842,
                'company_expense_id' => 1367,
                'company_land_id' => 11,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            52 => 
            array (
                'company_expense_land_id' => 1843,
                'company_expense_id' => 1367,
                'company_land_id' => 14,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '3.28',
            ),
            53 => 
            array (
                'company_expense_land_id' => 1852,
                'company_expense_id' => 1368,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '124.13',
            ),
            54 => 
            array (
                'company_expense_land_id' => 1853,
                'company_expense_id' => 1368,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '25.73',
            ),
            55 => 
            array (
                'company_expense_land_id' => 1854,
                'company_expense_id' => 1368,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            56 => 
            array (
                'company_expense_land_id' => 1855,
                'company_expense_id' => 1368,
                'company_land_id' => 9,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '47.54',
            ),
            57 => 
            array (
                'company_expense_land_id' => 1856,
                'company_expense_id' => 1369,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '93.10',
            ),
            58 => 
            array (
                'company_expense_land_id' => 1857,
                'company_expense_id' => 1369,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '19.30',
            ),
            59 => 
            array (
                'company_expense_land_id' => 1858,
                'company_expense_id' => 1369,
                'company_land_id' => 15,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            60 => 
            array (
                'company_expense_land_id' => 1859,
                'company_expense_id' => 1369,
                'company_land_id' => 8,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '35.66',
            ),
            61 => 
            array (
                'company_expense_land_id' => 1860,
                'company_expense_id' => 1370,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '63.03',
            ),
            62 => 
            array (
                'company_expense_land_id' => 1861,
                'company_expense_id' => 1370,
                'company_land_id' => 8,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '116.48',
            ),
            63 => 
            array (
                'company_expense_land_id' => 1862,
                'company_expense_id' => 1370,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '310.49',
            ),
            64 => 
            array (
                'company_expense_land_id' => 1869,
                'company_expense_id' => 1372,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '1613.66',
            ),
            65 => 
            array (
                'company_expense_land_id' => 1870,
                'company_expense_id' => 1372,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '334.46',
            ),
            66 => 
            array (
                'company_expense_land_id' => 1871,
                'company_expense_id' => 1372,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            67 => 
            array (
                'company_expense_land_id' => 1872,
                'company_expense_id' => 1372,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '618.03',
            ),
            68 => 
            array (
                'company_expense_land_id' => 1873,
                'company_expense_id' => 1373,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '147.71',
            ),
            69 => 
            array (
                'company_expense_land_id' => 1874,
                'company_expense_id' => 1373,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '0.00',
            ),
            70 => 
            array (
                'company_expense_land_id' => 1875,
                'company_expense_id' => 1373,
                'company_land_id' => 8,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '30.62',
            ),
            71 => 
            array (
                'company_expense_land_id' => 1876,
                'company_expense_id' => 1373,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '56.57',
            ),
            72 => 
            array (
                'company_expense_land_id' => 1893,
                'company_expense_id' => 1378,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '70.32',
            ),
            73 => 
            array (
                'company_expense_land_id' => 1894,
                'company_expense_id' => 1378,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '0.00',
            ),
            74 => 
            array (
                'company_expense_land_id' => 1895,
                'company_expense_id' => 1378,
                'company_land_id' => 14,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '26.93',
            ),
            75 => 
            array (
                'company_expense_land_id' => 1896,
                'company_expense_id' => 1378,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '14.58',
            ),
            76 => 
            array (
                'company_expense_land_id' => 1897,
                'company_expense_id' => 1379,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '29.17',
            ),
            77 => 
            array (
                'company_expense_land_id' => 1898,
                'company_expense_id' => 1379,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '0.00',
            ),
            78 => 
            array (
                'company_expense_land_id' => 1899,
                'company_expense_id' => 1379,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '6.05',
            ),
            79 => 
            array (
                'company_expense_land_id' => 1900,
                'company_expense_id' => 1379,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '11.17',
            ),
            80 => 
            array (
                'company_expense_land_id' => 1901,
                'company_expense_id' => 1374,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 1042,
                'company_expense_land_total_price' => '699.56',
            ),
            81 => 
            array (
                'company_expense_land_id' => 1902,
                'company_expense_id' => 1374,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 414,
                'company_expense_land_total_price' => '142.02',
            ),
            82 => 
            array (
                'company_expense_land_id' => 1903,
                'company_expense_id' => 1374,
                'company_land_id' => 12,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            83 => 
            array (
                'company_expense_land_id' => 1904,
                'company_expense_id' => 1374,
                'company_land_id' => 9,
                'company_expense_land_total_tree' => 618,
                'company_expense_land_total_price' => '262.43',
            ),
            84 => 
            array (
                'company_expense_land_id' => 1905,
                'company_expense_id' => 1380,
                'company_land_id' => 14,
                'company_expense_land_total_tree' => 2,
                'company_expense_land_total_price' => '0.00',
            ),
            85 => 
            array (
                'company_expense_land_id' => 1906,
                'company_expense_id' => 1380,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '160.75',
            ),
            86 => 
            array (
                'company_expense_land_id' => 1907,
                'company_expense_id' => 1380,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '33.32',
            ),
            87 => 
            array (
                'company_expense_land_id' => 1908,
                'company_expense_id' => 1380,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '61.57',
            ),
            88 => 
            array (
                'company_expense_land_id' => 1909,
                'company_expense_id' => 1377,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '80.07',
            ),
            89 => 
            array (
                'company_expense_land_id' => 1910,
                'company_expense_id' => 1377,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '16.60',
            ),
            90 => 
            array (
                'company_expense_land_id' => 1911,
                'company_expense_id' => 1377,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            91 => 
            array (
                'company_expense_land_id' => 1912,
                'company_expense_id' => 1377,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '30.67',
            ),
            92 => 
            array (
                'company_expense_land_id' => 1913,
                'company_expense_id' => 1376,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '496.51',
            ),
            93 => 
            array (
                'company_expense_land_id' => 1914,
                'company_expense_id' => 1376,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '102.91',
            ),
            94 => 
            array (
                'company_expense_land_id' => 1915,
                'company_expense_id' => 1376,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            95 => 
            array (
                'company_expense_land_id' => 1916,
                'company_expense_id' => 1376,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '190.16',
            ),
            96 => 
            array (
                'company_expense_land_id' => 1917,
                'company_expense_id' => 1375,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 531,
                'company_expense_land_total_price' => '111.72',
            ),
            97 => 
            array (
                'company_expense_land_id' => 1918,
                'company_expense_id' => 1375,
                'company_land_id' => 12,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '23.15',
            ),
            98 => 
            array (
                'company_expense_land_id' => 1919,
                'company_expense_id' => 1375,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            99 => 
            array (
                'company_expense_land_id' => 1920,
                'company_expense_id' => 1375,
                'company_land_id' => 11,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '42.79',
            ),
            100 => 
            array (
                'company_expense_land_id' => 1956,
                'company_expense_id' => 1411,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '126.73',
            ),
            101 => 
            array (
                'company_expense_land_id' => 1957,
                'company_expense_id' => 1411,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            102 => 
            array (
                'company_expense_land_id' => 1958,
                'company_expense_id' => 1411,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '25.73',
            ),
            103 => 
            array (
                'company_expense_land_id' => 1959,
                'company_expense_id' => 1411,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '47.54',
            ),
            104 => 
            array (
                'company_expense_land_id' => 1960,
                'company_expense_id' => 1412,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '8235.02',
            ),
            105 => 
            array (
                'company_expense_land_id' => 1961,
                'company_expense_id' => 1412,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            106 => 
            array (
                'company_expense_land_id' => 1962,
                'company_expense_id' => 1412,
                'company_land_id' => 14,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '1671.77',
            ),
            107 => 
            array (
                'company_expense_land_id' => 1963,
                'company_expense_id' => 1412,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '3089.21',
            ),
            108 => 
            array (
                'company_expense_land_id' => 1964,
                'company_expense_id' => 1413,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '158.26',
            ),
            109 => 
            array (
                'company_expense_land_id' => 1965,
                'company_expense_id' => 1413,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            110 => 
            array (
                'company_expense_land_id' => 1966,
                'company_expense_id' => 1413,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '32.13',
            ),
            111 => 
            array (
                'company_expense_land_id' => 1967,
                'company_expense_id' => 1413,
                'company_land_id' => 14,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '59.37',
            ),
            112 => 
            array (
                'company_expense_land_id' => 1968,
                'company_expense_id' => 1414,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '34.95',
            ),
            113 => 
            array (
                'company_expense_land_id' => 1969,
                'company_expense_id' => 1414,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            114 => 
            array (
                'company_expense_land_id' => 1970,
                'company_expense_id' => 1414,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '7.09',
            ),
            115 => 
            array (
                'company_expense_land_id' => 1971,
                'company_expense_id' => 1414,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '13.11',
            ),
            116 => 
            array (
                'company_expense_land_id' => 1972,
                'company_expense_id' => 1415,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '78.61',
            ),
            117 => 
            array (
                'company_expense_land_id' => 1973,
                'company_expense_id' => 1415,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            118 => 
            array (
                'company_expense_land_id' => 1974,
                'company_expense_id' => 1415,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '15.96',
            ),
            119 => 
            array (
                'company_expense_land_id' => 1975,
                'company_expense_id' => 1415,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '29.49',
            ),
            120 => 
            array (
                'company_expense_land_id' => 1976,
                'company_expense_id' => 1416,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '72.95',
            ),
            121 => 
            array (
                'company_expense_land_id' => 1977,
                'company_expense_id' => 1416,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            122 => 
            array (
                'company_expense_land_id' => 1978,
                'company_expense_id' => 1416,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '14.81',
            ),
            123 => 
            array (
                'company_expense_land_id' => 1979,
                'company_expense_id' => 1416,
                'company_land_id' => 9,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '27.36',
            ),
            124 => 
            array (
                'company_expense_land_id' => 1980,
                'company_expense_id' => 1417,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '253.46',
            ),
            125 => 
            array (
                'company_expense_land_id' => 1981,
                'company_expense_id' => 1417,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            126 => 
            array (
                'company_expense_land_id' => 1982,
                'company_expense_id' => 1417,
                'company_land_id' => 12,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '51.45',
            ),
            127 => 
            array (
                'company_expense_land_id' => 1983,
                'company_expense_id' => 1417,
                'company_land_id' => 8,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '95.08',
            ),
            128 => 
            array (
                'company_expense_land_id' => 1984,
                'company_expense_id' => 1418,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '202.77',
            ),
            129 => 
            array (
                'company_expense_land_id' => 1985,
                'company_expense_id' => 1418,
                'company_land_id' => 11,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            130 => 
            array (
                'company_expense_land_id' => 1986,
                'company_expense_id' => 1418,
                'company_land_id' => 15,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '41.16',
            ),
            131 => 
            array (
                'company_expense_land_id' => 1987,
                'company_expense_id' => 1418,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '76.07',
            ),
            132 => 
            array (
                'company_expense_land_id' => 1988,
                'company_expense_id' => 1419,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '73.66',
            ),
            133 => 
            array (
                'company_expense_land_id' => 1989,
                'company_expense_id' => 1419,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            134 => 
            array (
                'company_expense_land_id' => 1990,
                'company_expense_id' => 1419,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '14.95',
            ),
            135 => 
            array (
                'company_expense_land_id' => 1991,
                'company_expense_id' => 1419,
                'company_land_id' => 13,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '27.63',
            ),
            136 => 
            array (
                'company_expense_land_id' => 1992,
                'company_expense_id' => 1420,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '67.80',
            ),
            137 => 
            array (
                'company_expense_land_id' => 1993,
                'company_expense_id' => 1420,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            138 => 
            array (
                'company_expense_land_id' => 1994,
                'company_expense_id' => 1420,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '13.76',
            ),
            139 => 
            array (
                'company_expense_land_id' => 1995,
                'company_expense_id' => 1420,
                'company_land_id' => 8,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '25.43',
            ),
            140 => 
            array (
                'company_expense_land_id' => 1996,
                'company_expense_id' => 1421,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '31.68',
            ),
            141 => 
            array (
                'company_expense_land_id' => 1997,
                'company_expense_id' => 1421,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            142 => 
            array (
                'company_expense_land_id' => 1998,
                'company_expense_id' => 1421,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '6.43',
            ),
            143 => 
            array (
                'company_expense_land_id' => 1999,
                'company_expense_id' => 1421,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '11.89',
            ),
            144 => 
            array (
                'company_expense_land_id' => 2000,
                'company_expense_id' => 1422,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '126.73',
            ),
            145 => 
            array (
                'company_expense_land_id' => 2001,
                'company_expense_id' => 1422,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            146 => 
            array (
                'company_expense_land_id' => 2002,
                'company_expense_id' => 1422,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '25.73',
            ),
            147 => 
            array (
                'company_expense_land_id' => 2003,
                'company_expense_id' => 1422,
                'company_land_id' => 4,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '47.54',
            ),
            148 => 
            array (
                'company_expense_land_id' => 2004,
                'company_expense_id' => 1423,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '4.44',
            ),
            149 => 
            array (
                'company_expense_land_id' => 2005,
                'company_expense_id' => 1423,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            150 => 
            array (
                'company_expense_land_id' => 2006,
                'company_expense_id' => 1423,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '0.90',
            ),
            151 => 
            array (
                'company_expense_land_id' => 2007,
                'company_expense_id' => 1423,
                'company_land_id' => 13,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '1.66',
            ),
            152 => 
            array (
                'company_expense_land_id' => 2008,
                'company_expense_id' => 1424,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '9.19',
            ),
            153 => 
            array (
                'company_expense_land_id' => 2009,
                'company_expense_id' => 1424,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            154 => 
            array (
                'company_expense_land_id' => 2010,
                'company_expense_id' => 1424,
                'company_land_id' => 12,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '1.87',
            ),
            155 => 
            array (
                'company_expense_land_id' => 2011,
                'company_expense_id' => 1424,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '3.45',
            ),
            156 => 
            array (
                'company_expense_land_id' => 2012,
                'company_expense_id' => 1425,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '192.63',
            ),
            157 => 
            array (
                'company_expense_land_id' => 2013,
                'company_expense_id' => 1425,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            158 => 
            array (
                'company_expense_land_id' => 2014,
                'company_expense_id' => 1425,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '39.11',
            ),
            159 => 
            array (
                'company_expense_land_id' => 2015,
                'company_expense_id' => 1425,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '72.26',
            ),
            160 => 
            array (
                'company_expense_land_id' => 2016,
                'company_expense_id' => 1426,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '125.34',
            ),
            161 => 
            array (
                'company_expense_land_id' => 2017,
                'company_expense_id' => 1426,
                'company_land_id' => 13,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            162 => 
            array (
                'company_expense_land_id' => 2018,
                'company_expense_id' => 1426,
                'company_land_id' => 13,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '47.02',
            ),
            163 => 
            array (
                'company_expense_land_id' => 2019,
                'company_expense_id' => 1426,
                'company_land_id' => 12,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '25.44',
            ),
            164 => 
            array (
                'company_expense_land_id' => 2020,
                'company_expense_id' => 1427,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '205.31',
            ),
            165 => 
            array (
                'company_expense_land_id' => 2021,
                'company_expense_id' => 1427,
                'company_land_id' => 14,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            166 => 
            array (
                'company_expense_land_id' => 2022,
                'company_expense_id' => 1427,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '41.68',
            ),
            167 => 
            array (
                'company_expense_land_id' => 2023,
                'company_expense_id' => 1427,
                'company_land_id' => 13,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '77.02',
            ),
            168 => 
            array (
                'company_expense_land_id' => 2024,
                'company_expense_id' => 1428,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '293.26',
            ),
            169 => 
            array (
                'company_expense_land_id' => 2025,
                'company_expense_id' => 1428,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            170 => 
            array (
                'company_expense_land_id' => 2026,
                'company_expense_id' => 1428,
                'company_land_id' => 12,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '59.53',
            ),
            171 => 
            array (
                'company_expense_land_id' => 2027,
                'company_expense_id' => 1428,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '110.01',
            ),
            172 => 
            array (
                'company_expense_land_id' => 2028,
                'company_expense_id' => 1429,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '769.26',
            ),
            173 => 
            array (
                'company_expense_land_id' => 2029,
                'company_expense_id' => 1429,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            174 => 
            array (
                'company_expense_land_id' => 2030,
                'company_expense_id' => 1429,
                'company_land_id' => 9,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '156.17',
            ),
            175 => 
            array (
                'company_expense_land_id' => 2031,
                'company_expense_id' => 1429,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '288.57',
            ),
            176 => 
            array (
                'company_expense_land_id' => 2032,
                'company_expense_id' => 1430,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '433.11',
            ),
            177 => 
            array (
                'company_expense_land_id' => 2033,
                'company_expense_id' => 1430,
                'company_land_id' => 9,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            178 => 
            array (
                'company_expense_land_id' => 2034,
                'company_expense_id' => 1430,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '87.92',
            ),
            179 => 
            array (
                'company_expense_land_id' => 2035,
                'company_expense_id' => 1430,
                'company_land_id' => 12,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '162.47',
            ),
            180 => 
            array (
                'company_expense_land_id' => 2090,
                'company_expense_id' => 1479,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '646.97',
            ),
            181 => 
            array (
                'company_expense_land_id' => 2091,
                'company_expense_id' => 1479,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            182 => 
            array (
                'company_expense_land_id' => 2092,
                'company_expense_id' => 1479,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '131.34',
            ),
            183 => 
            array (
                'company_expense_land_id' => 2093,
                'company_expense_id' => 1479,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '242.70',
            ),
            184 => 
            array (
                'company_expense_land_id' => 2094,
                'company_expense_id' => 1480,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '1672.86',
            ),
            185 => 
            array (
                'company_expense_land_id' => 2095,
                'company_expense_id' => 1480,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            186 => 
            array (
                'company_expense_land_id' => 2096,
                'company_expense_id' => 1480,
                'company_land_id' => 9,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '339.60',
            ),
            187 => 
            array (
                'company_expense_land_id' => 2097,
                'company_expense_id' => 1480,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '627.54',
            ),
            188 => 
            array (
                'company_expense_land_id' => 2098,
                'company_expense_id' => 1481,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '31.05',
            ),
            189 => 
            array (
                'company_expense_land_id' => 2099,
                'company_expense_id' => 1481,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            190 => 
            array (
                'company_expense_land_id' => 2100,
                'company_expense_id' => 1481,
                'company_land_id' => 8,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '6.30',
            ),
            191 => 
            array (
                'company_expense_land_id' => 2101,
                'company_expense_id' => 1481,
                'company_land_id' => 10,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '11.65',
            ),
            192 => 
            array (
                'company_expense_land_id' => 2102,
                'company_expense_id' => 1482,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '11.72',
            ),
            193 => 
            array (
                'company_expense_land_id' => 2103,
                'company_expense_id' => 1482,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            194 => 
            array (
                'company_expense_land_id' => 2104,
                'company_expense_id' => 1482,
                'company_land_id' => 13,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '2.38',
            ),
            195 => 
            array (
                'company_expense_land_id' => 2105,
                'company_expense_id' => 1482,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '4.40',
            ),
            196 => 
            array (
                'company_expense_land_id' => 2106,
                'company_expense_id' => 1483,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '7.16',
            ),
            197 => 
            array (
                'company_expense_land_id' => 2107,
                'company_expense_id' => 1483,
                'company_land_id' => 4,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            198 => 
            array (
                'company_expense_land_id' => 2108,
                'company_expense_id' => 1483,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '1.45',
            ),
            199 => 
            array (
                'company_expense_land_id' => 2109,
                'company_expense_id' => 1483,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '2.69',
            ),
            200 => 
            array (
                'company_expense_land_id' => 2110,
                'company_expense_id' => 1484,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 521,
                'company_expense_land_total_price' => '8.87',
            ),
            201 => 
            array (
                'company_expense_land_id' => 2111,
                'company_expense_id' => 1484,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 0,
                'company_expense_land_total_price' => '0.00',
            ),
            202 => 
            array (
                'company_expense_land_id' => 2112,
                'company_expense_id' => 1484,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 207,
                'company_expense_land_total_price' => '1.80',
            ),
            203 => 
            array (
                'company_expense_land_id' => 2113,
                'company_expense_id' => 1484,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 309,
                'company_expense_land_total_price' => '3.33',
            ),
            204 => 
            array (
                'company_expense_land_id' => 5637,
                'company_expense_id' => 3374,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 534,
                'company_expense_land_total_price' => '41.45',
            ),
            205 => 
            array (
                'company_expense_land_id' => 5638,
                'company_expense_id' => 3374,
                'company_land_id' => 11,
                'company_expense_land_total_tree' => 1471,
                'company_expense_land_total_price' => '37.64',
            ),
            206 => 
            array (
                'company_expense_land_id' => 5639,
                'company_expense_id' => 3374,
                'company_land_id' => 12,
                'company_expense_land_total_tree' => 270,
                'company_expense_land_total_price' => '4.36',
            ),
            207 => 
            array (
                'company_expense_land_id' => 5640,
                'company_expense_id' => 3374,
                'company_land_id' => 13,
                'company_expense_land_total_tree' => 310,
                'company_expense_land_total_price' => '16.55',
            ),
            208 => 
            array (
                'company_expense_land_id' => 5641,
                'company_expense_id' => 3375,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 534,
                'company_expense_land_total_price' => '30.59',
            ),
            209 => 
            array (
                'company_expense_land_id' => 5642,
                'company_expense_id' => 3375,
                'company_land_id' => 5,
                'company_expense_land_total_tree' => 1471,
                'company_expense_land_total_price' => '27.78',
            ),
            210 => 
            array (
                'company_expense_land_id' => 5643,
                'company_expense_id' => 3375,
                'company_land_id' => 15,
                'company_expense_land_total_tree' => 270,
                'company_expense_land_total_price' => '3.21',
            ),
            211 => 
            array (
                'company_expense_land_id' => 5644,
                'company_expense_id' => 3375,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 310,
                'company_expense_land_total_price' => '12.22',
            ),
            212 => 
            array (
                'company_expense_land_id' => 5645,
                'company_expense_id' => 3376,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 534,
                'company_expense_land_total_price' => '20.73',
            ),
            213 => 
            array (
                'company_expense_land_id' => 5646,
                'company_expense_id' => 3376,
                'company_land_id' => 9,
                'company_expense_land_total_tree' => 1471,
                'company_expense_land_total_price' => '18.82',
            ),
            214 => 
            array (
                'company_expense_land_id' => 5647,
                'company_expense_id' => 3376,
                'company_land_id' => 13,
                'company_expense_land_total_tree' => 270,
                'company_expense_land_total_price' => '2.18',
            ),
            215 => 
            array (
                'company_expense_land_id' => 5648,
                'company_expense_id' => 3376,
                'company_land_id' => 8,
                'company_expense_land_total_tree' => 310,
                'company_expense_land_total_price' => '8.28',
            ),
            216 => 
            array (
                'company_expense_land_id' => 5649,
                'company_expense_id' => 3377,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 534,
                'company_expense_land_total_price' => '20.73',
            ),
            217 => 
            array (
                'company_expense_land_id' => 5650,
                'company_expense_id' => 3377,
                'company_land_id' => 8,
                'company_expense_land_total_tree' => 1471,
                'company_expense_land_total_price' => '18.82',
            ),
            218 => 
            array (
                'company_expense_land_id' => 5651,
                'company_expense_id' => 3377,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 270,
                'company_expense_land_total_price' => '2.18',
            ),
            219 => 
            array (
                'company_expense_land_id' => 5652,
                'company_expense_id' => 3377,
                'company_land_id' => 4,
                'company_expense_land_total_tree' => 310,
                'company_expense_land_total_price' => '8.28',
            ),
            220 => 
            array (
                'company_expense_land_id' => 5653,
                'company_expense_id' => 3378,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 534,
                'company_expense_land_total_price' => '25.71',
            ),
            221 => 
            array (
                'company_expense_land_id' => 5654,
                'company_expense_id' => 3378,
                'company_land_id' => 14,
                'company_expense_land_total_tree' => 1471,
                'company_expense_land_total_price' => '23.34',
            ),
            222 => 
            array (
                'company_expense_land_id' => 5655,
                'company_expense_id' => 3378,
                'company_land_id' => 15,
                'company_expense_land_total_tree' => 270,
                'company_expense_land_total_price' => '2.70',
            ),
            223 => 
            array (
                'company_expense_land_id' => 5656,
                'company_expense_id' => 3378,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 310,
                'company_expense_land_total_price' => '10.27',
            ),
            224 => 
            array (
                'company_expense_land_id' => 5657,
                'company_expense_id' => 3379,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 534,
                'company_expense_land_total_price' => '2.53',
            ),
            225 => 
            array (
                'company_expense_land_id' => 5658,
                'company_expense_id' => 3379,
                'company_land_id' => 13,
                'company_expense_land_total_tree' => 1471,
                'company_expense_land_total_price' => '2.30',
            ),
            226 => 
            array (
                'company_expense_land_id' => 5659,
                'company_expense_id' => 3379,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 270,
                'company_expense_land_total_price' => '0.27',
            ),
            227 => 
            array (
                'company_expense_land_id' => 5660,
                'company_expense_id' => 3379,
                'company_land_id' => 7,
                'company_expense_land_total_tree' => 310,
                'company_expense_land_total_price' => '1.01',
            ),
            228 => 
            array (
                'company_expense_land_id' => 5661,
                'company_expense_id' => 3380,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 534,
                'company_expense_land_total_price' => '7.77',
            ),
            229 => 
            array (
                'company_expense_land_id' => 5662,
                'company_expense_id' => 3380,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 1471,
                'company_expense_land_total_price' => '7.06',
            ),
            230 => 
            array (
                'company_expense_land_id' => 5663,
                'company_expense_id' => 3380,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 270,
                'company_expense_land_total_price' => '0.82',
            ),
            231 => 
            array (
                'company_expense_land_id' => 5664,
                'company_expense_id' => 3380,
                'company_land_id' => 2,
                'company_expense_land_total_tree' => 310,
                'company_expense_land_total_price' => '3.10',
            ),
            232 => 
            array (
                'company_expense_land_id' => 5665,
                'company_expense_id' => 3381,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 534,
                'company_expense_land_total_price' => '12.96',
            ),
            233 => 
            array (
                'company_expense_land_id' => 5666,
                'company_expense_id' => 3381,
                'company_land_id' => 6,
                'company_expense_land_total_tree' => 1471,
                'company_expense_land_total_price' => '11.77',
            ),
            234 => 
            array (
                'company_expense_land_id' => 5667,
                'company_expense_id' => 3381,
                'company_land_id' => 12,
                'company_expense_land_total_tree' => 270,
                'company_expense_land_total_price' => '1.36',
            ),
            235 => 
            array (
                'company_expense_land_id' => 5668,
                'company_expense_id' => 3381,
                'company_land_id' => 12,
                'company_expense_land_total_tree' => 310,
                'company_expense_land_total_price' => '5.18',
            ),
            236 => 
            array (
                'company_expense_land_id' => 5669,
                'company_expense_id' => 3382,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 534,
                'company_expense_land_total_price' => '15.80',
            ),
            237 => 
            array (
                'company_expense_land_id' => 5670,
                'company_expense_id' => 3382,
                'company_land_id' => 14,
                'company_expense_land_total_tree' => 1471,
                'company_expense_land_total_price' => '14.34',
            ),
            238 => 
            array (
                'company_expense_land_id' => 5671,
                'company_expense_id' => 3382,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 270,
                'company_expense_land_total_price' => '1.66',
            ),
            239 => 
            array (
                'company_expense_land_id' => 5672,
                'company_expense_id' => 3382,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 310,
                'company_expense_land_total_price' => '6.31',
            ),
            240 => 
            array (
                'company_expense_land_id' => 5673,
                'company_expense_id' => 3383,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 534,
                'company_expense_land_total_price' => '62.18',
            ),
            241 => 
            array (
                'company_expense_land_id' => 5674,
                'company_expense_id' => 3383,
                'company_land_id' => 1,
                'company_expense_land_total_tree' => 1471,
                'company_expense_land_total_price' => '56.46',
            ),
            242 => 
            array (
                'company_expense_land_id' => 5675,
                'company_expense_id' => 3383,
                'company_land_id' => 3,
                'company_expense_land_total_tree' => 270,
                'company_expense_land_total_price' => '6.53',
            ),
            243 => 
            array (
                'company_expense_land_id' => 5676,
                'company_expense_id' => 3383,
                'company_land_id' => 16,
                'company_expense_land_total_tree' => 310,
                'company_expense_land_total_price' => '24.83',
            ),
        ));
        
        
    }
}