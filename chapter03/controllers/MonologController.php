<?php
namespace Multiphalcon\Chapter03\Controllers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
class MonologController extends \Phalcon\Mvc\Controller{
    public function indexAction(){
        // Create the logger
        $logger = new Logger('my_logger');
        // Now add some handlers
        $logger->pushHandler(new StreamHandler(APPLICATION_PATH . '/my_app.log', Logger::WARNING));
        $logger->pushHandler(new FirePHPHandler());
    
        // You can now use your logger
        $logger->info('My logger is now ready');
        $logger->info('This is a log! ^_^ ');
        $logger->warning('This is a log warning! ^_^ ');
        $logger->error('This is a log error! ^_^ ');
    }
    
}



