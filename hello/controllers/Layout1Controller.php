<?php
namespace Multiphalcon\Hello\Controllers;
use \Phalcon\Mvc\View;

class Layout1Controller extends \Phalcon\Mvc\Controller{
    public function index1Action(){
        $this->view->setTemplateAfter('after');
        $this->view->setTemplateBefore('before');

        // $this->view->setRenderLevel(View::LEVEL_MAIN_LAYOUT);
        $this->view->setRenderLevel(View::LEVEL_AFTER_TEMPLATE);
        // $this->view->setRenderLevel(View::LEVEL_LAYOUT);
        // $this->view->setRenderLevel(View::LEVEL_BEFORE_TEMPLATE);
        // $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        //$this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        // $this->view->disableLevel(View::LEVEL_MAIN_LAYOUT);
        // $this->view->disableLevel(View::LEVEL_AFTER_TEMPLATE);
        $this->view->disableLevel(View::LEVEL_LAYOUT);
        $this->view->disableLevel(View::LEVEL_BEFORE_TEMPLATE);
        // $this->view->disableLevel(View::LEVEL_ACTION_VIEW);
    }

    public function index2Action(){
    }

}