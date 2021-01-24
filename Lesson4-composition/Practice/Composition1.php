<?php
require_once '../../Lesson5-Interfaces/Practice/Interfaces1.php';
/**
 *  Finish the the Car example shown in this lesson.
 *
 *  The car has:
 *   1 Engine.
 *   4 wheels.
 *   4 doors.
 *   1 Console command that has a radio and a GPS
 *
 *   When the car rides, a the engine starts working and returns a speed.
 *   Speed is needed for the wheels to spin.
 *   If the speed is 0, the wheels will stop spinning.
 */

class Car {

    const DOOR_NUMBER = 4;
    const WHEEL_NUMBER = 4;

    private $logger;
    private $doors;
    private $engine;

    public function __construct() {
        $this->logger = new CliLogger();
        $this->engine = new Engine(self::WHEEL_NUMBER);
        for ($i = 1; $i <= self::DOOR_NUMBER; $i++) {
            $this->doors[] =  new Door($i);
        }
    }

    function getInDriverSeat() {
        $this->unlockDoors();
        $this->openDriverDoor();
        $this->closeDriverDoor();
    }

    function exit() {
        $this->stop();
        $this->openDriverDoor();
        $this->closeDriverDoor();
        $this->lockDoors();
    }

    function start() {
        $this->engine->start();
    }

    function accelerateTo30kmPerHour() {
        $this->engine->setSpeed(30);
    }

    function brake() {
        $this->engine->setSpeed(0);
    }

    private function unlockDoors() {
        foreach ($this->doors as $door) {
            $door->unlockDoor();
        }
    }

    private function lockDoors() {
        foreach ($this->doors as $door) {
            $door->lockDoor();
        }
    }

    private function openDriverDoor() {
        $this->doors[0]->openDoor();
    }

    private function closeDriverDoor() {
        $this->doors[0]->closeDoor();
    }

    private function stop() {
        $this->engine->stop();
    }
}

class Door {

    private $logger;
    private $doorNumber;
    private $isOpen;
    private $isLocked;

    public function __construct(int $doorNumber) {
        $this->logger = new CliLogger();
        $this->isOpen = false;
        $this->isLocked = true;
        $this->doorNumber = $doorNumber;
    }

    function unlockDoor() {
        $this->isLocked = false;
        $this->logger->logAction("Door number " . $this->doorNumber . " has been unlocked");
    }

    function lockDoor() {
        $this->isLocked = true;
        $this->logger->logAction("Door number " . $this->doorNumber . " has been locked");
    }

    function openDoor() {
        $this->isOpen = true;
        $this->logger->logAction("Door number " . $this->doorNumber . " has been opened");
    }

    function closeDoor() {
        $this->isOpen = false;
        $this->logger->logAction("Door number " . $this->doorNumber . " has been closed");
    }
}

class Engine {

    private $logger;
    private $isOn;
    private $speed;
    private $wheels;

    public function __construct(int $wheelNumber) {
        $this->logger = new CliLogger();
        $this->isOn = false;
        $this->speed = 0;
        for ($i = 1; $i <= $wheelNumber; $i++) {
            $this->wheels[] =  new Wheel($i);
        }
    }

    function start() {
        $this->isOn = true;
        $this->logger->logAction("The engine is on!");
    }

    function stop() {
        $this->isOn = false;
        $this->logger->logAction("The engine is off");
    }

    function setSpeed(int $speedGoal) {
        $this->logger->logAction("Changing speed to " . $speedGoal . "km/h...");

        if ($speedGoal != 0) {
            foreach ($this->wheels as $wheel) {
                $wheel->startSpinning();
            }
        }

        if ($speedGoal > $this->speed) {
            while ($this->speed < $speedGoal) {
                $this->changeSpeed(5);
            }
        } else {
            while ($this->speed > $speedGoal) {
                $this->changeSpeed(-5);
            }
        }

        if ($this->speed == 0) {
            foreach ($this->wheels as $wheel) {
                $wheel->stopSpinning();
            }
        }

        $this->logger->logAction("The car has reached " . $speedGoal . "km/h!");
    }

    private function changeSpeed(int $speedChange) {
        $this->speed += $speedChange;
        $this->logger->logAction("The speed is now " . $this->speed . "km/h");
        sleep(1);
    }
}

class Wheel {

    private $logger;
    private $wheelNumber;
    private $isSpinning;

    public function __construct(int $wheelNumber) {
        $this->logger = new CliLogger();
        $this->isSpinning = false;
        $this->wheelNumber = $wheelNumber;
    }

    function startSpinning() {
        $this->isSpinning = true;
        $this->logger->logAction("Wheel " . $this->wheelNumber . " has started spinning");
    }

    function stopSpinning(){
        $this->isSpinning = false;
        $this->logger->logAction("Wheel " . $this->wheelNumber . " has stopped spinning");
    }
}


$car = new Car();
$car->getInDriverSeat();
$car->start();
$car->accelerateTo30kmPerHour();
$car->brake();
$car->exit();