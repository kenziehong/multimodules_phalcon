<?php

class RequestController extends Phalcon\Mvc\Controller{

    public function indexAction(){
        if($this->request->isPost()){
            //echo "has post";
            $params = $this->request->getPost();
            $username = $this->request->getPost("username"); //name = username
            $city = $this->request->getPost("city"); 

            echo "<pre>";
                print_r($params);
            echo "</pre>";

            echo '<h3 style="color:red"> Username: '.$username.'</h3>';
            echo '<h3 style="color:blue"> City: '.$city.'</h3>';


            //turn off view object that display index.phtml of RequestController
            $this->view->disable();
        }
    }

    public function index2Action(){        

    }

    public function index4Action(){

    }

    public function index5Action(){
        $header = $this->request->getHeaders();
        $accept = $this->request->getHeader("Accept");

        $serverName = $this->request->getServerName();
        $serverName2 = $this->request->getServer("SERVER_NAME");

        $serverAdd = $this->request->getServerAddress();
        $serverAdd2 = $this->request->getServer("SERVER_ADDR");

        $httpAccept = $this->request->getAcceptableContent();
        $httpAccept2 = $this->request->getServer("HTTP_ACCEPT");

        $language = $this->request->getLanguages();
        $language2 = $this->request->getServer("HTTP_ACCEPT_LANGUAGE");
        $bestlanguage = $this->request->getBestLanguage();

        $httpUserAgent = $this->request->getUserAgent();
        $httpUserAgent2 = $this->request->getServer("HTTP_USER_AGENT");

        $httpHost = $this->request->getHttpHost();
        $httpHost2 = $this->request->getServer("HTTP_HOST");


        
        echo "<pre>";
            print_r($header);
        echo "</pre>";    
        
        echo '<h3 style="color: green"> Accept: '.$accept.'</h3>';

        echo '<h3 style="color: pink"> serverName: '.$serverName.'</h3>';
        echo '<h3 style="color: pink"> serverName2: '.$serverName2.'</h3>';
        
        echo '<h3 style="color: brown"> serverAdd: '.$serverAdd.'</h3>';
        echo '<h3 style="color: brown"> serverAdd2: '.$serverAdd2.'</h3>';
        
        echo "<pre>";
            print_r($httpAccept);
        echo "</pre>";
        //echo '<h3 style="color: red"> httpAccept: '.$httpAccept.'</h3>';
        echo '<h3 style="color: red"> httpAccept2: '.$httpAccept2.'</h3>';

        echo "<pre>";
            print_r($language);
        echo "</pre>";
        echo '<h3 style="color: blue"> language2: '.$language2.'</h3>';
        echo '<h3 style="color: blue"> bestlanguage: '.$bestlanguage.'</h3>';
        
        echo '<h3 style="color: red"> httpUserAgent: '.$httpUserAgent.'</h3>';
        echo '<h3 style="color: red"> httpUserAgent2: '.$httpUserAgent2.'</h3>';

        echo '<h3 style="color: blue"> httpHost: '.$httpHost.'</h3>';
        echo '<h3 style="color: blue"> httpHost2: '.$httpHost2.'</h3>';

        echo "<pre>";
             print_r($_SERVER);
        echo "</pre>";  




        
    }
}
