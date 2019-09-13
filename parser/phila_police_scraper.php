#!/usr/bin/php

<?php

		include('scripts/rss_php.php');				/////RSS DOM Lib
		include('scripts/simplehtmldom.php');		/////HTML DOM Lib

		define("MYSQL_SERVER", 'localhost');
		define("MYSQL_USERNAME", 'gerry');
		define("MYSQL_PASSWORD", 'Keithistheking');
		define("MYSQL_DATABASE",'PhillyPolice');
		
		$CONN = mysqli_connect(MYSQL_SERVER,MYSQL_USERNAME,MYSQL_PASSWORD,MYSQL_DATABASE);
		
		if(mysqli_connect_errno()){
		    die($NO_CONNECTION. header('HTTP/1.1 403 Forbidden'));
		}

		$RSS_URL = "https://pr.phillypolice.com/feed/";	//SITE URL
		//$RSS_URL = "http://10.20.30.11/feed.xml";	//SITE URL
		$HTTP_REQUEST_COUNT = 0;
		$HTTP_DATA_CONTENT_COUNT = 0;
		$HASH = sha1(time());

	////////////////////////////////////////////START of FUNCTIONS/////////////////////////////////////////////////////
		
		function writeToLog($s_txt){
			file_put_contents('p_pd.log', $s_txt."\n",FILE_APPEND);
		}
		
		function getRandomUserAgent(){
		    
		    $userAgents=array(
		        
		        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6",
		        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",
		        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
		        "Opera/9.20 (Windows NT 6.0; U; en)",
		        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.50",
		        "Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.1) Opera 7.02 [en]",
		        "Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; fr; rv:1.7) Gecko/20040624 Firefox/0.9",
		        "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/48 (like Gecko) Safari/48"
		    );
		    
		    $random = rand(0,count($userAgents)-1);
		    
		    return $userAgents[$random];
		}
		
		function wcType($text){
			if(strpos($text, "Robbery")){
				return "Robbery";
			}
			if(strpos($text, "Burglary")){
				return "Burglary";
			}
			if(strpos($text, "Theft")){
				return "Theft";
			}
			if(strpos($text, "Shooting")){
				return "Shooting";
			}
			if(strpos($text, "Assault")){
				return "Assault";
			}
			if(strpos($text, "Robberies")){
			    return "Robberies";
			}
			if(strpos($text, "Burglaries")){
			    return "Burglary";
			}
			if(strpos($text, "Sexual Assault")){
			    return "Sexual Assault";
			}
			if(strpos($text, "Counterfeiting")){
			    return "Fraud";
			}
			if(strpos($text, "Aggravated Assault")){
			    return "Assault";
			}
			if(strpos($text, "Fraud")){
			    return "Fraud";
			}
			if(strpos($text, "Vandalism")){
			    return "Vandalism";
			}
			
			return $text;
		}
		
		function getDISnum($title){
			
			if(strpos($title, "18th District")>=1){
				return '18';	
			}
			
			if(strpos($title, "16th District")>=1){
				return '16';
			}
			
			if(strpos($title, "25th District")>=1){
				return '25';
			}
			if(strpos($title, "17th District")>=1){
				return '17';
			}

			if(strpos($title, "24th District")>=1){
				return '24';	
			}
			
			if(strpos($title, "19th District")>=1){
				return '19';
			}
			
			if(strpos($title, "15th District")>=1){
				return '15';
			}
			
			if(strpos($title, "26th District")>=1){
				return '26';
			}

			if(strpos($title, "22nd District")>=1){
				return '22';
			}
			
			if(strpos($title, "39th District")>=1){
				return '39';
			}
			if(strpos($title, "35th District")>=1){
				return '35';
			}
			if(strpos($title, "14th District")>=1){
				return '14';
			}

			if(strpos($title, "12th District")>=1){
				return '12';
			}
			if(strpos($title, "2nd District")>=1){
				return '2';
			}
			if(strpos($title, "7th District")>=1){
				return '7';
			}
			if(strpos($title, "8th District")>=1){
				return '8';	
			}
			
			if(strpos($title, "1st District")>=1){
				return '1';
			}
			if(strpos($title, "3rd District")>=1){
				return '3';
			}
			if(strpos($title, "5th District")>=1){
				return '5';
			}
			if(strpos($title, "9th District")>=1){
				return '9';	
			}
			if(strpos($title, "6th District")>=1){
				return '6';
			}
			
			
		}
		
		function fixCAP($var){
		    $nd = $var;
		    
		    if($var == "missing person"){
		        $nd = "Missing Person";
		    }
		    
		    if($var == "robbery"){
		        $nd = "Robbery";
		    }
		    
		    if($var == "theft"){
		        $nd = 'Theft';
		    }
		    
		    return $nd;
		}
		
		
		function trimIT($param) {
		    $total = $param;
		    
		    if(strpos($total, "Wanted: ") !== false){
		        $total = str_replace("Wanted: ","",$total);
		        
		    }
		    
		    if(strpos($total, " [VIDEO]")){
		        $total = str_replace(" [VIDEO]","",$total);
		    }
		    
		    $last = html_entity_decode($total,ENT_QUOTES);
		    
		    
		    return $last;
		    
		    
		}
		
		function makeEmailStr($dNum, $psa){
		    $fg = $dNum;
		    
		    if($fg == "5"){
		        $fg = "05";
		    }
		    if($fg == "2"){
		        $fg = "02";
		    }
		    if($fg == "7"){
		        $fg = "07";
		    }
		    if($fg == "8"){
		        $fg = "08";
		    }
		    if($fg == "6"){
		        $fg = "06";
		    }
		    if($fg == "9"){
		        $fg = "09";
		    }
		    if($fg == "1"){
		        $fg = "01";
		    }
		    if($fg == "3"){
		        $fg = "03";
		    }
		    $num = str_replace(" ","",$fg);
		    $ss = str_replace(" ","",$psa);
		    $pp = str_replace("PSA","",$ss);
		    return "ppd.".$num."_psa".$pp."@phila.gov";
		}
		
		function makeEmailStrCAP($dNum){
		    $fg = $dNum;
		    
		    if($fg == "5"){
		        $fg = "05";
		    }
		    if($fg == "2"){
		        $fg = "02";
		    }
		    if($fg == "7"){
		        $fg = "07";
		    }
		    if($fg == "8"){
		        $fg = "08";
		    }
		    if($fg == "6"){
		        $fg = "06";
		    }
		    if($fg == "9"){
		        $fg = "09";
		    }
		    if($fg == "1"){
		        $fg = "01";
		    }
		    if($fg == "3"){
		        $fg = "03";
		    }
		    $num = str_replace(" ","",$fg);
		    
		    return "police.co_".$num."@phila.gov";
		}
		
		function byte_convert($bytes){
		    $symbol = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		    
		    $exp = 0;
		    $converted_value = 0;
		    if($bytes > 0){
		        $exp = floor( log($bytes)/log(1024));
		        $converted_value = ($bytes/pow(1024,floor($exp)));
		    }
		    
		    return sprintf( '%.2f '.$symbol[$exp], $converted_value);
		}
			
	////////////////////////////////////////////END of FUNCTIONS/////////////////////////////////////////////////////	
	
		
		
    ///////////////////////////////////////////////////// START NEWS FEED SCRAPER //////////////////////////////////////////////////////
	
		date_default_timezone_set('US/Eastern');
		writeToLog("\n"."////STARTING SCRAPER SCRIPT//// ");
		writeToLog("StartTimeStamp: ".date('Y-m-d H:i:s'));
		
		$curl = curl_init($RSS_URL);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$curl_response = curl_exec($curl);
		
		if($curl_response === FALSE){
		    writeToLog("FAILED TO LOAD RSS FEED ".$RSS_URL);
		}
		$HTTP_REQUEST_COUNT ++;
		$info = curl_getinfo($curl,CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		$HTTP_DATA_CONTENT_COUNT = $info + $HTTP_DATA_CONTENT_COUNT;
		writeToLog("Loading Page....: ".$RSS_URL);
		curl_close($curl);
		
		$rss = new rss_php;
		$rss->loadRSS($curl_response);
		
		$items = $rss->getItems();

			$ct = count($items);
			
			$ctt = 0; //news stories inserted
			$cff = 0; //news stories failed to insert
			$caa = 0; // news stories that already exist
			
		
			writeToLog("NUMBER OF OBJs IN NEWS FEED: ".$ct);
			
				for($i=0;$i<$ct;$i++){
	
				    $title = trimIT($items[$i]['title']);
					$DIS_NUM = getDISnum($title);
					$link  = $items[$i]['link'];
					$jeff = str_replace("'", "\\'",$items[$i]['description']);  // mysql hack for apostrophe
					$desc = html_entity_decode($jeff,ENT_QUOTES);
					$obj_id = $items[$i]['guid'];
					$author = $items[$i]['dc:creator'];
					$pubDate =  date('Y-m-d H:i:s', strtotime($items[$i]['pubDate']));
					$category = fixCAP($items[$i]['category']);
	//				$txt = $items[$i]['wfw:commentRss']; // RSS channel for the story it self
					$isWanted = strpos($title, "Wanted: ");
					$isVideo = strpos($title, " [VIDEO]");
					$html = str_get_html($items[$i]['description']);
					$ext = str_get_html($items[$i]['content:encoded']);
					$imgURL = $html->find('img',0)->src;
					$tubURL = $ext->find('iframe',0)->src;
					$shortTxT = $html->plaintext;
					$longTxT = $ext->plaintext;
					//$lgTxT = htmlspecialchars_decode($longTxT,ENT_QUOTES);
					$lgTxT = mysqli_real_escape_string($CONN, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $longTxT));
				
					$isThere = "SELECT `GUID` FROM `NewsStory` WHERE `GUID` = '$obj_id'";
					$answ = mysqli_query($CONN, $isThere);
					
					if($DIS_NUM == "" || empty($DIS_NUM)){
					    $DIS_NUM = 0;
					}
					
						if(mysqli_num_rows($answ) <= 0){
							
							$in_rec = "INSERT INTO `NewsStory` (`DistrictNumber`,`Title`,`URL`,`Description`,`GUID`,`PubDate`,`Category`,`ImageURL`,`TubeURL`,`ScrapeHash`,`StoryAuthor`)
											VALUES('$DIS_NUM','$title','$link','$lgTxT','$obj_id','$pubDate','$category','$imgURL','$tubURL','$HASH','$author')";
						 	
						 	$res = mysqli_query($CONN, $in_rec);
						 		
						 		if($res){
							 		$ctt++;
									// $image = file_get_contents($imgURL);
									// file_put_contents('imgs/test'.$i.'.jpg', $image);
							 		$dc = '/(DC\s)(\d{2})(-)(\d+)(-)(\d{6})/is';
							 		$div = '/(\w+)(\s)(Detective|Detectives) (Division)/is';
							 		$spec = '/(Special Victims Unit)/is';
							 		$homi = '/(Homicide Division)/is';
							 		$homi1 = '/(Homicide Unit)/is';
							 		$maj = '/(Major Crimes Unit)/is';
							 		$atf = '/(Arson Task Force)/is';
							 		$aid = '/(Accident Investigation Division)/is';
							 		
							 		$sex = "SELECT `ID` FROM `NewsStory` WHERE GUID = '$obj_id'";
							 		$xres = mysqli_query($CONN,$sex);
							 		$xarr = mysqli_fetch_array($xres);
							 		$id1 = $xarr['ID'];
							 		
							 		if(preg_match_all($dc,$desc,$match,PREG_PATTERN_ORDER)){
							 		    
							 		    if(preg_match($div,$desc,$match1)){
							 		        $dDiv = $match1[0];
							 		    }else if(preg_match($spec,$desc,$match2)){
							 		        $dDiv = $match2[0];
							 		    }else if(preg_match($homi,$desc,$match3)){
							 		        $dDiv = $match3[0];
							 		    }else if(preg_match($homi1,$desc,$match4)){
							 		        $dDiv = $match4[0];
							 		    }else if(preg_match($maj,$desc,$match5)){
							 		        $dDiv = $match5[0];
							 		    }else if(preg_match($atf,$desc,$match6)){
							 		        $dDiv = $match6[0];
							 		    }else if(preg_match($aid,$desc,$match7)){
							 		        $dDiv = $match7[0];
							 		    }else{
							 		        $dDiv = 0;
							 		    }
							 		    
							 		    for($i = 0; $i < count($match[0]); $i++){
							 		        $dcNUM = $match[0][$i];
							 		        $chk = "SELECT `DCNumber` FROM `DCNumber` WHERE `DCNumber` = '$dcNUM'";
							 		        $rxt = mysqli_query($CONN,$chk);
							 		        if(mysqli_num_rows($rxt) >=1){
							 		            //"ALREADY EXIST </br>";
							 		        }else{
							 		            $io = "INSERT INTO `DCNumber`(`DCNumber`,`DistrictNumber`,`NewsStoryID`,`PubDate`,`DetectiveDivision`)VALUES('$dcNUM','$dist','$id','$pubDate','$dDiv');";
							 		            //echo $io."</br>";
							 		            mysqli_query($CONN, $io);
							 		            // echo $io.'</br>';
							 		        }
							 		    }
							 		    
							 		    
							 		}
							 		
							 		
							 		
							 		
							 		
						 		}else{
							 		$cff++;
						 		}
						
						}else if(mysqli_num_rows($answ) == 1){
							$caa++;
						} 

		
					}

			
				
						writeToLog("RSS NEWS SCRAPER STOPPED.....");
						writeToLog("NUMBER OF OBJs INSERTED: ".$ctt);
						writeToLog("NUMBER OF OBJs FAILED TO INSERTED: ".$cff);
						writeToLog("NUMBER OF OBJs ALREADY EXIST: ".$caa);
				
				
				
				// UPDATE SCRAPE HASH ONLY IF A NEW STORY WAS INSERT INTO THE DATABASE
				if($ctt >=1){
						
					$up_hash = "INSERT INTO `ScrapeHashHistory` (`HashName`,`HashTag`) VALUES ('NewsStory','$HASH')";
					$is_good = mysqli_query($CONN, $up_hash);
						
						if($is_good){
								$is_there = "SELECT `HashName` FROM `CurrentHash` WHERE `HashName` = 'NewsStory'";
								$is_res = mysqli_query($CONN, $is_there);
									if(mysqli_num_rows($is_res) >=1){
										$up_date = "UPDATE `CurrentHash` SET `TimeStamp` = NOW(), `Hash` = '$HASH' WHERE `HashName` = 'NewsStory'";
										$res_fin = mysqli_query($CONN, $up_date);
											if($res_fin){
												writeToLog("HASH UPDATED FOR NewsStory TABLE IN DATABASE");
											}else{
												// FAILED TO UPDATE THE CURRENT HASH --
											}
											
											
									}else{
										$in_to = "INSERT INTO `CurrentHash` (`HashName`,`Hash`)VALUES('NewsStory','$HASH')";
										$res_d = mysqli_query($CONN, $in_to);
											if($res_d){
												writeToLog("HASH UPDATED FOR NewsStory TABLE IN DATABASE");
											}else{
												//Failed to insert --
											}
									
									}
									
							
							
							
						}
				}
				
				
