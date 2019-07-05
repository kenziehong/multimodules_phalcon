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
    
    public function showAction() {
        echo '<h3 style="color:red">' . __METHOD__ .'</h3>';
        
        $params = $this->router->getParams();
        echo '<pre>';
        print_r($params);
        echo '</pre>';
    }
    
    public function index4Action() {
        $showUrl = $this->url->get([
            'for' => 'showUrl',
            'title' => 'phalcon-title',
            'id'=> 123,
            ]);
            
            echo '<a href=" '.$showUrl.' ">go-to-showAction</a>';
        }
        
        public function index5Action() {
            
            //create a path
            $path = $this->url->path(APPLICATION_PATH. '/chapter03/temp/path.php');
            
            require_once $path;
        }
        
        public function index6Action() {
            
            //setBasePath
            $this->url->setBasePath(APPLICATION_PATH);
            
            //get
            $basePath = $this->url->getBasePath();
            
            //create a path
            $path = $this->url->path(APPLICATION_PATH. '/chapter03/temp/path.php');
            
            echo 'basePath: ' . $basePath;
            echo  '<hr>';
            echo $path;
            
        }
        
        public function index7Action() {

            //set
            $this->url->setStaticBaseUri('base/');
            
            //difference between get and getStatic(can not pass query string)
            $staticUrl = $this->url->getStatic('chapter03/url/show');
            $staticBaseUrl = $this->url->getStaticBaseUri(); //original - slash

            echo '<a href=" '.$staticUrl.' ">go-to-showAction</a>';
            echo  '<hr>';
            echo $staticBaseUrl;
            echo  '<hr>';
            echo $staticUrl; //base/chapter03/url/show
            
        }
        
    }
    
