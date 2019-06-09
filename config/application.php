<?php

$application = new \Phalcon\Mvc\Application($di);
$application->registerModules([
    'hello' => [
        'className' => 'Multiphalcon\Hello\Module',
        'path' => APPLICATION_PATH . '/hello/Module.php',
    ],
]);

echo $application->handle()->getContent();