///////////////////////////////////////////////////// END NEWS FEED SCRAPER //////////////////////////////////////////////////////
				

			
//////////////////////////////////////////// START SHOOTINGS SCRAPE /////////////////////////////////////////////////////////
				
				
				date_default_timezone_set('US/Eastern');
				$td1 = date('Y-m-d');
				$dd = date('l');
				$pDate = strtotime('last '.$dd);
				$td2 = date('Y-m-d',$pDate);
				$SHct = 0;
				$ssURL = 'https://phl.carto.com/api/v2/sql?q=SELECT%20*%20FROM%20shootings%20WHERE%20date_%20%3E=%20%27'.$td2.'%27%20AND%20date_%20%3C%20%27'.$td1.'%27';
				writeToLog("Loading Page....: ".$ssURL);
				
				
				$curl = curl_init($ssURL);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				$curl_response = curl_exec($curl);
				
				
				if($curl_response === FALSE){
				    writeToLog("FAILED TO GO TO ".$ssURL);
				}
				
				$HTTP_REQUEST_COUNT ++;
				$info = curl_getinfo($curl,CURLINFO_CONTENT_LENGTH_DOWNLOAD);
				$HTTP_DATA_CONTENT_COUNT = $info + $HTTP_DATA_CONTENT_COUNT;
				curl_close($curl);
				$curl_jason = json_decode($curl_response, true);
				
				
				
				$arr = $curl_jason['rows'];
				writeToLog("SHOOTING ROW COUNT: ".count($arr));
				
				foreach($arr as $feet){
				    
				    $d_id = $feet['cartodb_id'];
				    $obj_id = $feet['objectid'];
				    $year = $feet['year'];
				    $dc_num = $feet['dc_key'];
				    $c_code = $feet['code'];
				    $snuff = explode("T",$feet['date_']);
				    $d_date = $snuff[0];
				    $race = $feet['race'];
				    $gen = $feet['sex'];
				    $age = $feet['age'];
				    $wound = $feet['wound'];
				    $isOffI = $feet['officer_involved'];
				    $isOffenInj = $feet['offender_injured'];
				    $isOffDead = $feet['offender_deceased'];
				    $location = $feet['location'];
				    $pointx = $feet['point_x'];
				    $pointy = $feet['point_y'];
				    $dist = $feet['dist'];
				    $time_d = $feet['time'];
				    $in = $feet['inside'];
				    $out = $feet['outside'];
				    $fatal = $feet['fatal'];
				    
				    $isT = "SELECT `DataID` FROM `Shooting` WHERE `ObjID` = '$obj_id'";
				    $reg = mysqli_query($CONN, $isT);
				    
				    if(mysqli_num_rows($reg) >= 1){
				        // RECORD ALREADY EXIST
				    }else{
				        
				        $in = "INSERT INTO `Shooting` (`DataID`,`ObjID`,`Year`,`DCNumber`,`CrimeCode`,`CrimeDate`,`Race`,
                            `Gender`,`Age`,`Wound`,`isOfficerInvolved`,`isOffenderInj`,`isOffenderDec`,`LocationAddress`,
                                `LocationX`,`LocationY`,`DistrictNumber`,`CrimeTime`,`isInside`,`isOutside`,`isFatal`,`HashTag`) VALUES('$d_id','$obj_id','$year',
                                    '$dc_num','$c_code','$d_date','$race','$gen','$age','$wound','$isOffI','$isOffenInj','$isOffDead',
                                        '$location','$pointx','$pointy','$dist','$time_d','$in','$out','$fatal','$HASH')";
				        
				        $isG = mysqli_query($CONN, $in);
				            if($isG){
				                $SHct ++;
				            }
				    }
				    
				    
				    
				}
				
				writeToLog("SHOOTING RECORDS INSERTED: ".$SHct);
				
				if($SHct >= 1){
				    $up_hash = "INSERT INTO `ScrapeHashHistory` (`HashName`,`HashTag`) VALUES ('Shootings','$HASH')";
				    $is_good = mysqli_query($CONN, $up_hash);
				    
				    if($is_good){
				        $is_there = "SELECT `HashName` FROM `CurrentHash` WHERE `HashName` = 'Shootings'";
				        $is_res = mysqli_query($CONN, $is_there);
				        if(mysqli_num_rows($is_res) >=1){
				            $up_date = "UPDATE `CurrentHash` SET `TimeStamp` = NOW(), `Hash` = '$HASH' WHERE `HashName` = 'Shootings'";
				            $res_fin = mysqli_query($CONN, $up_date);
				            if($res_fin){
				                writeToLog("HASH UPDATED FOR Shootings TABLE IN DATABASE");
				            }else{
				                // FAILED TO UPDATE THE CURRENT HASH --
				            }
				            
				            
				        }else{
				            $in_to = "INSERT INTO `CurrentHash` (`HashName`,`Hash`)VALUES('Shootings','$HASH')";
				            $res_d = mysqli_query($CONN, $in_to);
				            if($res_d){
				                writeToLog("HASH UPDATED FOR Shootings TABLE IN DATABASE");
				            }else{
				                //Failed to insert --
				            }
				            
				        }
				        
				        
				        
				        
				    }
				    
				}else{
				    writeToLog("NO CHANGE IN SHOOTING RECORD");
				}
				
				
				
				
				
