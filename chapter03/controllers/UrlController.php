<?php
namespace Multiphalcon\Chapter03\Controllers;

class UrlController extends \Phalcon\Mvc\Controller {
    public function indexAction() {

        //create url, link to an image
        $imageUrl = $this->url->get('resources/image/redrose.jpg');
        $cssUrl = $this->url->get('resources/css/me.css');
        
        echo 'Image Url: '.$imageUrl;
        echo '<img src=" '.$imageUrl.' " width="300px" height="200px" >';
        
        echo'<link rel="stylesheet" href=" '.$cssUrl.' ">';
    }

    public function index2Action() {
        //$baseUrl = $this->url->getBaseUrl(); //display a slash
        $this->url->setBaseUri('chapter04/url');
        $baseUrl = $this->url->getBaseUri(); 
        $imageUrl = $this->url->get('resources/image/redrose.jpg');
        echo $baseUrl;
        echo '<hr>';
        echo $imageUrl;
    }

    public function index3Action() {
        $infoUrl = $this->url->get('chapter03/url/info', [
            'book_name' => 'phalcon book',
            'price' => 600,
        ]);

        $zendvnUrl = $this->url->get('https://www.google.com/', [], true); //cannot access outside local
        $zendvnUrl2 = $this->url->get('https://www.google.com/', [], false); //can access outside local
        echo '<a href=" '.$infoUrl.' ">go-to-infoAction</a>';
        echo '<hr>';
        echo '<a href=" '.$zendvnUrl.' ">go-to-zendvn-local</a>';
        echo '<hr>';
        echo '<a href=" '.$zendvnUrl2.' ">go-to-zend</a>';
    }

    public function infoAction() {
        echo '<h3 style="color:red">' . __METHOD__ .'</h3>';
        $book_name =  $this->request->getQuery('book_name'); //getPost, take data from FORM
        $price = $this->request->getQuery('price');

        echo 'Book Name: '. $book_name;
        echo '<hr>';
        echo 'Price: '. $price;
    }
}

