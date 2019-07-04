<?php
namespace Multiphalcon\Chapter03\Controllers;

class ResponseController extends \Phalcon\Mvc\Controller {
    public function indexAction() {
        //$this->response->redirect('chapter03/response/index1');
        //$this->response->redirect('hello/index/index');
        //$this->response->redirect('https://www.google.com/');
        $this->response->redirect([
            'for' => 'redirect' //name of router
        ]);
    }

    public function index1Action() {
        echo '<h3 style="color:red">' . __METHOD__ .'</h3>';
    }

}