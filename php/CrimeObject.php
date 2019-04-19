<?php 




class CrimeObject{
 
    
    var $CrimeID;
    var $TimeStamp;
    var $DistrictNumber;
    var $PSAArea;
    var $DispatchTime;
    var $DispatchDate;
    var $Address;
    var $CrimeType;
    var $CrimeCode;
    var $LocationX;
    var $LocationY;
    
    function setCrimeID($CrimeID) { $this->CrimeID = $CrimeID; }
    function getCrimeID() { return $this->CrimeID; }
    function setTimeStamp($TimeStamp) { $this->TimeStamp = $TimeStamp; }
    function getTimeStamp() { return $this->TimeStamp; }
    function setDistrictNumber($DistrictNumber) { $this->DistrictNumber = $DistrictNumber; }
    function getDistrictNumber() { return $this->DistrictNumber; }
    function setPSAArea($PSAArea) { $this->PSAArea = $PSAArea; }
    function getPSAArea() { return $this->PSAArea; }
    function setDispatchTime($DispatchTime) { $this->DispatchTime = $DispatchTime; }
    function getDispatchTime() { return $this->DispatchTime; }
    function setDispatchDate($DispatchDate) { $this->DispatchDate = $DispatchDate; }
    function getDispatchDate() { return $this->DispatchDate; }
    function setAddress($Address) { $this->Address = $Address; }
    function getAddress() { return $this->Address; }
    function setCrimeType($CrimeType) { $this->CrimeType = $CrimeType; }
    function getCrimeType() { return $this->CrimeType; }
    function setCrimeCode($CrimeCode) { $this->CrimeCode = $CrimeCode; }
    function getCrimeCode() { return $this->CrimeCode; }
    function setLocationX($LocationX) { $this->LocationX = $LocationX; }
    function getLocationX() { return $this->LocationX; }
    function setLocationY($LocationY) { $this->LocationY = $LocationY; }
    function getLocationY() { return $this->LocationY; }
    
    
    
    
    
}





?>
