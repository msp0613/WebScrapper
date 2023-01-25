<?php 

class ScrapperItem{
    public $title;
    public $image;
    public $price;
    public $description;
    public $shop_url;
    public $shop;

    public function __construct($title = '', $image = '', $price = '', $description = [], $shop_url = '', $shop = ''){
        $this->title = $title;
        $this->image = $image;
        $this->price = $price;
        $this->description = $description;
        $this->shop_url = $shop_url;
        $this->shop = $shop;
    }
}