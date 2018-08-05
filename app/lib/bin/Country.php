<?php 
//API LINK: https://restcountries.eu

$API = 'https://restcountries.eu/rest/v2/';

function getAllCountries(){
    global $API;
    $url = "{$API}all";
    $content = file_get_contents($url, false);
    return json_decode($content);  
}

function getCountryByName($name,$fullname = false){
    global $API;
    $url = "{$API}name/{$name}";
    if($fullname === true) $url .= "{$url}fullText=true";
    $content = file_get_contents($url, false);
    return json_decode($content);  
}

function getCountryByCode($code){
    global $API;
    if(is_array($code)){
        $codeList = implode(";",$code);
        $url = "{$API}alpha?codes={$codeList}";
        $content = file_get_contents($url, false);
        return json_decode($content);  
    }else{
        $url = "{$API}alpha/{$code}";
        $content = file_get_contents($url, false);
        return json_decode($content); 
    }
}

function getCurrencyByCode($currency){
    global $API;
    $url = "{$API}currency/{$currency}";
    $content = file_get_contents($url, false);
    return json_decode($content); 
}

function getLanguageByCode($language){
    global $API;
    $url = "{$API}lang/{$language}";
    $content = file_get_contents($url, false);
    return json_decode($content); 
}

function getDetailByCapital($capital){
    global $API;
    $url = "{$API}capital/{$capital}";
    $content = file_get_contents($url, false);
    return json_decode($content); 
}

function getDetailByCallCode($callCode){
    global $API;
    $url = "{$API}callingcode/{$callCode}";
    $content = file_get_contents($url, false);
    return json_decode($content); 
}


function getDetailByRegion($region){
    global $API;
    $url = "{$API}region/{$region}";
    $content = file_get_contents($url, false);
    return json_decode($content); 
}

function regionalBloc($block){
    global $API;
    $url = "{$API}regionalbloc/{$block}";
    $content = file_get_contents($url, false);
    return json_decode($content); 
}

function getPlacesDetails(Array $details){
    global $API;
    $fields = implode(";", $details);
    $url = "{$API}all?fields={$fields}";
    $content = file_get_contents($url, false);
    return json_decode($content); 
}
