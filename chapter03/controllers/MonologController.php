<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Formatter\LineFormatter;
//use Monolog\Formatter\FormatterInterface;

class MonologController extends Phalcon\Mvc\Controller{

    public function indexAction(){

        // the default date format is "Y-m-d H:i:s"
        //$dateFormat = "Y n j, g:i a";
        // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
        //$output = "%datetime% > %level_name% > %message% %context% %extra%\n";

        // finally, create a formatter
        //$formatter = new LineFormatter($output, $dateFormat); 
           
               
        // Create some handlers
        $stream = new StreamHandler(__DIR__.'application/monolog3.log', Logger::WARNING);
        //$stream->setFormatter($formatter);
        //$error_log = new ErrorLogHandler(APPLICATION_PATH.'/application/logs/testmonolog3.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Create the main logger of the app
        $logger = new Logger('my_logger');
        $logger->pushHandler($stream);
        //$logger->pushHandler($error_log);
        $logger->pushHandler($firephp);

        // Create a logger for the security-related stuff with a different channel
        $securityLogger = new Logger('security');
        $securityLogger->pushHandler($stream);
        //$securityLogger->pushHandler($error_log);
        $securityLogger->pushHandler($firephp);

        // Or clone the first one to only change the channel
        $securityLogger = $logger->withName('security');


        // You can now use your logger
        $logger->info('My logger is now ready');

        $logger->info('Adding a new user', array('username' => 'Seldaek'));

        $logger->warning("Foo");
        $logger->error("Bar");
    }
    

   
}



