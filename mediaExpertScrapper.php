<?php

function getMediaExpertHTMLContent($url = 'https://www.morele.net/kategoria/laptopy-31/'){ //funkcja majaca za zadanie pobranie danych ze strony euro i przetworzenie ich do stanu w ktorym beda sie nadawaly do wyswietlania na stronie
    $euroHTML = file_get_html($url); //pobranie danych ze strony euro

    $products = [];

    foreach($euroHTML->find('.cat-product-inside') as $product){
        $attribiutes = [];

        $title = trim(preg_replace('/\s\s+/', ' ', $product->find('.price-new')[0]->plaintext));
        if(isset($product->find('.product-image')[0])){
            if($product->find('.product-image')[0]->src){
                $image = $product->find('.product-image')[0]->src;
            }
            elseif ($product->find('.product-image')[0]->getAttribute('data-src')){
                $image = $product->find('.product-image')[0]->getAttribute('data-src');
            }
            else{
                $image = 'https://evopc.pl/wp-content/plugins/woocommerce/assets/images/placeholder.png';
            }
        }
        else{
            $image = 'https://evopc.pl/wp-content/plugins/woocommerce/assets/images/placeholder.png';
        }

        if($product->find('.price-new')){
            $price = trim(preg_replace('/\s\s+/', ' ', $product->find('.price-new')[0]->plaintext)) . ' zł';
        }
        else{
            $price = 'Brak danych';
        }
       

        foreach($product->find('.cat-product-features') as $attribute){
            array_push($attribiutes, trim(preg_replace('/\s\s+/', ' ', $attribute->plaintext)));  
        }

        $shopUrl = 'https://www.morele.net' . $product->find('.productLink')[0]->href;

        array_push($products, new ScrapperItem($title, $image, $price, $attribiutes, $shopUrl, 'https://www.morele.net/static/img/shop/logo/image-logo-morele.svg'));
    }
    return $products;
}
function getDiscCapacity($size){
    $sizes = [
        '2' => '2000',
        '1' => '1000',
        '512' => '512',
        '256' => '256',
        '128' => '128',
        '64' => '64'
    ];

    return $sizes[$size];
}
function generateURLForMediaExpertScrapping($params){
    if(isset($_GET['shop']) && !in_array('media', $_GET['shop'])){
        return false;
    }

    $url = 'https://www.morele.net/kategoria/laptopy-31/';

    if(isset($_GET['producent'])){
        $url .= "/" . $_GET['producent'];
    }

    if(isset($_GET['screen'])){
        $url .= "/przekatna-ekranu-cal_" . $_GET['screen'] . "-cali";
    }

    if(isset($_GET['ram'])){
        $url .= "/wielkosc-pamieci-ram-gb_" . $_GET['ram'];
    }

    if(isset($_GET['disc'])){
        $url .= "/dysk-ssd-gb_" . getDiscCapacity($_GET['disc']);
    }

    if(isset($_GET['matrix'])){
        $url .= "/typ-matrycy_" . $_GET['matrix'];
    }

    return $url;
}