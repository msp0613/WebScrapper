<?php

function getEuroHTMLContent($url = 'https://www.euro.com.pl/laptopy-i-netbooki.bhtml'){
    $euroHTML = file_get_html($url); 

    $products = [];

    foreach($euroHTML->find('.product-box') as $product){ 
        $attribiutes = []; 

        if(!count($product->find('.product-name')) || !count($product->find('.product-photo .photo-hover')) || !count($product->find('.price-normal'))){
            continue; 
        }

        $title = trim(preg_replace('/\s\s+/', ' ', $product->find('.product-name')[0]->plaintext)); 
        $image = 'https://www.euro.com.pl' . $product->find('.product-photo .photo-hover')[0]->{'data-hover'}; 
        $price = trim(preg_replace('/\s\s+/', ' ', $product->find('.price-normal')[0]->plaintext));

        foreach($product->find('.product-attributes .attributes-row') as $attribute){ 
            array_push($attribiutes, trim(preg_replace('/\s\s+/', ' ', $attribute->plaintext)));  
        }

        $shopUrl = 'https://www.euro.com.pl' . $product->find('.product-name a')[0]->href; 
       
        array_push($products, new ScrapperItem($title, $image, $price, $attribiutes,  $shopUrl, 'https://f00.esfr.pl/img/desktop/euro/logo.png'));
    }


    return $products; 
} 

function getScreenSize($size){ 
    $sizes = [
        '10' => '11',
        '11' => '!11-2-13-1',
        '12' => '!11-2-13-1',
        '13' => '!13-2-14-1',
        '14' => '!14-2-15-3',
        '15' => '!15-4-15-6',
        '16' => '!15-7-',
        '17' => '!-17'
    ];
    
    return $sizes[$size];
}
function getDiskCapacity($size){ 
    $sizes = [
        '2' => '2-tb',
        '1' => '1-tb',
        '512' => '512-gb',
        '256' => '256-gb',
        '128' => '128-gb',
        '64' => '64-gb'
    ];
    return $sizes[$size];
}


function generateURLForEuroScrapping($params){ 
    if(isset($_GET['shop']) && !in_array('euro', $_GET['shop'])){ 
        return false;
    }

    $url = 'https://www.euro.com.pl/laptopy-i-netbooki{producent}{przekatna}{ram}{dysk}{matryca}.bhtml';

    if(isset($_GET['producent'])){
        $url = str_replace('{producent}', ",_" . $_GET['producent'], $url);
    }
    else{
        $url = str_replace('{producent}', "", $url);
    }

    if(isset($_GET['screen'])){
        $url = str_replace('{przekatna}', ",przekatna-ekranu-cale-" .  getScreenSize($_GET['screen']), $url);
    }
    else{
        $url = str_replace('{przekatna}', "", $url);
    }

    if(isset($_GET['ram'])){
        $url = str_replace('{ram}', ",pamiec-ram_2!" .  $_GET['ram'] . "-gb", $url);
    }
    else{
        $url = str_replace('{ram}', "", $url);
    }
    if(isset($_GET['disc'])){
        $url = str_replace('{dysk}', ",dysk-ssd!" .  getDiskCapacity($_GET['disc']), $url);
    }
    else{
        $url = str_replace('{dysk}', "", $url);
    }

    if(isset($_GET['matrix'])){
        $url = str_replace('{matryca}', ",typ-matrycy_2!" .  $_GET['matrix'], $url);
    }
    else{
        $url = str_replace('{matryca}', "", $url);
    }

    return $url; 
}
