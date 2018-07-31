<?php 

$remove_elements = array();
$remove_elements = ["a","an","the","has","have","and","for",
                    "this","where","when","was","to","as",
                    "there","from","called","at","new","in",
                    "his","her","he","she","i","am"];

function createSearchTerm(String $str, $sort = false){
    global $remove_elements;
    $word_list = explode(" ", trim($str));
    $word_list = array_map('trim', $word_list);
    $word_list = array_map('strtolower', $word_list);
    $word_list = array_diff($word_list, $remove_elements);
    $word_list = array_clean($word_list);
    if($sort == true) sort($word_list);
    $word_list = implode(";",$word_list);
    return $word_list;
}

function setSKillName(String $str){
    $word_list = explode(" ", trim($str));
    $word_list = array_clean($word_list);
    $word_list = array_map('ucfirst', $word_list);
    $word_list = join(" ",$word_list);
    return $word_list;
}

function setSKillKey(String $str){
    $word_list = explode(" ", trim($str));
    $word_list = array_clean($word_list);
    $word_list = array_map('ucfirst', $word_list);
    $word_list = join("",$word_list);
    return $word_list;
}