////////////////////////////////////////////END SHOOTINGS SCRAPE ///////////////////////////////////////////////////////////////////////				
				
				
				
				
				
////////////////////////////////////////////////////////////START CRIME INCIDENTS //////////////////////////////////////////////////////////////////////////////////////////////////////

				writeToLog("START CRIME INCIDENTS SCRAPE");
				date_default_timezone_set('US/Eastern');
				$pDate = strtotime('today - 2 days');
				$td2z = date('Y-m-d',$pDate);
				$gURL = 'https://phl.carto.com/api/v2/sql?q=SELECT%20*%20FROM%20incidents_part1_part2%20WHERE%20dispatch_date%20%3E=%20%27'.$td2z.'%27';
				writeToLog("GOING TO SITE: ".$gURL);
				$curl = curl_init($gURL);
				writeToLog("FETCHING CRIMES INCIDENTS DATA....");
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				$curl_response = curl_exec($curl);
				
				if($curl_response === FALSE){
				    writeToLog("NO DATA RETURNED CURL RETURNED FALSE");
				    writeToLog("CURL ERROR ".curl_error($curl));
				}
				$HTTP_REQUEST_COUNT ++;
				$info = curl_getinfo($curl,CURLINFO_CONTENT_LENGTH_DOWNLOAD);
				$HTTP_DATA_CONTENT_COUNT = $info + $HTTP_DATA_CONTENT_COUNT;
				curl_close($curl);
				$curl_jason = json_decode($curl_response, true);
				$d_arr = $curl_jason['rows'];
				writeToLog("JSON CRIMES OBJECT DATA COUNT: ".count($d_arr));
				$rw_ct = 0;
				foreach($d_arr as $tree){
				    
				    $d_id = $tree['cartodb_id'];
				    $obj_id = $tree['objectid'];
				    $dist = $tree['dc_dist'];
				    $psa = $tree['psa'];
				    $disTime = $tree['dispatch_time'];
				    $disDate = $tree['dispatch_date'];
				    $dc_key = $tree['dc_key'];
				    $loca = $tree['location_block'];
				    $crCode = $tree['ucr_general'];
				    $cr_name = $tree['text_general_code'];
				    $pointx = $tree['point_x'];
				    $pointy = $tree['point_y'];
				    
				    $isT = "SELECT `DataID` FROM `CrimeIncidents` WHERE `DataID` = '$d_id' OR `ObjID` = '$obj_id'";
				    $ret = mysqli_query($CONN, $isT);
				    
				    if(mysqli_num_rows($ret) >=1){
				        // RECORD EXIST
				    }else{
				        
				        $inin = "INSERT INTO `CrimeIncidents` (`DataID`,`ObjID`,`DistrictNumber`,`PSAArea`,
                                            `DispatchTime`,`DispatchDate`,`AddressBlock`,`CrimeCode`,`CrimeName`,
                                                `LocationX`,`LocationY`,`HashTag`)VALUES('$d_id','$obj_id','$dist','$psa','$disTime','$disDate',
                                                    '$loca','$crCode','$cr_name','$pointx','$pointy','$HASH')";
				        $iS_g = mysqli_query($CONN, $inin);
    				       
    				        if($iS_g){
    				            $rw_ct ++;
    				        }
				        
				    }
				    
				}
				
				writeToLog("CRIME INCIDENTS INSERTED: ".$rw_ct);
				
				if($rw_ct >= 1){
				    $up_hash = "INSERT INTO `ScrapeHashHistory` (`HashName`,`HashTag`) VALUES ('CrimeIncidents','$HASH')";
				    $is_good = mysqli_query($CONN, $up_hash);
				    
				    if($is_good){
				        $is_there = "SELECT `HashName` FROM `CurrentHash` WHERE `HashName` = 'CrimeIncidents'";
				        $is_res = mysqli_query($CONN, $is_there);
				        if(mysqli_num_rows($is_res) >=1){
				            $up_date = "UPDATE `CurrentHash` SET `TimeStamp` = NOW(), `Hash` = '$HASH' WHERE `HashName` = 'CrimeIncidents'";
				            $res_fin = mysqli_query($CONN, $up_date);
				            if($res_fin){
				                writeToLog("HASH UPDATED FOR CrimeIncidents TABLE IN DATABASE");
				            }else{
				                // FAILED TO UPDATE THE CURRENT HASH --
				            }
				            
				            
				        }else{
				            $in_to = "INSERT INTO `CurrentHash` (`HashName`,`Hash`)VALUES('Shootings','$HASH')";
				            $res_d = mysqli_query($CONN, $in_to);
				            if($res_d){
				                writeToLog("HASH UPDATED FOR CrimeIncidents TABLE IN DATABASE");
				            }else{
				                //Failed to insert --
				            }
				            
				        }
				        
				        
				        
				        
				    }
				}else{
				    writeToLog("NOT CRIME INCIDENTS UPDATE: ");
				}
				
				
				
				
				
				
				
