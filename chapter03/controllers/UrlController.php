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
}

