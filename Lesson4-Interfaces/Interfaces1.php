<?php

interface LoggerInterface {
    public function logAction(string $action);
}

class CliLogger implements LoggerInterface {

    public function logAction(string $action)
    {
        echo $action . "\n";
    }
}

class FileLogger implements LoggerInterface {

    private $fileLocation;

    public function __construct(string $fileLocation)
    {
        $this->fileLocation = $fileLocation;
    }

    public function logAction(string $action)
    {
        /*
         * 1- Opens the file in write mode, if it does not exist it creates it.
         * 2- writes the line
         * 3- closes the file
         */
        file_put_contents($this->fileLocation, $action . PHP_EOL ,FILE_APPEND);
    }
}

class Greeter {
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function giveGreetings()
    {
        $this->logger->logAction("I'm about to give greetings");
        echo "Hello world\n";
        $this->logger->logAction("I've just gave greetings");
    }

}


$greeter = new Greeter(new CliLogger());
$greeter->giveGreetings();

$greeter = new Greeter(new FileLogger(__DIR__ . "/actionLog.log"));
$greeter->giveGreetings();
