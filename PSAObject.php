<?php 

    class PSAObject{
                
        var $PSAID;
//        var $TimeStamp;
        var $DistrictNumber;
        var $EmailAddress;
        var $PSAAreaNumber;
        var $LieutenantName;
//         var $ScrapeHash;
        
        function setPSAID($PSAID) { $this->PSAID = $PSAID; }
        function getPSAID() { return $this->PSAID; }
//         function setTimeStamp($TimeStamp) { $this->TimeStamp = $TimeStamp; }
//         function getTimeStamp() { return $this->TimeStamp; }
        function setDistrictNumber($DistrictNumber) { $this->DistrictNumber = $DistrictNumber; }
        function getDistrictNumber() { return $this->DistrictNumber; }
        function setEmailAddress($EmailAddress) { $this->EmailAddress = $EmailAddress; }
        function getEmailAddress() { return $this->EmailAddress; }
        function setPSAAreaNumber($PSAAreaNumber) { $this->PSAAreaNumber = $PSAAreaNumber; }
        function getPSAAreaNumber() { return $this->PSAAreaNumber; }
        function setLieutenantName($LieutenantName) { $this->LieutenantName = $LieutenantName; }
        function getLieutenantName() { return $this->LieutenantName; }
//         function setScrapeHash($ScrapeHash) { $this->ScrapeHash = $ScrapeHash; }
//         function getScrapeHash() { return $this->ScrapeHash; }
        

    }


?>
