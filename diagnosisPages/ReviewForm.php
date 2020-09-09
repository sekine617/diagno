<?php 
class ReviewForm {
  
   public $valueNum;
   public $lavelName;

   function __construct($valueNum, $lavelName){
       
       $this->valueNum = $valueNum;
       $this->lavelName = $lavelName;
   }

   public function getValue(){
       return $this->valueNum;
   }

   public function getLavel(){
       return $this->lavelName;
   }
}


// ?>