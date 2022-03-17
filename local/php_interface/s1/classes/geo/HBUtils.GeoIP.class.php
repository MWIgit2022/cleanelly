<?
use \Bitrix\Iblock\ElementTable;
use \Bitrix\Sale\Location;
use \Bitrix\Main\Service\GeoIp;

class HBUtilsGeoIP
{
    public static $cidr_optim_file_path = 'local/php_interface/s1/classes/geo/cidr_optim.txt';
    public static $cities_file_path = 'local/php_interface/s1/classes/geo/cities.txt';
    public static $csvRowLength = 1000;
    public static $csvDelimiter = "	";

    /**
     * Функция получения объекта местоположения по ID текущего региона к которому привязано местолположение
     */
    public static function getCurrentLocation()
    {
        $result = '';
        if ($_COOKIE['current_region']) {
            $cityID = $_COOKIE['current_region'];
        } else {
            $cityID = static::getRegionIDByIp() ? static::getRegionIDByIp() : false;
        }

        if (!is_numeric($cityID)) {
            if(file_exists($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/s1/consts.php')){
                include($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/s1/consts.php');
                $cityID = ID_DEFAULT_CITY;
            }
            if (!is_numeric($cityID)) {
                $cityID = 84;
            }
        }

        $locationRES = \Bitrix\Sale\Location\LocationTable::getList(['filter' => ['=ID' => $cityID]]);
        if ($location = $locationRES->fetch()) {
            $result = $location;
        }

        return $result;
    }

    /**
     * Функция проверки есть ли такой город в БД
     */
    public static function chCity($name)
    {
        $cityID = ID_DEFAULT_CITY;

        if (!is_numeric($cityID)) {
            if(file_exists($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/s1/consts.php')){
                include($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/s1/consts.php');
                $cityID = ID_DEFAULT_CITY;
            }
            if (!is_numeric($cityID)) {
                $cityID = 84;
            }
        }
        
        if (trim($name) != '') {
            $res = \Bitrix\Sale\Location\LocationTable::getList([
                'filter' => [ "=NAME.LANGUAGE_ID" => LANGUAGE_ID, "=NAME.NAME" => trim($name), "TYPE.CODE" => ['CITY', 'VILLAGE', 'SUBREGION'], "COUNTRY_ID" => 1],
                'select' => [ 
                    "NAME_RU" => "NAME.NAME",
                    "TYPE_CODE" => "TYPE.CODE",
                    'ID',
                    'CODE',
                    "COUNTRY_ID"
                ]
            ]);
            while($elem = $res->fetch())
            {
                if (intval($elem['ID']) > 0) {
                    $cityID = intval($elem['ID']);
                }
            }

        }

        return $cityID;
    }

    /**
     * Определение города по IP
     */
    public static function getRegionIDByIp()
    {
        if (!$_COOKIE['current_city']) {
            global $APPLICTION;
            global $USER;
            $geoArray = array();
           // $ip = static::getRealIpAddr();
           // $geoArray = static::getInfoAboutIP($ip);
		   
			$ipAddress = GeoIp\Manager::getRealIp();
			if($ipAddress){
				$dataResult = GeoIp\Manager::getDataResult($ipAddress, "ru");
				if($dataResult){
					$result = $dataResult->getGeoData();
				}
			}
			
            /* $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://ipgeobase.ru:7020/geo?ip=' . $ip);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Bot');
            $data = curl_exec($ch);
            $city = (!curl_errno($ch) && $xml = simplexml_load_string($data)) ? $xml->ip->city : '';
            curl_close($ch); */

            $geoArray['cityName'] = $result->cityName;
            $result = static::chCity($geoArray['cityName']);
            setcookie('current_city', $geoArray['cityName'], time() + 3600 * 24 * 7, '/'); //set cookie for 1 week
        } else {
            $result = static::chCity($_COOKIE['current_city']);
        }

        return $result;
    }

    /**
     * Функция определения IP пользователя
     */
    public static function getRealIpAddr($ip = false)
    {
        if ($ip) {
            return $ip;
        } else {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            return $ip;
        }
    }

    public static function getInfoAboutIP($ip = false)
    {
        if (!$ip) {
            $ip = static::getRealIpAddr();
        }

        $codedIp = static::getIpSum($ip);

        $ipRowArr = static::findIpRow($codedIp);

        $cityArr = array();
        if ($ipRowArr[4]) {
            $cityArr = static::getCityInfo($ipRowArr[4]);
        }

        return array(
            "id" => $cityArr[0],
            "cityName" => $cityArr[1],
            "oblastName" => $cityArr[2],
            "okrugName" => $cityArr[3],
            "countyCode" => $ipRowArr[3],
            "lat" => $cityArr[4],
            "lon" => $cityArr[5],
        );
    }

    public static function getIpSum($ip = false)
    {
        $multiplier = 256;

        $ipArr = explode('.', $ip);

        return $ipArr[0] * $multiplier * $multiplier * $multiplier + $ipArr[1] * $multiplier * $multiplier + $ipArr[2] * $multiplier + $ipArr[3];
    }

    public static function findIpRow($codedIp = false)
    {
        $answer = false;
        if (($handle = fopen($_SERVER["DOCUMENT_ROOT"] . '/' . static::$cidr_optim_file_path, "r")) !== false) {
            while (($data = fgetcsv($handle, static::$csvRowLength, static::$csvDelimiter)) !== false) {
                if ($data[0] > $codedIp || $codedIp > $data[1]) {
                    continue;
                }

                $answer = $data;
                break;
            }
            fclose($handle);
        }

        return $answer;
    }

    public static function getCityInfo($cityId = false)
    {
        $answer = false;
        if (($handle = fopen($_SERVER["DOCUMENT_ROOT"] . '/' . static::$cities_file_path, "r")) !== false) {
            while (($data = fgetcsv($handle, static::$csvRowLength, static::$csvDelimiter)) !== false) {
                if ($data[0] != $cityId) {
                    continue;
                }

                $answer = $data;
                break;
            }
            fclose($handle);
        }

        return $answer;
    }
}
