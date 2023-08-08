<?php

namespace App\Libraries;
class Wfunction
{
    public function report_months_lookup($month)
    {
        $data = "";
        switch ($month) {
            case 1:
                $data .= "Jan";
                break;
            case 2:
                $data .= "Feb";
                break;
            case 3:
                $data .= "Mar";
                break;
            case 4:
                $data .= "Apr";
                break;
            case 5:
                $data .= "May";
                break;
            case 6:
                $data .= "Jun";
                break;
            case 7:
                $data .= "Jul";
                break;
            case 8:
                $data .= "Aug";
                break;
            case 9:
                $data .= "Sep";
                break;
            case 10:
                $data .= "Oct";
                break;
            case 11:
                $data .= "Nov";
                break;
            case 12:
                $data .= "Dec";
                break;
        }
        return $data;
    }

    public function getmonth()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        return date("m");
    }

    public function getmonth_without_zero()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        return date("n");
    }

    public function getyear()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        return date("Y");
    }

    public function getday()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        return date("d");
    }

    public function getdate()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        return date("Y-m-d");
    }

    public function getdatetime()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        return date("Y-m-d H:i:s");
    }

    public function getdatetimeymd()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        return date("Y-m-d");
    }

    public function getdatetimegmt()
    {
        date_default_timezone_set('UTC');
        return date("Y-m-d H:i:s");
    }

    public function getdateonly()
    {
        return date("Y-m-d");
    }

    public function getDD()
    {
        $array = array();
        $array[0] = "Day";
        for ($i = 1; $i <= 31; $i++) {
            $array[$i] = $i;
        }
        return $array;
    }

    public function getMM()
    {
        $array = array();
        $array[0] = "Month";
        $array[1] = "Jan";
        $array[2] = "Feb";
        $array[3] = "Mar";
        $array[4] = "Apr";
        $array[5] = "May";
        $array[6] = "Jun";
        $array[7] = "Jul";
        $array[8] = "Aug";
        $array[9] = "Sep";
        $array[10] = "Oct";
        $array[11] = "Nov";
        $array[12] = "Dec";
        return $array;
    }

    public function getMMTextual($month,$full=false){
        $default = $full ? "January" : "Jan";
        switch($month){
            case '1':
                $default = $full ? "January" : "Jan";
                break;
            case '2':
                $default = $full ? "February" : "Feb";
                break;
            case '3':
                $default = $full ? "March" : "Mar";
                break;
            case '4':
                $default = $full ? "April" : "Apr";
                break;
            case '5':
                $default = $full ? "May" : "May";
                break;
            case '6':
                $default = $full ? "June" : "Jun";
                break;
            case '7':
                $default = $full ? "July" : "Jul";
                break;
            case '8':
                $default = $full ? "August" : "Aug";
                break;
            case '9':
                $default = $full ? "September" : "Sep";
                break;
            case '10':
                $default = $full ? "October" : "Oct";
                break;
            case '11':
                $default = $full ? "November" : "Nov";
                break;
            case '12':
                $default = $full ? "December" : "Dec";
                break;
        }
        return $default;
    }

    public function getYYYY($starting_year)
    {
        $array = array();
        $array[0] = "Year";
        for ($i = $starting_year; $i <= DATE('Y'); $i++) {
            $array[$i] = $i;
        }
        return $array;
    }

    public function ChopStr($str, $len, $remove = false)
    {
        if (strlen($str) < $len)
            return $str;
        $str = substr($str, 0, $len);
        if ($spc_pos = strrpos($str, " "))
            $str = substr($str, 0, $spc_pos);
        if ($remove == true) {
            return $str;
        } else {
            return $str . "..";
        }
    }

    public function GetRandomNumber($length)
    {
        if ($length > 0) {
            $rand_id = "";
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((float) microtime() * 1000000);
                $num = mt_rand(1, 36);
                $wfunction = new Wfunction();
                $rand_id .= $wfunction->AssignRandomValue($num);
            }
        }
        return $rand_id;
    }

    private function AssignRandomValue($num)
    {
        switch ($num) {
            case "1":
                $rand_value = "a";
                break;
            case "2":
                $rand_value = "b";
                break;
            case "3":
                $rand_value = "c";
                break;
            case "4":
                $rand_value = "d";
                break;
            case "5":
                $rand_value = "e";
                break;
            case "6":
                $rand_value = "f";
                break;
            case "7":
                $rand_value = "g";
                break;
            case "8":
                $rand_value = "h";
                break;
            case "9":
                $rand_value = "i";
                break;
            case "10":
                $rand_value = "j";
                break;
            case "11":
                $rand_value = "k";
                break;
            case "12":
                $rand_value = "l";
                break;
            case "13":
                $rand_value = "m";
                break;
            case "14":
                $rand_value = "n";
                break;
            case "15":
                $rand_value = "o";
                break;
            case "16":
                $rand_value = "p";
                break;
            case "17":
                $rand_value = "q";
                break;
            case "18":
                $rand_value = "r";
                break;
            case "19":
                $rand_value = "s";
                break;
            case "20":
                $rand_value = "t";
                break;
            case "21":
                $rand_value = "u";
                break;
            case "22":
                $rand_value = "v";
                break;
            case "23":
                $rand_value = "w";
                break;
            case "24":
                $rand_value = "x";
                break;
            case "25":
                $rand_value = "y";
                break;
            case "26":
                $rand_value = "z";
                break;
            case "27":
                $rand_value = "0";
                break;
            case "28":
                $rand_value = "1";
                break;
            case "29":
                $rand_value = "2";
                break;
            case "30":
                $rand_value = "3";
                break;
            case "31":
                $rand_value = "4";
                break;
            case "32":
                $rand_value = "5";
                break;
            case "33":
                $rand_value = "6";
                break;
            case "34":
                $rand_value = "7";
                break;
            case "35":
                $rand_value = "8";
                break;
            case "36":
                $rand_value = "9";
                break;
        }
        return $rand_value;
    }

    public function safe_enc($val) {
        $val = base64_encode($val);
        $val = str_replace(array('+', '/', '='), array('-', '_', '~'), $val);
        return $val;
    }

    public function safe_dec($val){
        $val = str_replace(array('-', '_', '~'), array('+', '/', '='), $val);
        $val = base64_decode($val);
        return $val;
    }

    public function check_device(){
        // checkDevice() : checks if user device is android or else
        // RETURNS 1 for android
        // RETURN 0 for else
        if (is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "android"))) {
            return 1;
        } else {
            return 0;
        }
    }

    public function GetRandomAlphabet($length){
        if ($length > 0) {
            $rand_id = "";
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((float) microtime() * 1000000);
                $num = mt_rand(1, 26);
                $rand_id .= $this->AssignRandomAlphabet($num);
            }
        }
        return $rand_id;
    }

    private function AssignRandomAlphabet($num){
        switch ($num) {
            case "1":
                $rand_value = "a";
                break;
            case "2":
                $rand_value = "b";
                break;
            case "3":
                $rand_value = "c";
                break;
            case "4":
                $rand_value = "d";
                break;
            case "5":
                $rand_value = "e";
                break;
            case "6":
                $rand_value = "f";
                break;
            case "7":
                $rand_value = "g";
                break;
            case "8":
                $rand_value = "h";
                break;
            case "9":
                $rand_value = "i";
                break;
            case "10":
                $rand_value = "j";
                break;
            case "11":
                $rand_value = "k";
                break;
            case "12":
                $rand_value = "l";
                break;
            case "13":
                $rand_value = "m";
                break;
            case "14":
                $rand_value = "n";
                break;
            case "15":
                $rand_value = "o";
                break;
            case "16":
                $rand_value = "p";
                break;
            case "17":
                $rand_value = "q";
                break;
            case "18":
                $rand_value = "r";
                break;
            case "19":
                $rand_value = "s";
                break;
            case "20":
                $rand_value = "t";
                break;
            case "21":
                $rand_value = "u";
                break;
            case "22":
                $rand_value = "v";
                break;
            case "23":
                $rand_value = "w";
                break;
            case "24":
                $rand_value = "x";
                break;
            case "25":
                $rand_value = "y";
                break;
            case "26":
                $rand_value = "z";
                break;
        }
        return $rand_value;
    }

    public function array_merge_recursive_distinct(array &$array1, array &$array2) {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }

    public function year_sel(){
        return [
          '2016'=>2016,
            '2017'=>2017,
            '2018'=>2018,
            '2019'=>2019,
            '2020'=>2020,
            '2021'=>2021,
            '2022'=>2022,
            '2023'=>2023,
            '2024'=>2024,
            '2025'=>2025
        ];
        //        return ['2016','2017','2018','2019','2020','2021','2022','2023','2024','2025'];
    }

    public function month_sel(){
        return [
            '1'=>'Jan',
            '2'=>'Feb',
            '3'=>'Mar',
            '4'=>'Apr',
            '5'=>'May',
            '6'=>'Jun',
            '7'=>'Jul',
            '8'=>'Aug',
            '9'=>'Sep',
            '10'=>'Oct',
            '11'=>'Nov',
            '12'=>'Dec',
        ];
    }

    public function get_random_number($length) {
        if ($length > 0) {
            $rand_id = "";
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((double) microtime() * 1000000);
                $num = mt_rand(1, 36);
                $wfunction = new Wfunction();
                $rand_id .= $wfunction::assign_random_value($num);
            }
        }
        return $rand_id;
    }

    private function assign_random_value($num) {
        switch ($num) {
            case "1":
                $rand_value = "a";
                break;
            case "2":
                $rand_value = "b";
                break;
            case "3":
                $rand_value = "c";
                break;
            case "4":
                $rand_value = "d";
                break;
            case "5":
                $rand_value = "e";
                break;
            case "6":
                $rand_value = "f";
                break;
            case "7":
                $rand_value = "g";
                break;
            case "8":
                $rand_value = "h";
                break;
            case "9":
                $rand_value = "i";
                break;
            case "10":
                $rand_value = "j";
                break;
            case "11":
                $rand_value = "k";
                break;
            case "12":
                $rand_value = "l";
                break;
            case "13":
                $rand_value = "m";
                break;
            case "14":
                $rand_value = "n";
                break;
            case "15":
                $rand_value = "o";
                break;
            case "16":
                $rand_value = "p";
                break;
            case "17":
                $rand_value = "q";
                break;
            case "18":
                $rand_value = "r";
                break;
            case "19":
                $rand_value = "s";
                break;
            case "20":
                $rand_value = "t";
                break;
            case "21":
                $rand_value = "u";
                break;
            case "22":
                $rand_value = "v";
                break;
            case "23":
                $rand_value = "w";
                break;
            case "24":
                $rand_value = "x";
                break;
            case "25":
                $rand_value = "y";
                break;
            case "26":
                $rand_value = "z";
                break;
            case "27":
                $rand_value = "0";
                break;
            case "28":
                $rand_value = "1";
                break;
            case "29":
                $rand_value = "2";
                break;
            case "30":
                $rand_value = "3";
                break;
            case "31":
                $rand_value = "4";
                break;
            case "32":
                $rand_value = "5";
                break;
            case "33":
                $rand_value = "6";
                break;
            case "34":
                $rand_value = "7";
                break;
            case "35":
                $rand_value = "8";
                break;
            case "36":
                $rand_value = "9";
                break;
        }
        return $rand_value;
    }
}
