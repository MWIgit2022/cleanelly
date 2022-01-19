<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$favorites = new Favorites();
$favorites->resizeImages();
$favorites->selectOffers();
$favorites->selectHits();

$arResult['FAVORITES'] = $favorites->getFavoritesProducts(); 

$this->IncludeComponentTemplate();
