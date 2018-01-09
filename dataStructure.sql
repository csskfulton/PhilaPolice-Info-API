SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `Bookmarks` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `DeviceID` varchar(33) DEFAULT NULL,
  `NewsStoryID` int(5) DEFAULT NULL,
  `UCVideoID` int(6) DEFAULT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

CREATE TABLE IF NOT EXISTS `Calendar` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `TimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `DistrictNumber` int(3) DEFAULT NULL,
  `Title` varchar(75) DEFAULT NULL,
  `MeetDate` varchar(75) DEFAULT NULL,
  `MeetLocation` varchar(200) DEFAULT NULL,
  `ScrapeHash` varchar(60) NOT NULL,
  `MasterHash` varchar(50) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=260 ;

CREATE TABLE IF NOT EXISTS `CrimeIncidents` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DataID` varchar(27) DEFAULT NULL,
  `ObjID` bigint(21) DEFAULT NULL,
  `DistrictNumber` int(2) DEFAULT NULL,
  `PSAArea` int(2) DEFAULT NULL,
  `DispatchTime` varchar(29) DEFAULT NULL,
  `DispatchDate` varchar(28) DEFAULT NULL,
  `AddressBlock` varchar(100) DEFAULT NULL,
  `CrimeCode` int(5) DEFAULT NULL,
  `CrimeName` varchar(55) DEFAULT NULL,
  `LocationX` varchar(50) DEFAULT NULL,
  `LocationY` varchar(50) DEFAULT NULL,
  `HashTag` varchar(50) NOT NULL,
  `MasterHash` varchar(50) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6782 ;

CREATE TABLE IF NOT EXISTS `CrimeTypes` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `Type` varchar(20) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

CREATE TABLE IF NOT EXISTS `CurrentHash` (
  `ID` int(9) NOT NULL AUTO_INCREMENT,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `HashName` varchar(30) NOT NULL,
  `Hash` varchar(60) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

CREATE TABLE IF NOT EXISTS `Devices` (
  `ID` int(6) NOT NULL AUTO_INCREMENT,
  `TimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `DeviceID` varchar(100) DEFAULT NULL,
  `CurrentHashTag` varchar(150) DEFAULT NULL,
  `LastRequestIP` varchar(50) DEFAULT NULL,
  UNIQUE KEY `ID` (`ID`),
  UNIQUE KEY `DeviceID` (`DeviceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `DistrictInfo` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `TimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `DistrictNumber` int(3) DEFAULT NULL,
  `LocationAddress` varchar(75) DEFAULT NULL,
  `Phone` varchar(25) DEFAULT NULL,
  `EmailAddress` varchar(100) DEFAULT NULL,
  `CaptainName` varchar(60) DEFAULT NULL,
  `CaptainURL` varchar(300) DEFAULT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

CREATE TABLE IF NOT EXISTS `Images` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `LocalImageURL` varchar(1000) NOT NULL,
  `RemoteImageURL` varchar(1000) NOT NULL,
  `NewsID` int(11) NOT NULL,
  `DistrictNumber` int(2) NOT NULL,
  `ScrapeHash` varchar(50) NOT NULL,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MasterHash` varchar(50) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=141 ;

CREATE TABLE IF NOT EXISTS `NewsStory` (
  `ID` int(7) NOT NULL AUTO_INCREMENT,
  `TimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `StoryAuthor` varchar(50) NOT NULL,
  `DistrictNumber` int(2) NOT NULL,
  `Title` varchar(300) DEFAULT NULL,
  `URL` varchar(500) DEFAULT NULL,
  `Description` varchar(10000) DEFAULT NULL,
  `GUID` varchar(300) DEFAULT NULL,
  `PubDate` varchar(50) DEFAULT NULL,
  `Category` varchar(30) DEFAULT NULL,
  `ImageURL` varchar(300) DEFAULT NULL,
  `ScrapeHash` varchar(50) NOT NULL,
  `TubeURL` varchar(300) DEFAULT NULL,
  `MasterHash` varchar(50) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=112 ;

CREATE TABLE IF NOT EXISTS `PSA` (
  `ID` int(2) NOT NULL AUTO_INCREMENT,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DistrictNumber` int(2) DEFAULT NULL,
  `Email` varchar(61) DEFAULT NULL,
  `PSAAreaNum` varchar(10) DEFAULT NULL,
  `LieutenantName` varchar(50) DEFAULT NULL,
  `isCurrent` int(1) NOT NULL DEFAULT '0',
  `ScrapeHash` varchar(90) NOT NULL,
  `MasterHash` varchar(50) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

CREATE TABLE IF NOT EXISTS `ScrapeHashHistory` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `HashName` varchar(100) NOT NULL,
  `HashTag` varchar(100) NOT NULL,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=663 ;

CREATE TABLE IF NOT EXISTS `Shooting` (
  `ID` int(6) NOT NULL AUTO_INCREMENT,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DataID` int(6) DEFAULT NULL,
  `ObjID` int(6) DEFAULT NULL,
  `DistrictNumber` varchar(2) NOT NULL,
  `Year` int(4) DEFAULT NULL,
  `CrimeTime` varchar(50) NOT NULL,
  `DCNumber` varchar(68) DEFAULT NULL,
  `CrimeCode` bigint(10) DEFAULT NULL,
  `CrimeDate` varchar(20) DEFAULT NULL,
  `Race` varchar(21) DEFAULT NULL,
  `Gender` varchar(14) DEFAULT NULL,
  `Age` int(3) DEFAULT NULL,
  `Wound` varchar(36) DEFAULT NULL,
  `isOfficerInvolved` int(2) DEFAULT NULL,
  `isOffenderInj` int(2) DEFAULT NULL,
  `isOffenderDec` int(2) DEFAULT NULL,
  `LocationAddress` varchar(118) DEFAULT NULL,
  `LocationX` varchar(50) DEFAULT NULL,
  `LocationY` varchar(50) DEFAULT NULL,
  `isInside` int(2) DEFAULT NULL,
  `isOutside` int(2) DEFAULT NULL,
  `isFatal` int(2) DEFAULT NULL,
  `HashTag` varchar(60) NOT NULL,
  `MasterHash` varchar(60) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=153 ;

CREATE TABLE IF NOT EXISTS `UCVideos` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `NewsID` int(100) NOT NULL,
  `TimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `VideoTitle` varchar(50) DEFAULT NULL,
  `Description` varchar(600) DEFAULT NULL,
  `VideoID` varchar(30) DEFAULT NULL,
  `VideoImageURL` varchar(300) NOT NULL,
  `VideoDate` varchar(30) DEFAULT NULL,
  `DistrictNumber` varchar(2) DEFAULT NULL,
  `CrimeType` varchar(30) DEFAULT NULL,
  `HashTag` varchar(50) DEFAULT NULL,
  `MasterHash` varchar(50) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=111 ;
