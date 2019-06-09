<?php

use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;

class LoggController extends Phalcon\Mvc\Controller{

    public function onConstruct(){
       
        
        //Create Logger File in application/logs Folder
        $logger = new FileAdapter(APPLICATION_PATH.'/application/logs/test.log',[
            'mode' => 'w', //full path and force mode
            ]);
            
        // // Changing the logger format
        // $formatter = new LineFormatter('%date% - %message%');
        // $logger->setFormatter($formatter);
        
        // These are the different log levels available:

        $logger->critical(
            'This is a critical message'
        );

        $logger->emergency(
            'This is an emergency message'
        );

        $logger->debug(
            'This is a debug message'
        );

        $logger->error(
            'This is an error message'
        );

        $logger->info(
            'This is an info message'
        );

        $logger->notice(
            'This is a notice message'
        );

        $logger->warning(
            'This is a warning message'
        );

        $logger->alert(
            'This is an alert message'
        );

        // You can also use the log() method with a Logger constant:
        $logger->log(
            'This is another error message',
            Logger::ERROR
        );

        // If no constant is given, DEBUG is assumed.
        $logger->log(
            'This is a message'
        );

        // You can also pass context parameters like this
        $logger->log(
            'This is a {message}', 
            [ 
                'message' => 'parameter' 
            ]
        );
            
    }

    public function indexAction(){
       
    }


}