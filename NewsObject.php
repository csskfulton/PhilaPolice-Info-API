<?php

    
    class NewsObject{
        
        
       var $id;
       var $timeStamp;
       var $storyAuthor;
       var $districtNumber;
       var $title;
       var $url;
       var $description;
       var $GUID;
       var $pubDate;
       var $category;
       var $imageURL;
       var $scrapeHash;
       var $tubeURL;
       
       
       function set_id($id) {
           $this->id = $id;
       }
       
       function get_id() {
           return $this->id;
       }
       function set_timeStamp($timeStamp) {
           $this->timeStamp = $timeStamp;
       }
       
       function get_timeStamp() {
           return $this->timeStamp;
       }
       function set_storyAuthor($storyAuthor) {
           $this->storyAuthor = $storyAuthor;
       }
       
       function get_storyAuthor() {
           return $this->storyAuthor;
       }
       function set_districtNumber($districtNumber) {
           $this->districtNumber = $districtNumber;
       }
       
       function get_districtNumber() {
           return $this->districtNumber;
       }
       function set_title($title) {
           $this->title = $title;
       }
       
       function get_title() {
           return $this->title;
       }
       function set_url($url) {
           $this->url = $url;
       }
       
       function get_url() {
           return $this->url;
       }
       function set_description($description) {
           $this->description = $description;
       }
       
       function get_description() {
           return $this->description;
       }
       function set_GUID($GUID) {
           $this->GUID = $GUID;
       }
       
       function get_GUID() {
           return $this->GUID;
       }
       function set_pubDate($pubDate) {
           $this->pubDate = $pubDate;
       }
       
       function get_pubDate() {
           return $this->pubDate;
       }
       function set_category($category) {
           $this->category = $category;
       }
       
       function get_category() {
           return $this->category;
       }
       function set_imageURL($imageURL) {
           $this->imageURL = $imageURL;
       }
       
       function get_imageURL() {
           return $this->imageURL;
       }
       function set_scrapeHash($scrapeHash) {
           $this->scrapeHash = $scrapeHash;
       }
       
       function get_scrapeHash() {
           return $this->scrapeHash;
       }
       
       function set_tubeURL($tubeURL) {
           $this->tubeURL = $tubeURL;
       }
       
       function get_tubeURL() {
           return $this->tubeURL;
       }
        
        
        
    }


?>
