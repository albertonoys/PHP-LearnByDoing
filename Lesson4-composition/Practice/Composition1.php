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

abstract class Vehicle {
    protected $doors;
    protected $engine;
    protected $logger;
    protected $doorNumber;
    protected $wheelNumber;

    public function __construct(int $wheelNumber, int $doorNumber) {
        $this->logger = new CliLogger();
        $this->wheelNumber = $wheelNumber;
        $this->doorNumber = $doorNumber;
        $this->engine = new Engine($wheelNumber);
        for ($i = 1; $i <= $doorNumber; $i++) {
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

    function start(){
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


class Car extends Vehicle {

    const DOOR_NUMBER = 4;
    const WHEEL_NUMBER = 4;

    public function __construct() {
        parent::__construct(self::WHEEL_NUMBER, self::DOOR_NUMBER);
    }
}

class Truck extends Vehicle {

    const DOOR_NUMBER = 2;
    const WHEEL_NUMBER = 8;

    public function __construct() {
        parent::__construct(self::WHEEL_NUMBER, self::DOOR_NUMBER);
    }
}

class Motorcycle extends Vehicle {

    const DOOR_NUMBER = 0;
    const WHEEL_NUMBER = 0;

    public function __construct() {
        parent::__construct(self::WHEEL_NUMBER, self::DOOR_NUMBER);
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

/*
 * I have no name!@99ed037ad563:/app/Lesson4-composition/Practice$ php Composition1.php
Door number 1 has been unlocked
Door number 2 has been unlocked
Door number 3 has been unlocked
Door number 4 has been unlocked
Door number 1 has been opened
Door number 1 has been closed
The engine is on!
Changing speed to 30km/h...
Wheel 1 has started spinning
Wheel 2 has started spinning
Wheel 3 has started spinning
Wheel 4 has started spinning
The speed is now 5km/h
The speed is now 10km/h
The speed is now 15km/h
The speed is now 20km/h
The speed is now 25km/h
The speed is now 30km/h
The car has reached 30km/h!
Changing speed to 0km/h...
The speed is now 25km/h
The speed is now 20km/h
The speed is now 15km/h
The speed is now 10km/h
The speed is now 5km/h
The speed is now 0km/h
Wheel 1 has stopped spinning
Wheel 2 has stopped spinning
Wheel 3 has stopped spinning
Wheel 4 has stopped spinning
The car has reached 0km/h!
The engine is off
Door number 1 has been opened
Door number 1 has been closed
Door number 1 has been locked
Door number 2 has been locked
Door number 3 has been locked
Door number 4 has been locked

 */