<?php
/**
 *  You need to save some money in order to buy a brand new car.
 *  You talk with your bank and they offer 3 products for you to accomplish your goal.
 *
 *   A) A safe box.
 *   B) A saving account.
 *   C) An investment account.
 *
 *   Other types of of products exist in the bank, and all of them (including the ones that they offered you) cost
 *   $100 a year.
 *
 *   As you are already a client they offer the saving account for free but it reduces by 1% of the initial deposit per year.
 *   If you choose the investment account, it will generate a +15% of interest in a year.
 *
 *   All the products have:
 *      1) a identification number (unique)
 *      2) The name of client
 *      3) The money that was deposited.
 *      4) The capability to calculate how much money you will have after expenses.
 *
 *   You need to come up with a model of the different classes involved and the UML Class Diagram
 *   (Commit it in the diagrams folder)
 */

 abstract class Product {

	 protected $cost;
     protected $uniqueId;
     protected $clientName;
     protected $money;

     public function __construct(int $cost, int $uniqueId, int $clientName, int $money) {
         $this->cost = $cost;
         $this->uniqueId = $uniqueId;
         $this->clientName = $clientName;
         $this->money = $money;
     }

     public function getCost(): int {
         return $this->cost;
     }

     public function setCost(int $cost) {
         $this->cost = $cost;
     }

     public function getUniqueId(): int {
         return $this->uniqueId;
     }

     public function setUniqueId(int $uniqueId) {
         $this->uniqueId = $uniqueId;
     }

     public function getClientName(): int {
         return $this->clientName;
     }

     public function setClientName(int $clientName) {
         $this->clientName = $clientName;
     }

     public function getMoney(): int {
         return $this->money;
     }

     public function setMoney(int $money) {
         $this->money = $money;
     }

     abstract function calculateAfterExpenses();
 }

 class SaveBox extends Product {

     function calculateAfterExpenses(): float {
         return $this->money - $this->cost;
     }
 }

 class SavingsAccount extends Product {

     private $interest = -0.01;

     function calculateAfterExpenses(): float {
         return $this->money - ($this->money * $this->interest);
     }
 }

 class InvestmentAccount extends Product {

     private $interest;

     public function __construct(int $cost, int $uniqueId, int $clientName, int $money, float $interest = 0.15) {
         parent::__construct($cost, $uniqueId, $clientName, $money);
         $this->interest = $interest;
     }

     function calculateAfterExpenses(): float {
         return $this->money - $this->cost + ($this->money * $this->interest);
     }
 }