<?php
class Assay {
    public $assayName;
    public $evaluationItem;
    public function __construct($assayName, $evaluationItem){
        $this->assayName=$assayName;
        $this->evaluationItem=$evaluationItem;
    }
    public function getName(){
       return $this->assayName;
    }
    public function getEvalName(){
        return $this->evaluationItem;
    }
}
?>