////////////////////////////////////////////////////////////END CRIME INCIDENTS //////////////////////////////////////////////////////////////////////////////////////////////////////
				
			
				
				
				
////////////////////////////////////////////////////////////START UNSOLVED MURDERS //////////////////////////////////////////////////////////////////////////////////////////////////////
				
				$usmArray = array("https://www.phillyunsolvedmurders.com/component/tags/tag/18th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/25th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/24th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/26th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/17th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/22nd-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/39th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/35th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/14th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/19th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/16th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/12th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/15th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/3rd-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/6th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/9th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/2nd-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/5th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/7th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/8th-district/",
				"https://www.phillyunsolvedmurders.com/component/tags/tag/1st-district/");
				
				$intCT = 0;
				$intCT1 = 0;
				
				mysqli_set_charset($CONN, 'utf8mb4');
				writeToLog("STARTING TO SCRAPE UNSOLVED MURDERS");
				foreach($usmArray as $MURL){
				    $agent = getRandomUserAgent();
				    $site = 'https://www.phillyunsolvedmurders.com/';
				    $curl = curl_init($MURL);
				    writeToLog("GOING TO SITE: ".$MURL);
				    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				    //         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				    //         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				    curl_setopt($curl, CURLOPT_REFERER, $site);
				    curl_setopt($curl, CURLOPT_USERAGENT, $agent);
				    $curl_response = curl_exec($curl);
				    curl_close($curl);
				    
				    if($curl_response === FALSE){
				       writeToLog("NO DATA RETURNED CURL RETURNED FALSE");
				       writeToLog("CURL ERROR ".curl_error($curl));
				    }else{
				        $HTTP_REQUEST_COUNT ++;
				        $info = curl_getinfo($curl,CURLINFO_CONTENT_LENGTH_DOWNLOAD);
				        $HTTP_DATA_CONTENT_COUNT = $info + $HTTP_DATA_CONTENT_COUNT;
				        writeToLog("DATA RETURNED FROM SITE: ");
				        $html = str_get_html($curl_response);
				        $form = $html->find('#adminForm');
				        foreach($form as $item){
				            $ul = $item->find('ul.category');
				            foreach($ul as $li){
				                $h3 = $li->find('h3');
				                foreach($h3 as $title){
				                    $dcN = '(DC# \d{2}(-)\d{2}(-)\d{6})';
				                    $tt = $title->plaintext;
				                    if(preg_match($dcN,$tt,$match)){
				                        $dc = $match[0];
				                        $pt0 = explode(" - ",$tt);
				                        $str = trim($pt0[0]);
				                        $nurl = $title->getElementByTagName('a')->href;
				                        $sel = "SELECT `DCNumber` FROM `UnsolvedMurders` WHERE `DCNumber` = '$dc'";
				                        $res = mysqli_query($CONN, $sel);
				                        if(mysqli_num_rows($res)>=1){
				                            
				                        }else{
				                            
				                            $agent = getRandomUserAgent();
				                            $site = 'https://www.phillyunsolvedmurders.com'.$nurl;
				                            $curl2 = curl_init($site);
				                            curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
				                            //         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				                            //         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				                            curl_setopt($curl2, CURLOPT_REFERER, $site);
				                            curl_setopt($curl2, CURLOPT_USERAGENT, $agent);
				                            $curl_response2 = curl_exec($curl2);
				                            curl_close($curl2);
				                            if($curl_response2 === FALSE){
				                           
				                            }else{
				                                $HTTP_REQUEST_COUNT ++;
				                                $info = curl_getinfo($curl2,CURLINFO_CONTENT_LENGTH_DOWNLOAD);
				                                $HTTP_DATA_CONTENT_COUNT = $info + $HTTP_DATA_CONTENT_COUNT;
				                                $html2 = str_get_html($curl_response2);
				                                $spc = $html2->find('div#sp-component');
				                                $DC = '';
				                                $DESC = '';
				                                $VIC = '';
				                                
				                                foreach($spc as $big){
				                                    
				                                    $head = $big->find('.entry-header');
				                                    foreach($head as $h2){
				                                        $pre = $h2->plaintext; // DC NUMBER
				                                        $dcN = '(DC# \d{2}(-)\d{2}(-)\d{6})';
				                                        
				                                        if(preg_match($dcN,$pre,$match)){
				                                            $DC = $match[0];
				                                            $doe = preg_replace($dcN,"",$DC);
				                                            $VIC = trim($doe);
				                                            
				                                        }else{
				                                            $DC = $h2->plaintext;
				                                        }
				                                        
				                                        
				                                    }
				                                    
				                                    $div = $big->find('div[itemprop=articleBody] p');
				                                    $txt= '';
				                                    foreach($div as $p){
				                                        $dap = $p->plaintext;
				                                        if(preg_match('/(215-686-TIPS)/is',$dap)){
				                                            $DESC .= $p->plaintext; // DECSRIPTION
				                                            $real = mysqli_real_escape_string($CONN,$DESC);
				                                    //        $real = str_replace(chr(194),"",$real);
				                                            $in = "INSERT INTO `UnsolvedMurders`(`DCNumber`,`VictimName`,`NewsURL`,`ScrapeHash`,`Description`)VALUES('$dc','$str','$nurl','$HASH','$real')";
				                                            $req = mysqli_query($CONN,$in);
				                                            if($req){
				                                                $intCT ++;
				                                            }else{
				                                                writeToLog("INSERT FAILED ".$in);
				                                            }
				                                            
				                                            break;
				                                            
				                                        }
				                                        
				                                        if($DESC == ''){
				                                            $DESC = $p->plaintext;
				                                        }else{
				                                            $DESC .= $p->plaintext;
				                                        }
				                                        
				                                    }
				                                    
				                                    
				                                    
				                                    $tell = $big->find('ul#sige_0');
				                                    
				                                    if($tell){
				                                        
				                                        foreach($tell as $li){
				                                            $spn = $li->getElementByTagName('a')->getElementByTagName('img')->src;
				                                            //echo $spn."</br>";
				                                            $ser = "SELECT `DCNumber` FROM `USMImages` WHERE `DCNumber` = '$DC'";
				                                            //echo $ser."<br>";
				                                            $rey = mysqli_query($CONN,$ser);
				                                            if(mysqli_num_rows($rey) >= 1){
				                                                //echo "recorsExist <br>";
				                                            }else{
				                                                $ni = "INSERT INTO `USMImages` (`UCMurderURL`,`DCNumber`,`ScrapeHash`)VALUES('$spn','$DC','$HASH')";
				                                                $req1 = mysqli_query($CONN,$ni);
				                                                if($req1){
				                                                    $intCT1 ++;
				                                                }else{
				                                                    writeToLog("INSERT FAILED ");
				                                                }
				                                            }
				                                            
				                                            
				                                        }
				                                        
				                                    }else{
				                                        
				                                        
				                                        
				                                    }
				                                    
				                                    
				                                    
				                                    
				                                    
				                                }
				                                
				                                
				                            }
				                            
				                        }
				                    }else{
				                        writeToLog("No Match IN DC TITLE: ".$tt);
				                    }
				                    
				                }
				                
				            }
				        }
				    }
				    
				    
				    
				}
				
				writeToLog("UNSOLVED MURDERS INSERTED: ".$intCT);
				writeToLog("UNSOLVED MURDERS IMAGES: ".$intCT1);
				
				if($intCT >= 1){
				    
				    
				    
				    
				    
				    $up_hash = "INSERT INTO `ScrapeHashHistory` (`HashName`,`HashTag`) VALUES ('USMurders','$HASH')";
				    $is_good = mysqli_query($CONN, $up_hash);
				    
				    if($is_good){
				        $is_there = "SELECT `HashName` FROM `CurrentHash` WHERE `HashName` = 'USMurders'";
				        $is_res = mysqli_query($CONN, $is_there);
				        if(mysqli_num_rows($is_res) >=1){
				            $up_date = "UPDATE `CurrentHash` SET `TimeStamp` = NOW(), `Hash` = '$HASH' WHERE `HashName` = 'USMurders'";
				            $res_fin = mysqli_query($CONN, $up_date);
				            if($res_fin){
				                writeToLog("HASH UPDATED FOR USMurders TABLE IN DATABASE");
				            }else{
				                // FAILED TO UPDATE THE CURRENT HASH --
				            }
				            
				            
				        }else{
				            $in_to = "INSERT INTO `CurrentHash` (`HashName`,`Hash`)VALUES('USMurders','$HASH')";
				            $res_d = mysqli_query($CONN, $in_to);
				            if($res_d){
				                writeToLog("HASH UPDATED FOR USMurders TABLE IN DATABASE");
				            }else{
				                //Failed to insert --
				            }
				            
				        }
				        
				        
				        
				        
				    }
				    
				    
				    
				    
				    
				    
				    
				}else{
				    writeToLog("NO UNSOLVED MURDERS UPDATED");
				}
				
