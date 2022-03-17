<style>
    .sizes-wrapper {
        min-width: 100%;
        overflow-x: auto;
        margin: 0 -15px;
        padding: 0 15px;
    }

    .sizes-holder {
        display: inline-block;
    }

    @media (max-width: 767px) {

        .tabs .nav.nav-tabs a {
            padding: 17px 9px 18px;
        }

    }

    @media (max-width: 413px) {

        .tabs .nav.nav-tabs a {
            font-size: 12px;
            padding: 17px 7px 18px;
        }

        .foot-size-img {
            max-width: 190px;
        }

    }
</style>

<? /* Таблица размеров*/?>
<div class="tabs">
    <ul class="nav nav-tabs">
        <li class=" active"><a href="#man" data-toggle="tab"><span>Мужская одежда</span></a></li>
        <li class="product_reviews_tab"><a href="#woman" data-toggle="tab"><span>Женская одежда</span></a></li>
        <!--li class="product_reviews_tab"><a href="#universal" data-toggle="tab"><span>Универсальная одежда</span></a></li-->
        <li class="product_reviews_tab"><a href="#kid" data-toggle="tab"><span>Детская одежда</span></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="man">
            <div class="heading">Таблица соответствия мужских размеров</div>
            <div class="sizes-wrapper">
                <div class="sizes-holder">
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
						array(
							"COMPONENT_TEMPLATE" => ".default",
							"PATH" => SITE_DIR."include/table_sizes/men.php",
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "",
							"AREA_FILE_RECURSIVE" => "Y",
							"EDIT_TEMPLATE" => "include_area.php"
						),
						false
					);?>
                </div>
            </div>
        </div>


        <div class="tab-pane" id="woman">
            <div class="heading">Таблица соответствия женских размеров</div>
            <div class="sizes-wrapper">
                <div class="sizes-holder">
                   <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
						array(
							"COMPONENT_TEMPLATE" => ".default",
							"PATH" => SITE_DIR."include/table_sizes/women.php",
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "",
							"AREA_FILE_RECURSIVE" => "Y",
							"EDIT_TEMPLATE" => "include_area.php"
						),
						false
					);?>
                    <br /><br />
                    <table>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--div class="tab-pane" id="universal">
            <div class="heading">Таблица соответствия универсальных размеров</div>
            <div class="sizes-wrapper">
                <div class="sizes-holder">
                    <table class="sizes">
                    <tbody>
                            <tr class="bg">
                                <td class="title"><b>Обхват груди (см)</b></td>
                                <td><b>86-90</b></td>
                                <td><b>90-98</b></td>
                                <td><b>98-106</b></td>
                                <td><b>106-114</b></td>
                                <td><b>114-122</b></td>
                            </tr>
                            <tr>
                                <td class="title"><b>Обхват талии (см)</b></td>
                                <td>66-78</td>
                                <td>68-86</td>
                                <td>74-92</td>
                                <td>78-100</td>
                                <td>98-106</td>
                            </tr>
                            <tr>
                                <td class="title"><b>Обхват бедер (см)</b></td>
                                <td>90-100</td>
                                <td>100-108</td>
                                <td>108-116</td>
                                <td>116-124</td>
                                <td>124-130</td>
                            </tr>
                            <tr>
                                <td class="title"><b>Обхват шеи (см)</b></td>
                                <td>37</td>
                                <td>38-39</td>
                                <td>40-41</td>
                                <td>42-43</td>
                                <td>44-45</td>
                            </tr>
                            <tr>
                                <td class="title"><b>Длина рукава с учетом плечевого шва (см)</b></td>
                                <td>77-77,5</td>
                                <td>78-78,5</td>
                                <td>78,7-79,3</td>
                                <td>79,5-79,8</td>
                                <td>80-80,5</td>
                            </tr>
                            <tr>
                                <td class="title"><b>Ширина спинки на уровне глубины проймы (см)</b></td>
                                <td>56-57,5</td>
                                <td>58-60</td>
                                <td>61-63</td>
                                <td>64-67</td>
                                <td>68-70</td>
                            </tr>
                            <tr>
                                <td class="title"><b>Размер Cleanelly</b></td>
                                <td>44</td>
                                <td>46/48</td>
                                <td>50/52</td>
                                <td>54/56</td>
                                <td>58/60</td>
                            </tr>
                            <tr>
                                <td class="title"><b>Международный размер</b></td>
                                <td>S</td>
                                <td>M</td>
                                <td>L</td>
                                <td>XL</td>
                                <td>XXL</td>
                            </tr>
                        </tbody>
                    </table>
                    <br /><br />
                    <table>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div-->
        <div class="tab-pane" id="kid">
            <div class="heading">Таблица для определения размера детских халатов</div>
            <div class="sizes-wrapper">
                <div class="sizes-holder">
                   <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
						array(
							"COMPONENT_TEMPLATE" => ".default",
							"PATH" => SITE_DIR."include/table_sizes/kids.php",
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "",
							"AREA_FILE_RECURSIVE" => "Y",
							"EDIT_TEMPLATE" => "include_area.php"
						),
						false
					);?>
                </div>
            </div>
        </div>
    </div>
</div>