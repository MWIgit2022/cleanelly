<?php
use Bitrix\Main;
use Bitrix\Iblock;

class Migration
{
    /** Инициализация */
    public function init()
    {
        require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
    }

    /*Создание типа инфоблока*/
    public function createTypeIblock()
    {
        $ibt = new CIBlockType;
        $arFields = Array(
            'ID' => 'users',
            'SORT' => 1000,
            'LANG' => Array(
                'ru' => Array(
                    'NAME' => 'Пользователи',
                    'SECTION_NAME' => 'Пользователи',
                    'ELEMENT_NAME' => 'Пользователь'
                    )
                )
        );
        $ibt->Add($arFields);
    }
    
    /*Создание инфоблока*/
    public function createIblock()
    {
        $ib = new CIBlock;
        $arFields = Array(
            "ACTIVE" => 'Y',
            "NAME" => 'Пользователи, применившие купон',
            "CODE" => 'users_used_coupon',
            "IBLOCK_ID" => 'users_used_coupon',
            "IBLOCK_TYPE_ID" => 'users',
            "SITE_ID" => Array("s1"),
            "SORT" => 500,
            "GROUP_ID" => Array("2"=>"R")
        );
        return $ib->Add($arFields);

    }    

    /** Установка миграции */
    public function up()
    {
        $this->includeModules();
        $id = Migration::getIblock();
        
        if(!$id) {
            throw new Main\SystemException('Can not find IBLOCK users_used_coupon const');
        }
        
        $prop1 = $this->getIblockProperty('USER_ID', $id);
        if(empty($prop1)) {
            $res1 = $this->addIblockProperty($id, array(
                'NAME' => 'ID пользователя',
                'ACTIVE' => 'Y',
                'SORT' => 500,
                'CODE' => 'USER_ID',
                'PROPERTY_TYPE' => 'S'                 
            ));
        } 
    }

    /** Откат миграции */
    public function down()
    {
        $this->includeModules();
        $iblockId = Migration::getIblock();
        $this->deleteIblock($iblockId);
    }

    /*Удаление инфоблока*/
    public function deleteIblock($iblockId) 
    {
       $result = CIBlock::Delete($iblockId);
       if(!$result) 
       {
           throw new Main\SystemException('Can not delete this iblock');
       }
    }

    /**
    * @throws Main\LoaderException
    * @throws Main\SystemException
    */
    private function includeModules()
    {
        if(!Main\Loader::includeModule('iblock')) {
            throw new Main\SystemException('Can not load iblock module');
        }
    } 

    /**
    * @return bool|int
    * @throws Main\SystemException
    */
    public function getIblock()
    {
        $id = $this->getIblockId('users_used_coupon');
        if(!$id) {
            throw new Main\SystemException('Can not find users_used_coupon iblock');
        }
 
        return $id;
    }

    /**
    * @param string $code
    * @param string $type
    * @return int|bool
    */
    private function getIblockId($code, $type = '')
    {
        $arFilter = array(
            '=CODE' => $code
        );
        if(!empty($type)) {
            $arFilter['=IBLOCK_TYPE_ID'] = $type;
        }
 
        $d = Iblock\IblockTable::getRow(array(
            'filter' => $arFilter,
            'select' => array('ID')
        ));
 
        if(!empty($d) && !empty($d['ID'])) {
            return $d['ID'];
        }
 
        return false;
    } 

    /**
    * @param string $code
    * @param int $iblockId
    * @return array|null
    */
    private function getIblockProperty($code, $iblockId)
    {
        $arFilter = array(
            '=CODE' => $code,
            '=IBLOCK_ID' => $iblockId
        );
 
        return Iblock\PropertyTable::getRow(array(
            'filter' => $arFilter,
            'select' => array('*')
        ));
    } 

    private function getIblockPropertyEnum($code, $iblockId)
    {
        $arFilter = array(
            '=CODE' => $code,
            '=IBLOCK_ID' => $iblockId
        );

        return CIBlockPropertyEnum::GetList(array(), $arFilter);
    }

    /**
    * @param int $iblockId
    * @param array $data
    * @return bool
    * @throws Main\SystemException
    */
    private function addIblockProperty($iblockId, $data)
    {
        $data['IBLOCK_ID'] = $iblockId;
        $ibProp = new \CIBlockProperty;
        $id = $ibProp->Add($data);
        if(!$id) {
            throw new Main\SystemException(sprintf('Iblock property add error: %s',
                $ibProp->LAST_ERROR));
        }
 
        return $id;
    }  

    /**
    * @param int $id
    * @return bool|CDBResult
    * @throws Main\SystemException
    */
    private function removeIblockProperty($id)
    {
        $res = \CIBlockProperty::Delete($id);
        if(!$res) {
            /** @global \CMain $APPLICATION */
            global $APPLICATION;
            $ex = $APPLICATION->GetException();
 
            throw new Main\SystemException(sprintf(
                'Iblock property #%u delete error: %s',
                $id,
                (
                    !empty($ex)
                    ? $ex->GetString()
                    : 'no error msg'
                )
            ));
        }
 
        return $res;
    }

}
?>