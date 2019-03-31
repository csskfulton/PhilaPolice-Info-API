<?php

    class NewsObject{
        
        
        var $NewsStoryID;
        var $TimeStamp;
        var $StoryAuthor;
        var $DistrictNumber;
        var $Title;
        var $URL;
        var $Description;
        var $GUID;
        var $PubDate;
        var $Category;
        var $ImageURL;
        var $ScrapeHash;
        var $TubeURL;
        var $MasterHash;
        
        function setNewsStoryID($NewsStoryID) { $this->NewsStoryID = $NewsStoryID; }
        function getNewsStoryID() { return $this->NewsStoryID; }
        function setTimeStamp($TimeStamp) { $this->TimeStamp = $TimeStamp; }
        function getTimeStamp() { return $this->TimeStamp; }
        function setStoryAuthor($StoryAuthor) { $this->StoryAuthor = $StoryAuthor; }
        function getStoryAuthor() { return $this->StoryAuthor; }
        function setDistrictNumber($DistrictNumber) { $this->DistrictNumber = $DistrictNumber; }
        function getDistrictNumber() { return $this->DistrictNumber; }
        function setTitle($Title) { $this->Title = $Title; }
        function getTitle() { return $this->Title; }
        function setURL($URL) { $this->URL = $URL; }
        function getURL() { return $this->URL; }
        function setDescription($Description) { $this->Description = $Description; }
        function getDescription() { return $this->Description; }
        function setGUID($GUID) { $this->GUID = $GUID; }
        function getGUID() { return $this->GUID; }
        function setPubDate($PubDate) { $this->PubDate = $PubDate; }
        function getPubDate() { return $this->PubDate; }
        function setCategory($Category) { $this->Category = $Category; }
        function getCategory() { return $this->Category; }
        function setImageURL($ImageURL) { $this->ImageURL = $ImageURL; }
        function getImageURL() { return $this->ImageURL; }
        function setScrapeHash($ScrapeHash) { $this->ScrapeHash = $ScrapeHash; }
        function getScrapeHash() { return $this->ScrapeHash; }
        function setTubeURL($TubeURL) { $this->TubeURL = $TubeURL; }
        function getTubeURL() { return $this->TubeURL; }
        function setMasterHash($MasterHash) { $this->MasterHash = $MasterHash; }
        function getMasterHash() { return $this->MasterHash; }
        
    }


?>