// 				if($intCT1 >= 1){
				    
// 				}
				
				
				
				
				
////////////////////////////////////////////////////////////END UNSOLVED MURDERS //////////////////////////////////////////////////////////////////////////////////////////////////////

				
				
				
				
				
				
////////////////////////////////////////////////////////////// START DISTRICT PAGE SCRAPER  //////////////////////////////////////////////
		
	$sites = array(
 					  "http://www.phillypolice.com/districts/18th/",
 					  "http://www.phillypolice.com/districts/25th/",
 					  "http://www.phillypolice.com/districts/24th/",
 					  "http://www.phillypolice.com/districts/26th/",
 					  "http://www.phillypolice.com/districts/17th/",
  					  "http://www.phillypolice.com/districts/22nd/",
 					  "http://www.phillypolice.com/districts/39th/",
 					  "http://www.phillypolice.com/districts/35th/",
 					  "http://www.phillypolice.com/districts/14th/",
 					  "http://www.phillypolice.com/districts/19th/",
 					  "http://www.phillypolice.com/districts/16th/",
 					  "http://www.phillypolice.com/districts/12th/",
 					  "http://www.phillypolice.com/districts/15th/",
 					  "http://www.phillypolice.com/districts/3rd/",
 					  "http://www.phillypolice.com/districts/6th/",
 					  "http://www.phillypolice.com/districts/9th/",
 					  "http://www.phillypolice.com/districts/2nd/",
 					  "http://www.phillypolice.com/districts/5th/",
 					  "http://www.phillypolice.com/districts/7th/",
 					  "http://www.phillypolice.com/districts/8th/",
					  "http://www.phillypolice.com/districts/1st/"
					      
					 );
					 
					 $loop_count = count($sites);
			
			
			
	/////////////////////////////DIRTy FOR LOOP BEGINS ////////////////////////////
			
			writeToLog("STARTING LOOP at: ".time());
			writeToLog("SITES BEING SCRAPED: ".$loop_count.' '.time());
			$alr = 0;		 
			 
			 for($oo=0;$oo<$loop_count;$oo++){
			     
			     $curl = curl_init();
			     curl_setopt($curl, CURLOPT_URL, $sites[$oo]);
			     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			     curl_setopt($curl, CURLOPT_USERAGENT, getRandomUserAgent());
			     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			     $curl_response = curl_exec($curl);
			     
			     if($curl_response === FALSE){
			         writeToLog("FAILED TO LOAD WEB SITE ".$sites[$oo]);
			     }
			     
			     $HTTP_REQUEST_COUNT ++;
			     $info = curl_getinfo($curl,CURLINFO_CONTENT_LENGTH_DOWNLOAD);
			     $HTTP_DATA_CONTENT_COUNT = $info + $HTTP_DATA_CONTENT_COUNT;
			     writeToLog("Loading Page....: ".$RSS_URL);
			     curl_close($curl);
			 		
				$html = str_get_html($curl_response);
				writeToLog("CURRENTLY SCRAPING: ".$sites[$oo].' '.time());  
				$infoDiv = $html->find('div.span3',1);
				if(!empty($infoDiv)){
				    $addI = $infoDiv->find('p',0)->plaintext;
				}
				
				$loc_prts = explode("Google Maps", $addI);
				$add_loc = trim($loc_prts[0]);
				$add_prts = $loc_prts[1];
				$adf = explode(" ", $add_prts);
				$loc_phone = $adf[2];
				$hdr_cont = $html->find('div#header-content');
				$DIS_N = "";
									
	//////////////FETCHING THE District Number/////////////
		
			foreach($hdr_cont as $item){
				$dis_txt = $item->find('div',5);
				$num_arr = explode("District",$dis_txt->plaintext);
				$dis_num = $num_arr[0];
				
				$pos = strpos($dis_num, "st");
				$pos2 = strpos($dis_num, "th");
				$pos3 = strpos($dis_num, "nd");
				$pos4 = strpos($dis_num, "rd");
				
					if($pos >=1 ){
						$dd_arr = explode("st", $dis_num);
						$DIS_N = $dd_arr[0];
					}
						
					if($pos2 >=1){
						$d_arr = explode("th", $dis_num);
						$DIS_N = $d_arr[0];
					}
					
					if($pos3 >=1){
						$ddd_arr = explode("nd", $dis_num);
						$DIS_N = $ddd_arr[0];
					}
			
					if($pos4 >=1){
						$dddd_arr = explode("rd", $dis_num);
						$DIS_N = $dddd_arr[0];
					}
		

			}
			
			
	//////////////END FETCHING THE District Number/////////////	
             		

	////////////////FETCHING Calendar Info ///////////////////////////////////////////////////////////////////////////////////////////////////////////
		
			$cal_info  = $html->find('div.span3',2);
			
			$meet_title = "";
			$time_loc = "";
			$addr = "";
			$daa = 0; // cal record inserted
			$dxx = 0; //insert failed for cal record
			$dzz = 0; // record exist

			foreach($cal_info->find('p') as $item){
						
					foreach($item->find('a') as $li){
	             		$meet_title = $li->plaintext;
						
	       			}
					
					foreach($item->find('strong') as $stg){
	             		$time_loc = $stg->plaintext;
									
	       			}
	       			
	       			if(preg_match("/(PM)/is",$item->plaintext)){
	       			    $loc_add = explode("PM",$item->plaintext);
	       			}
	       			
	       			if(preg_match("/(AM)/is",$item->plaintext)){
	       			    $loc_add = explode("AM",$item->plaintext);
	       			}
					
						//$loc_add = explode("PM",$item->plaintext);
						
						$addr = trim(preg_replace('/\s\s+/', ' ', $loc_add[1]));
						
						$is_cal = "SELECT `MeetDate`,`MeetLocation` FROM `Calendar` WHERE `MeetDate` = '$time_loc' AND `MeetLocation` = '$addr'";
						$res_cal = mysqli_query($CONN, $is_cal);
					
							if(mysqli_num_rows($res_cal) <= 0){
								$sql_cal = "INSERT INTO `Calendar` (`DistrictNumber`,`Title`,`MeetDate`,`MeetLocation`,`ScrapeHash`)
										VALUES('$DIS_N','$meet_title','$time_loc','$addr','$HASH')";
						              
						              if(!empty($meet_title)){
						                  $res = mysqli_query($CONN, $sql_cal);
						              }
								
									if($res){
										// cal record inserted
										$daa ++;
									}else{
										//insert faied for cal record
										$dxx ++;
									}	
							}else{
								//cal record exist
								$dzz ++;
							}
							
								
					
			} 

							if($daa >=1 && $alr == 0){ /////////////////////CALENDAR HASH UPDATE
								$alr ++;
								
								$up_uc = "INSERT INTO `ScrapeHashHistory` (`HashName`,`HashTag`)VALUES('Calendar','$HASH')";
								$cal_res = mysqli_query($CONN, $up_uc);
								
									if($cal_res){
											
										$is_cal = "SELECT `HashName` FROM `CurrentHash` WHERE `HashName` = 'Calendar'";
										$res_cal = mysqli_query($CONN, $is_cal);
											
											if(mysqli_num_rows($res_cal)>=1){
												
													
												$up_cal = "UPDATE `CurrentHash` SET `TimeStamp` = NOW(), `Hash` = '$HASH' WHERE `HashName` = 'Calendar'";
												$res_up = mysqli_query($CONN, $up_cal);
													
													if($res_up){
														//CALENDAR RECORD UPDATED	
													}else{
														//CALENDAR FAILED TO INSERT
													}
													
											}else{
													
												$in_cal = "INSERT INTO `CurrentHash` (`HashName`,`Hash`)VALUES('Calendar','$HASH')";
												$res_in = mysqli_query($CONN, $in_cal);
													
													if($res_in){
														// CURRENT HASH UPDATED
													}else{
														// CURRENT HASH FAILED
													}
											}
									}
								
							}/////////////////////END CALENDAR HASH UPDATE
		
		
		
////////////////END FETCHING Calendar Info ///////////////////////////////////////////////////////////////////////////////////////////////////////////		
		
		
	///////////////Captain Info///////////////////////////////////////
       
			$picURL = "";
			$cap_name = "";
			
		
			$cap_img_info = $infoDiv->find('div',2);
			$preFix = "https://www.phillypolice.com";
				foreach($cap_img_info->find('img') as $img){
					$pic_URL = $img->src;
					//$picURL = $preFix.$pic_URL;								
       			}
	
			
			$cap_info = $infoDiv->find('div',3);
			$cap_div = $cap_info->find('p',0);
			foreach($cap_div->find('a') as $a){
			   // $cap_name = $a->plaintext;
			    $cap_name = str_replace("'", "\\'",$a->plaintext);
       		}
			
			
			
			$id_dis = "SELECT `CaptainName` FROM `DistrictInfo` WHERE `CaptainName` = '$cap_name' AND `CaptainURL` = '$pic_URL'";
			$res_pd = mysqli_query($CONN, $id_dis);
				if(mysqli_num_rows($res_pd)<=0){
						
				    $redd = makeEmailStrCAP($DIS_N);
				    
				    
					$in_dis = "INSERT INTO `DistrictInfo` (`DistrictNumber`,`LocationAddress`,`Phone`,`EmailAddress`,`CaptainName`,`CaptainURL`)
								VALUES('$DIS_N','$add_loc','$loc_phone','$redd','$cap_name','$pic_URL')";
					
					$res_dis = mysqli_query($CONN, $in_dis);
					
						if($res_dis){
							// record inserted in district info
						}else{
							//record failed to insert
						}			
								
				}else{
					//district record already exist
				}
			
			
	///////////////END Captain Info///////////////////////////////////////		
	
	
	///////////////////////////PSA Info //////////////////////////////////

			$psa_CT = 0;
			foreach($infoDiv->find('p.district-psa') as $psa){
				foreach($psa->find('strong') as $p){
				$lt_name = html_entity_decode($p->plaintext,ENT_QUOTES);
				$arry = explode("-",$psa->plaintext); 	
				$psa_area = $arry[0];
				// echo $psa_area.'</br>';
				// echo $p.'</br>';
				
					$is_psa = "SELECT `ID`,`DistrictNumber`,`PSAAreaNum`,`LieutenantName` FROM `PSA` WHERE `DistrictNumber` = '$DIS_N' 
								AND `PSAAreaNum` = '$psa_area' AND `LieutenantName` = '$lt_name'";
					$res_is_chk = mysqli_query($CONN, $is_psa);
					
						if(mysqli_num_rows($res_is_chk) <=0){
						    $ccK = "SELECT `DistrictNumber`,`PSAAreaNum` FROM `PSA` WHERE `DistrictNumber` = '$DIS_N'
						        AND `PSAAreaNum` = '$psa_area'";
						    $reCCK = mysqli_query($CONN, $ccK);
						    $happ = makeEmailStr($DIS_N,$psa_area);
						      
						      if(mysqli_num_rows($reCCK) >=1){
						          $upd = "UPDATE `PSA` SET `isCurrent` = 0 WHERE `PSAAreaNum` = '$psa_area' AND `DistrictNumber` != '$DIS_N'  AND `LieutenantName` = '$lt_name'";
						          $in_psa = "INSERT INTO `PSA` (`DistrictNumber`,`PSAAreaNum`,`LieutenantName`,`ScrapeHash`,`isCurrent`,`Email`)VALUES('$DIS_N','$psa_area','$lt_name','$HASH',1,'$happ')";
						          $res_upd = mysqli_query($CONN, $udp);
						          $res_psa = mysqli_query($CONN, $in_psa);
						              
						              if($res_psa){
						                  $psa_CT ++;
						              }
						          
						      }else{
						          $in_psa1 = "INSERT INTO `PSA` (`DistrictNumber`,`PSAAreaNum`,`LieutenantName`,`ScrapeHash`,`isCurrent`,`Email`)VALUES('$DIS_N','$psa_area','$lt_name','$HASH',1,'$happ')";
						          $res_psa1 = mysqli_query($CONN, $in_psa1);
						          
						              if($res_psa1){
						                  $psa_CT ++;
						              }
						      }
						    
						    
						    
							
							
// 								if($res_psa){
// 									// insert the PAS record
// 								}else{
// 									// insert of PSA record failed
// 								}
								
								
								
						}else if(mysqli_num_rows($res_is_chk) >=1){
						    // PSA Already UPDATE GOOD
						    $fex = mysqli_fetch_array($res_is_chk);
						    $idd = $fex['ID'];
						    
						        $up = "UPDATE `PSA` SET `isCurrent` = 1 WHERE `ID` = '$idd'";
						        $rus = mysqli_query($CONN, $up);
						        
						      
						        
						    }
						    
						    
						    
						    
						    
						    
						    
						    
						    
						    
							
						    
							
									
						}
				
					
				
									
       			}	
				
       			if($psa_CT >= 1){
				    $uop = "UPDATE `CurrentHash` SET `TimeStamp` = NOW(), `Hash` = '$HASH' WHERE `HashName` = 'PSA'";
				    mysqli_query($CONN, $uop);
				}
       		
			
			
		///////////////////////////END OF PSA Info //////////////////////////////////		

		
		
		///////////////////////////CLEAN UP IMAGES THAT HAVE BLANK NEWS IDs //////////////////////////////////
			
			
//        			$getNum = "SELECT `RemoteImageURL` FROM `Images` WHERE `NewsID` = 0";
//        			$res = mysqli_query($CONN, $getNum);
       			
//        			while($row = mysqli_fetch_array($res)){
//        			    $x_id = $row['RemoteImageURL'];
//        			    $q = "UPDATE `Images` SET `NewsID` = (SELECT `ID` FROM `NewsStory` WHERE `ImageURL` = '$x_id' LIMIT 1) WHERE '$x_id' = `RemoteImageURL`";
//        			    mysqli_query($CONN, $q);
//        			}

       			
                //// IMAGES ID CLEAN UP
       			$fg = "SELECT `RemoteImageURL` FROM `Images` WHERE `NewsID` = 0";
       			$res = mysqli_query($CONN, $fg);
       			
       			while($row = mysqli_fetch_array($res)){
       			    
       			    $tol = $row['RemoteImageURL'];
       			    
       			    $r = "SELECT `ID` FROM `NewsStory` WHERE `ImageURL` = '$tol'";
       			    $red = mysqli_query($CONN, $r);
       			    
       			    if(mysqli_num_rows($red) >=1){
       			        $raw = mysqli_fetch_array($red);
       			        $lo = $raw['ID'];
       			        $up = "UPDATE `Images` SET `NewsID` = '$lo' WHERE `RemoteImageURL` = '$tol'";
       			        mysqli_query($CONN, $up);
       			    }
       			    
       			}
       			    /////// UCVIDEOS ID CLEANUP
       			    $fg = "SELECT `VideoID` FROM `UCVideos` WHERE `VideoID` IS NOT NULL";
       			    $res = mysqli_query($CONN, $fg);
       			    
       			    while($row = mysqli_fetch_array($res)){
       			        
       			        $tol = $row['VideoID'];
       			        
       			        $r = "SELECT `ID` FROM `NewsStory` WHERE `TubeURL` LIKE '%$tol%'";
       			        $red = mysqli_query($CONN, $r);
       			        
       			        if(mysqli_num_rows($red) >=1){
       			            $raw = mysqli_fetch_array($red);
       			            $lo = $raw['ID'];
       			            $up = "UPDATE `UCVideos` SET `NewsID` = '$lo' WHERE `VideoID` = '$tol'";
       			            mysqli_query($CONN, $up);
       			        }
       			        
       			        
       			    }
       			
       			
       			
       			///////////////////////////END CLEAN UP IMAGES THAT HAVE BLANK NEWS IDs //////////////////////////////////
			 

			 
					 
	} 
	
	
	
	
	
		
