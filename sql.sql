SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `Bookmarks` (
  `ID` int(5) NOT NULL,
  `DeviceID` varchar(33) DEFAULT NULL,
  `NewsStoryID` int(5) DEFAULT NULL,
  `UCVideoID` int(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `Calendar` (
  `ID` int(5) NOT NULL,
  `TimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `DistrictNumber` int(3) DEFAULT NULL,
  `Title` varchar(75) DEFAULT NULL,
  `MeetDate` varchar(75) DEFAULT NULL,
  `MeetLocation` varchar(200) DEFAULT NULL,
  `ScrapeHash` varchar(60) NOT NULL,
  `MasterHash` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `CrimeIncidents` (
  `ID` int(8) NOT NULL,
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
  `MasterHash` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `CrimeTypes` (
  `ID` int(4) NOT NULL,
  `Type` varchar(20) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `CurrentHash` (
  `ID` int(9) NOT NULL,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `HashName` varchar(30) NOT NULL,
  `Hash` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Devices` (
  `ID` int(6) NOT NULL,
  `TimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `DeviceID` varchar(100) DEFAULT NULL,
  `CurrentHashTag` varchar(150) DEFAULT NULL,
  `LastRequestIP` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `DistrictInfo` (
  `ID` int(5) NOT NULL,
  `TimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `DistrictNumber` int(3) DEFAULT NULL,
  `LocationAddress` varchar(75) DEFAULT NULL,
  `Phone` varchar(25) DEFAULT NULL,
  `EmailAddress` varchar(100) DEFAULT NULL,
  `CaptainName` varchar(60) DEFAULT NULL,
  `CaptainURL` varchar(300) DEFAULT NULL,
  `DetectiveDivision` varchar(30) NOT NULL,
  `isDivisionDetective` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `Images` (
  `ID` int(8) NOT NULL,
  `LocalImageURL` varchar(1000) NOT NULL,
  `RemoteImageURL` varchar(1000) NOT NULL,
  `NewsID` int(11) NOT NULL,
  `DistrictNumber` int(2) NOT NULL,
  `ScrapeHash` varchar(50) NOT NULL,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MasterHash` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `NewsStory` (
  `ID` int(7) NOT NULL,
  `TimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `StoryAuthor` varchar(50) NOT NULL,
  `DistrictNumber` int(2) DEFAULT NULL,
  `Title` varchar(300) DEFAULT NULL,
  `URL` varchar(500) DEFAULT NULL,
  `Description` varchar(10000) DEFAULT NULL,
  `GUID` varchar(300) DEFAULT NULL,
  `PubDate` varchar(50) DEFAULT NULL,
  `Category` varchar(50) DEFAULT NULL,
  `ImageURL` varchar(300) DEFAULT NULL,
  `ScrapeHash` varchar(50) NOT NULL,
  `TubeURL` varchar(300) DEFAULT NULL,
  `MasterHash` varchar(50) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `PSA` (
  `ID` int(2) NOT NULL,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DistrictNumber` int(2) DEFAULT NULL,
  `Email` varchar(61) DEFAULT NULL,
  `PSAAreaNum` varchar(10) DEFAULT NULL,
  `LieutenantName` varchar(50) DEFAULT NULL,
  `isCurrent` int(1) NOT NULL DEFAULT '0',
  `ScrapeHash` varchar(90) NOT NULL,
  `MasterHash` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ScrapeHashHistory` (
  `ID` int(255) NOT NULL,
  `HashName` varchar(100) NOT NULL,
  `HashTag` varchar(100) NOT NULL,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Shooting` (
  `ID` int(6) NOT NULL,
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
  `isOfficerInvolved` varchar(2) DEFAULT NULL,
  `isOffenderInj` varchar(2) DEFAULT NULL,
  `isOffenderDec` varchar(2) DEFAULT NULL,
  `LocationAddress` varchar(118) DEFAULT NULL,
  `LocationX` varchar(50) DEFAULT NULL,
  `LocationY` varchar(50) DEFAULT NULL,
  `isInside` int(2) DEFAULT NULL,
  `isOutside` int(2) DEFAULT NULL,
  `isFatal` int(2) DEFAULT NULL,
  `HashTag` varchar(60) NOT NULL,
  `MasterHash` varchar(60) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `UCVideos` (
  `ID` int(8) NOT NULL,
  `NewsID` int(100) NOT NULL,
  `TimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `VideoTitle` varchar(100) DEFAULT NULL,
  `Description` varchar(600) DEFAULT NULL,
  `VideoID` varchar(30) DEFAULT NULL,
  `VideoImageURL` varchar(300) NOT NULL,
  `VideoDate` varchar(30) DEFAULT NULL,
  `DistrictNumber` varchar(2) DEFAULT NULL,
  `CrimeType` varchar(30) DEFAULT NULL,
  `HashTag` varchar(50) DEFAULT NULL,
  `MasterHash` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `Bookmarks`
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `Calendar`
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `CrimeIncidents`
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `CrimeTypes`
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `CurrentHash`
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `Devices`
  ADD UNIQUE KEY `ID` (`ID`),
  ADD UNIQUE KEY `DeviceID` (`DeviceID`);

ALTER TABLE `DistrictInfo`
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `Images`
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `NewsStory`
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `PSA`
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `ScrapeHashHistory`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `Shooting`
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `UCVideos`
  ADD UNIQUE KEY `ID` (`ID`);


ALTER TABLE `Bookmarks`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
ALTER TABLE `Calendar`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2375;
ALTER TABLE `CrimeIncidents`
  MODIFY `ID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156697;
ALTER TABLE `CrimeTypes`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
ALTER TABLE `CurrentHash`
  MODIFY `ID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `Devices`
  MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `DistrictInfo`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
ALTER TABLE `Images`
  MODIFY `ID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=721;
ALTER TABLE `NewsStory`
  MODIFY `ID` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1450;
ALTER TABLE `PSA`
  MODIFY `ID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;
ALTER TABLE `ScrapeHashHistory`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6501;
ALTER TABLE `Shooting`
  MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1935;
ALTER TABLE `UCVideos`
  MODIFY `ID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;
