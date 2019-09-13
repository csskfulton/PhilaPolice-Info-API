<?php 


    
    
    class UnsolvedMurdersObject {
        
        var $ID;
        var $TimeStamp;
        var $DCNumber;
        var $VictimName;
        var $NewsURL;
        var $ScrapeHash;
        
       
        function setID($ID) { $this->ID = $ID; }
        function getID() { return $this->ID; }
        function setTimeStamp($TimeStamp) { $this->TimeStamp = $TimeStamp; }
        function getTimeStamp() { return $this->TimeStamp; }
        function setDCNumber($DCNumber) { $this->DCNumber = $DCNumber; }
        function getDCNumber() { return $this->DCNumber; }
        function setVictimName($VictimName) { $this->VictimName = $VictimName; }
        function getVictimName() { return $this->VictimName; }
        function setNewsURL($NewsURL) { $this->NewsURL = $NewsURL; }
        function getNewsURL() { return $this->NewsURL; }
        function setScrapeHash($ScrapeHash) { $this->ScrapeHash = $ScrapeHash; }
        function getScrapeHash() { return $this->ScrapeHash; }
        
        
        
    }













?>