/////////////////////////////////////////////////////////////// END DISTRICT PAGE SCRAPER  //////////////////////////////////////////
	
	
	
            	$VIX_URL = "https://www.phillypolice.com/news/unsolved-crime-surveillance-videos/";	//SITE URL
            	
            	
            	$curl = curl_init($VIX_URL);
            	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            	curl_setopt($curl, CURLOPT_USERAGENT, getRandomUserAgent());
            	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            	// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            	$curl_response = curl_exec($curl);
            	
            	if($curl_response === FALSE){
            	    writeToLog("FAILED TO LOAD UC VIDEOS");
            	}
            	
            	$HTTP_REQUEST_COUNT ++;
            	$info = curl_getinfo($curl,CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            	$HTTP_DATA_CONTENT_COUNT = $info + $HTTP_DATA_CONTENT_COUNT;
            	curl_close($curl);
	
	
				//$uchtml = file_get_html("https://10.0.0.206/phillyPD/uscvideos.html");
				$uchtml = str_get_html($curl_response);
				// $infoDiv = $html->find('div.span3',1);
				// $addI = $infoDiv->find('p',0)->plaintext;
				// $loc_prts = explode("Google Maps", $addI);
				// $add_loc = $loc_prts[0];
				// $add_prts = $loc_prts[1];
				// $adf = explode(" ", $add_prts);
				// $loc_phone = $adf[2];
				// $hdr_cont = $html->find('div#header-content');
				// $DIS_N = "";
				
				$ucc = 0; //video added successfully
				$uxx = 0; //video insert failed
				$uaa = 0;
				
				$div_box = $uchtml->find('div#video-cards-wrapper');
					foreach ($div_box as $vid) {
						$ape = $vid->find('.video-card-thumbnail');
							foreach ($ape as $vv_item) {
								//echo $vv_item;	
								$vid_img = $vv_item->getElementByTagName('a')->getElementByTagName('img')->src;
								$crd_title = $vv_item->getElementByTagName('div.video-card-title');
								$d_txt = $crd_title->getElementByTagName('a')->getAttribute("data-description");
								$vid_arr = $vv_item->getElementByTagName('div.video-card-meta')->plaintext;
								$r = explode("&nbsp;&nbsp;&nbsp;", $vid_arr);
								
								$vi_date = $r[0];
								$vit_type = wcType($crd_title);
								$desc = utf8_decode($d_txt);
								//$vid_img = $vv_item->getElementByTagName('a')->getElementByTagName('img')->src;
								$vid_id = $crd_title->getElementByTagName('a')->getAttribute("data-videoid");
								$vid_title = $crd_title->getElementByTagName('a')->getAttribute("data-title");
								
									
								$ref = strip_tags($desc);

								$is_ne = "SELECT `ID`,`DistrictNumber` FROM `NewsStory` WHERE `TubeURL` LIKE '%$vid_id%'";
								$res_ne = mysqli_query($CONN, $is_ne);
								
								    if(mysqli_num_rows($res_ne) >=1){
								        $mRow = mysqli_fetch_array($res_ne);
								        $p_div = $mRow['DistrictNumber'];
								        $nID = $mRow['ID'];
								        
								        $is_vid = "SELECT `VideoID` FROM `UCVideos` WHERE `VideoID` = '$vid_id'";
								        $res_vi = mysqli_query($CONN, $is_vid);
								        
								        if(mysqli_num_rows($res_vi) <=0){
								            $in_vid = "INSERT INTO `UCVideos` (`VideoTitle`,`Description`,`VideoID`,`VideoDate`,`CrimeType`,`HashTag`,`VideoImageURL`,`DistrictNumber`,`NewsID`)
													VALUES ('$vid_title','$ref','$vid_id','$vi_date','$vit_type','$HASH','$vid_img','$p_div','$nID')";
								            
								            if($vid_title !== 'Private video'){
								                $res_in = mysqli_query($CONN, $in_vid);
								                
								                if($res_in){
								                    $ucc ++; // video added successfully
								                }else{
								                    $uxx ++; //video faile to insert
								                }
								            }
								            
								            
								            
								        }else{
								            $uaa ++;
								            // video recod already exist
								        }
								        
								        
								    }else{
								        
								        $is_vid = "SELECT `VideoID` FROM `UCVideos` WHERE `VideoID` = '$vid_id'";
								        $res_vi = mysqli_query($CONN, $is_vid);
								        
								        
								        if(mysqli_num_rows($res_vi) <=0){
								            $p_div = 00;
								            $nID = 0;
								            $in_vid = "INSERT INTO `UCVideos` (`VideoTitle`,`Description`,`VideoID`,`VideoDate`,`CrimeType`,`HashTag`,`VideoImageURL`,`DistrictNumber`,`NewsID`)
													VALUES ('$vid_title','$ref','$vid_id','$vi_date','$vit_type','$HASH','$vid_img','$p_div','$nID')";
								            
								            if($vid_title !== 'Private video'){
								                $res_in = mysqli_query($CONN, $in_vid);
								                
								                if($res_in){
								                    $ucc ++; // video added successfully
								                }else{
								                    $uxx ++; //video faile to insert
								                }
								            }
								            
								            
								            
								        }else{
								            $uaa ++;
								            // video recod already exist
								        }
								        
								    }
								
									
							
							

								
							}
					}				
					
					
					writeToLog("HTTP REQUEST COUNT: ".$HTTP_REQUEST_COUNT);
					writeToLog("HTTP REQUEST DATA SIZE: ".byte_convert($HTTP_DATA_CONTENT_COUNT));

									if($ucc >=1){
										$up_uc = "INSERT INTO `ScrapeHashHistory` (`HashName`,`HashTag`)VALUES('UCVideos','$HASH')";
										$up_res = mysqli_query($CONN, $up_uc);
											
											if($up_res){
												$sel_up = "SELECT `HashName` FROM `CurrentHash` WHERE `HashName` = 'UCVideos'";
												$res_up = mysqli_query($CONN, $sel_up);
													if(mysqli_num_rows($res_up) >=1){
														$up_up = "UPDATE `CurrentHash` SET `TimeStamp` = NOW(), `Hash` = '$HASH' WHERE `HashName` = 'UCVideos'";
														$update_res = mysqli_query($CONN, $up_up);
															if($update_res){
																// CURRENT HASH FROM UCVIDEOS IS UPDATED
															}else{
																// CURRENT HASH FOR UCVIDEOS FAILED TO UPDATE
															}
													}else{
														$in_rec = "INSERT INTO `CurrentHash` (`HashName`,`Hash`)VALUES('UCVideos','$HASH')";
														$ress_in = mysqli_query($CONN, $in_rec);
															if($ress_in){
																// NEW CURRENT HASH UPDATED
															}else{
																// NEW USC HASH FAILED TO INSERT
															}
													}
											}
									}

///////////////////////////////////////////////// MASTER HASH SETUP ////////////////////////////////////////////////////////////

				$is_mas_hash = "SELECT `Hash` FROM `CurrentHash` WHERE `HashName` = 'MasterHash'";
				$res_isgood = mysqli_query($CONN, $is_mas_hash);
					
					if(mysqli_num_rows($res_isgood) >=1){
						
						$update_hash = "SELECT `HashName`,`Hash` FROM `CurrentHash` WHERE NOT `HashName` = 'MasterHash'";
						$res_up_hash = mysqli_query($CONN, $update_hash);
						$m_str = 0;
							
							if(mysqli_num_rows($res_up_hash) >=1){
									
								$n_str_H = 0;
								$c_str_H = 0;
								$u_vid_H = 0;
								$p_sa = 0;
								$d_shoot = 0;
								
									while($rowp = mysqli_fetch_array($res_up_hash)){
										
										if($rowp['HashName'] == 'NewsStory'){
											$n_str_H = $rowp['Hash'];
										}else if($rowp['HashName'] == 'UCVideos'){
											$u_vid_H = $rowp['Hash'];
										}else if($rowp['HashName'] == 'Calendar'){
											$c_str_H = $rowp['Hash'];
										}else if($rowp['HashName'] == 'PSA'){
											$p_sa = $rowp['Hash'];
										}else if($rowp['HashName'] == 'Shooting'){
										    $d_shoot = $rowp['Hash'];
										}else if($rowp['HashName'] == 'CrimeIncidents'){
										    $p_ccc = $rowp['Hash'];
										}
										
									}
									
									$m_str = sha1($n_str_H.$c_str_H.$u_vid_H.$p_sa.$d_shoot.$p_ccc);
									$in_hash_m = "UPDATE `CurrentHash` SET `TimeStamp` = NOW(), `Hash` = '$m_str' WHERE `HashName` = 'MasterHash'";
									$res_don = mysqli_query($CONN, $in_hash_m);
									   
									   if($res_don){
									       
									       $in_in = "INSERT INTO `ScrapeHashHistory` (`HashName`,`HashTag`)VALUES('MasterHash','$m_str')";
									       mysqli_query($CONN, $in_in);
									       
									       $cal_sel = "SELECT `ID` FROM `Calendar` WHERE `MasterHash`= ''  AND `ScrapeHash` = '$HASH'";
									       $re_cal = mysqli_query($CONN, $cal_sel);
									       
									       if(mysqli_num_rows($re_cal) >=1){
									           
									           while($toi = mysqli_fetch_array($re_cal)){
									               $d_id = $toi['ID'];
									               $upp = "UPDATE `Calendar` SET `MasterHash` = '$m_str' WHERE `ID` = '$d_id'";
									               mysqli_query($CONN, $upp);
									           }
									           
									       }
									       
									       //IMAGES ARE SCRAPED BY NODE
// 									       $img_se = "SELECT `ID` FROM `Images` WHERE `MasterHash`= '' AND `ScrapeHash` = '$HASH'";
// 									       $re_img = mysqli_query($CONN, $img_se);
									      
// 									       if(mysqli_num_rows($re_img) >=1){
									           
// 									           while($tor = mysqli_fetch_array($re_img)){
// 									               $d_id = $tor['ID'];
// 									               $upp = "UPDATE `Iamges` SET `MasterHash` = '$m_str' WHERE `ID` = '$d_id'";
// 									               mysqli_query($CONN, $upp);
// 									           }
									           
// 									       }
									       
									      
									      // $n_sto = "SELECT `ID` FROM `NewsStory` WHERE `MasterHash`= '' AND `ScrapeHash` = '$HASH'";
									       $n_sto = "SELECT `ID` FROM `NewsStory` WHERE `MasterHash` IS NULL OR `MasterHash` = '' AND `ScrapeHash` = '$HASH'";
									       $re_sto = mysqli_query($CONN, $n_sto);

									       if(mysqli_num_rows($re_sto) >=1){
									           
									           while($tot = mysqli_fetch_array($re_sto)){
									               $d_id = $tot['ID'];
									               $upp = "UPDATE `NewsStory` SET `MasterHash` = '$m_str' WHERE `ID` = '$d_id'";
									               mysqli_query($CONN, $upp);
									           }
									           
									       }
									       
									      // $psa_sel = "SELECT `ID` FROM `PSA` WHERE `MasterHash`= '' AND `ScrapeHash` = '$HASH'";
									       $psa_sel = "SELECT `ID` FROM `PSA` WHERE `MasterHash` IS NULL OR `MasterHash` = '' AND `ScrapeHash` = '$HASH'";
									       $re_psa = mysqli_query($CONN, $psa_sel);
									       
									       if(mysqli_num_rows($re_psa) >=1){
									           
									           while($toz = mysqli_fetch_array($re_psa)){
									               $d_id = $toz['ID'];
									               $upp = "UPDATE `PSA` SET `MasterHash` = '$m_str' WHERE `ID` = '$d_id'";
									               mysqli_query($CONN, $upp);
									           }
									           
									       }
									       
									       //$uc_vids = "SELECT `ID` FROM `UCVideos` WHERE `MasterHash`= '' AND `HashTag` = '$HASH'";
									       $uc_vids = "SELECT `ID` FROM `UCVideos` WHERE `MasterHash` IS NULL OR `MasterHash` = '' AND `HashTag` = '$HASH'";
									       $re_ucv = mysqli_query($CONN, $uc_vids);
									       
									       if(mysqli_num_rows($re_ucv) >=1){
									           
									           while($tog = mysqli_fetch_array($re_ucv)){
									               $d_id = $tog['ID'];
									               $upp = "UPDATE `UCVideos` SET `MasterHash` = '$m_str' WHERE `ID` = '$d_id'";
									               mysqli_query($CONN, $upp);
									           }
									           
									       }
									       
									       //$shot_pu = "SELECT `ID` FROM `Shooting` WHERE `MasterHash`= '' AND `HashTag` = '$HASH'";
									       $shot_pu = "SELECT `ID` FROM `Shooting` WHERE `MasterHash` IS NULL OR `MasterHash` = '' AND `HashTag` = '$HASH'";
									       $rx_ucx = mysqli_query($CONN, $shot_pu);
									       
									       if(mysqli_num_rows($rx_ucx) >=1){
									           
									           while($tox = mysqli_fetch_array($rx_ucx)){
									               $x_id = $tox['ID'];
									               $upx = "UPDATE `Shooting` SET `MasterHash` = '$m_str' WHERE `ID` = '$x_id'";
									               mysqli_query($CONN, $upx);
									           }
									           
									       }
									       
									      // $gInc = "SELECT `ID` FROM `CrimeIncidents` WHERE `MasterHash`= '' AND `HashTag` = '$HASH'";
									       $gInc = "SELECT `ID` FROM `CrimeIncidents` WHERE `MasterHash` IS NULL OR `MasterHash` = '' AND `HashTag` = '$HASH'";
									       $gIx = mysqli_query($CONN, $gInc);
									       
									       if(mysqli_num_rows($gIx) >=1){
									           
									           while($toxx = mysqli_fetch_array($gIx)){
									               $xx_id = $toxx['ID'];
									               $upxx = "UPDATE `CrimeIncidents` SET `MasterHash` = '$m_str' WHERE `ID` = '$xx_id'";
									               mysqli_query($CONN, $upxx);
									           }
									           
									       }
									       
									       
									       
									   }

								}else{
									///NO HASES EXIST AT ALL ???
								}
							

					}else{
							//// CREATE A HASH IF ONE DOES NOT EXIST
							
						$update_hash = "SELECT `HashName`,`Hash` FROM `CurrentHash` WHERE NOT `HashName` = 'MasterHash'";
						$res_up_hash = mysqli_query($CONN, $update_hash);
						$m_str = 0;
							
							if(mysqli_num_rows($res_up_hash) >=1){
									
								$n_str_H = 0;
								$c_str_H = 0;
								$u_vid_H = 0;
								$p_sa = 0;
								
									while($rowp = mysqli_fetch_array($res_up_hash)){
										
										if($rowp['HashName'] == 'NewsStory'){
											$n_str_H = sha1($rowp['Hash']);
										}else if($rowp['HashName'] == 'UCVideos'){
											$u_vid_H = sha1($rowp['Hash']);
										}else if($rowp['HashName'] == 'Calendar'){
											$c_str_H = sha1($rowp['Hash']);
										}else if($rowp['HashName'] == 'PSA'){
										    $p_sa = sha1($rowp['Hash']);
										}else if($rowp['HashName'] == 'Shooting'){
										    $d_shoot = $rowp['Hash'];
										}
										
									}
									
									$m_str = sha1($n_str_H.$c_str_H.$u_vid_H.$p_sa.$d_shoot);
									$in_MH = "INSERT INTO `CurrentHash` (`HashName`,`Hash`)VALUES('MasterHash','$m_str')";
									mysqli_query($CONN, $in_MH);
							
							}else{
								// NO OTHER HASES EXIST in the DATABASE
							}
						
						
						
						
						
						
						
						
					}					
					
					
					
				
///////////////////////////////////////////////// END MASTER HASH SETUP ////////////////////////////////////////////////////////////					
					
									

	





?>
