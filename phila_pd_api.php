<?php


        //INCLUDE NEWSOBJECT CLASS
        include('php/NewsObject.php');
        include('php/DistrictInfoObject.php');
        include('php/PSAObject.php');
        include('php/CalendarObject.php');
        include('php/UCVideoObject.php');
        include('php/ShootingObject.php');
        include('php/CrimeObject.php');
    
	    // MySQL Server Settings
        define("MYSQL_SERVER", 'localhost');
        define("MYSQL_USERNAME", 'gerry');
        define("MYSQL_PASSWORD", 'Keithistheking');
        define("MYSQL_DATABASE",'PhillyPolice');
       
        define('R_MD5_MATCH', '/^[a-f0-9]{32}$/i'); //MD5 REGEX CHECKER
        define('NUM_ONLY', '/^\d{1,6}$/');
        $IP = $_SERVER['REMOTE_ADDR'];
        $PROTO1 = "http://phillypd.info/api/v1/"; //URL PREFIX
        $IMG_DIR = "http://10.20.30.10/phillyPD/images/"; // IMAGE DIRECTORY
        $data = json_decode(file_get_contents('php://input'),true);
        
        
        
        
        // ERRORS MESSAGES
        $NO_DATABASE = json_encode(array("error"=>"true","msg"=>"Database Does Not Exist"));
        $NO_CONNECTION = json_encode(array("error"=>"true","msg"=>"Could Not Connect To Database"));
        $MYSQL_ERROR = json_encode(array("error"=>"true","msg"=>"MYSQL ERROR"));
        $INVALID_DEVICE_ID = json_encode(array("error"=>"true","msg"=>"INVALID DEVICE ID"));
        $INVALID_DEVICE = json_encode(array("error"=>"true","msg"=>"INVALID DEVICE BEING USED"));
        $ZERO_HASH_KEYS = json_encode(array("error"=>"true","msg"=>"NO HASH KEYS FOUND"));
        $INVALID_DIS = json_encode(array("error"=>"true","msg"=>"INVALID DISTRICT"));
        
        
        //CONNECT TO DATABASE
        $CONN = mysqli_connect(MYSQL_SERVER,MYSQL_USERNAME,MYSQL_PASSWORD,MYSQL_DATABASE);
        
        if(mysqli_connect_errno()){
            die($NO_CONNECTION. header('HTTP/1.1 403 Forbidden'));
        }
        
        
        ///////FUNCTIONS/////////////////////
        ////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////
        
        function writeToLog($s_txt){
            file_put_contents('test.log', $s_txt."\n",FILE_APPEND);
        }
    	
			function convertdiv($distNum){

			    $div = null;

			    $south = array("17","3","1");
			    $cen = array("22","6","9");
			    $east = array("25","24","26");
			    $norE = array("15","2","7","8");
			    $norW = array("39","35","14","5");
			    $souW = array("19","16","12","18");

			    if(in_array($distNum,$south)){
			        $div = "South";
			    }else if(in_array($distNum,$cen)){
			        $div = "Central";
			    }else if(in_array($distNum,$east)){
			        $div = "East";
			    }else if(in_array($distNum,$norE)){
			        $div = "Northeast";
			    }else if(in_array($distNum,$norW)){
			        $div = "Northwest";
			    }else if(in_array($distNum,$souW)){
			        $div = "Southwest";
			    }

			    return $div;

			}
			
// 			function trimCat($cat){
			    
// 			   $words = array("Missing Juvenile",
// 			        "Crime Alerts",
// 			        "Traffic Alerts",
// 			        "News",
// 			        "Missing Endangered Person",
// 			        "Missing Persons",
// 			         "Missing Endangered Juvenile");
			   
// 			   if(in_array($cat, $words)){
// 			       return $cat;
// 			   }else{
// 			       return 0;
// 			   }
			    
			    
// 			}
			
			function trimLieName($add){
			    
			    $nam = $add;
			    
			    $patt = "/(LT)( )(LT)(\.)/is";
			    $patt1 = "/(LT)( )(Lt)(\.)/is";
			    $patt2 = "/(LT)( )/is";
			    
			    if(preg_match($patt, $add)){
			        $nam = str_replace("LT LT. ","Lt. ",$add);
			        
			    }
			    
			    if(preg_match($patt1, $add)){
			        $nam = str_replace("LT Lt. ","Lt. ",$add);
			        
			    }
			    
			    return $nam;
			}
			
			function trimTitle($title,$istrue){
			    
			    $sfc = '/(Suspect\(s\)|Suspects|Suspect)( for)/is';
			    //$sfc = '/(Wanted )|Suspect|s( for)/is';
			    $enl1 = '/(in the )(\d+)(\w+)( District)/is';
			    $enl = '/(in the)/is';
			    $mip = '/(Missing Person)/is';
			    $miR = '/(Missing Person )(-)/is';
			    $mit = '/(Missing Tender Age)/is';
			    $ema = '/(Endangered Missing Adult)/is';
			    $emb = '/(Endangered Missing Baby)/is';
			    $emj = '/(Endangered Missing Juvenile)/is';
			    $emp = '/(Endangered Missing Person )(-)/is';
			    $fbi = '/(FBI\/PPD Violent Crimes Task Force Search for)/is';
			    $map = '/(Missing Adult Person)/is';
			    $mee = '/(Missing Elderly Endangered Person)/is';
			    $me1 = '/(Missing Endangered Elderly |\- Person )/is';
			    $mej = '/(Missing Endangered Juvenile)/is';
			    $me6 = '/(Missing Endangered Juveniles)/is';
			    $me9 = '/(Missing Endangered Person )/is';
			    $me91 = '/(Missing Endangered Person )(-)/is';
			    $mta = '/(Missing Endangered Tender Age)/is';
			    $mt1 = '/(Missing Endangered Tender age)/is';
			    $mi2 = '/(Missing Juvenile )(-)/is';
			    $mjp = '/(Missing Juvenile Person)/is';
			    $mt2 = '/(Missing Tender Age)/is';
			    $ftd = '/(From the )(\d+)(\w+)( District)/is';
			    
			    if(preg_match($sfc,$title,$match)){
			        $pt1 = str_replace($match[0],"",$title);
			        if($istrue == "true"){
			            $newL = preg_replace($enl1,"",$pt1);
			            return  $newL;
			        }else if($istrue == "false"){
			            $newL = preg_replace($enl,"-",$pt1);
			            return  $newL;
			        }
			        
			    }else if(preg_match($ema,$title,$match5)){
			        $pt1 = str_replace($match5[0],"",$title);
			        return $pt1;
			    }else if(preg_match($emb,$title,$match6)){
			        $pt1 = str_replace($match6[0],"",$title);
			        return $pt1;
			    }else if(preg_match($emj,$title,$match7)){
			        $pt1 = str_replace($match7[0],"",$title);
			        return $pt1;
			    }else if(preg_match($emp,$title,$match8)){
			        $pt1 = str_replace($match8[0],"",$title);
			        return $pt1;
			    }else if(preg_match($fbi,$title,$match10)){
			        $pt1 = str_replace($match10[0],"",$title);
			        if($istrue == "true"){
			            $pt1 = preg_replace($enl1,"",$pt1);
			            return $pt1;
			        }else if($istrue == "false"){
			            $pt1 = preg_replace($enl,"-",$pt1);
			            return $pt1;
			        }
			        
			    }else if(preg_match($map,$title,$match11)){
			        $pt1 = str_replace($match11[0],"",$title);
			        if($istrue == "true"){
			            $ptz = preg_replace($ftd,"",$pt1);
			            $ptx = str_replace("-","",$ptz);
			            return $ptx;
			            
			        }else if($istrue == "false"){
			            
			            return $pt1;
			        }
			    }else if(preg_match($mee,$title,$match12)){
			        $pt1 = str_replace($match12[0],"",$title);
			        return $pt1;
			    }else if(preg_match($me1,$title,$match13)){
			        $pt1 = str_replace($match13[0],"",$title);
			        return $pt1;
			    }else if(preg_match($mej,$title,$match14)){
			        $pt1 = str_replace($match14[0],"",$title);
			        if($istrue == "true"){
			            $ptz = preg_replace($ftd,"",$pt1);
			            $ptx = str_replace("-","",$ptz);
			            return $ptx;
			            
			        }else if($istrue == "false"){
			            
			            return $pt1;
			        }
			    }else if(preg_match($me6,$title,$match15)){
			        $pt1 = str_replace($match15[0],"",$title);
			        return $pt1;
			    }else if(preg_match($mta,$title,$match16)){
			        $pt1 = str_replace($match16[0],"",$title);
			        return $pt1;
			    }else if(preg_match($me91,$title,$match91)){
			        $pt1 = str_replace($match91[0],"",$title);
			        if($istrue == "true"){
			            $ptz = preg_replace($ftd,"",$pt1);
			            $ptx = str_replace("-","",$ptz);
			            return $ptx;
			            
			        }else if($istrue == "false"){
			            
			            return $pt1;
			        }
			    }else if(preg_match($me9,$title,$match18)){
			        $pt1 = str_replace($match18[0],"",$title);
			        return $pt1;
			    }else if(preg_match($mt1,$title,$match17)){
			        $pt1 = str_replace($match17[0],"",$title);
			        return $pt1;
			    }else if(preg_match($mit,$title,$match4)){
			        $pt1 = str_replace($match4[0],"",$title);
			        return $pt1;
			    }else if(preg_match($mt2,$title,$match22)){
			        $pt1 = str_replace($match22[0],"",$title);
			        return $pt1;
			    }else if(preg_match($miR,$title,$match88)){
			        $pt1 = str_replace($match88[0],"",$title);
			        if($istrue == "true"){
			            $ptz = preg_replace($ftd,"",$pt1);
			            $ptx = str_replace("-","",$ptz);
			            return $ptx;
			            
			        }else if($istrue == "false"){
			           
			            return $pt1;
			        }
			        
			    }else if(preg_match($mip,$title,$match1)){
			        $pt1 = str_replace($match1[0],"",$title);
			        if($istrue == "true"){
			            $ptz = preg_replace($ftd,"",$pt1);
			            $ptx = str_replace("-","",$ptz);
			            return $ptx;
			            
			        }else if($istrue == "false"){
			          
			            return $pt1;
			        }
			        
			        
			    }else if(preg_match($mi2,$title,$match23)){
			        $pt1 = str_replace($match23[0],"",$title);
			        if($istrue == "true"){
			            $ptz = preg_replace($ftd,"",$pt1);
			            $ptx = str_replace("-","",$ptz);
			            return $ptx;
			            
			        }else if($istrue == "false"){
			            
			            return $pt1;
			        }
			    }else{
			        return $title;
			    }
			    
			    
			}
			
 			function trimCat($cat,$title){
 			    

 			    $crm = '/(Crime Alerts)/is';
 			    $dep = '/(Decomposed)/is';
 			    $hit = '/(Hit and Run)/is';
 			    $ina = '/(indecent assault)/is';
 			    $lsp = '/(lost Property)/is';
 			    $lur = '/(Lure)/is';
 			    $mep = '/(Missing Endangerd Person)/is';
 			    $mej = '/(Missing Endangered Juvenile)/is';
 			    $mep = '/(Missing Endangered Person)/is';
 			    $mex = '/(Missing Endangered Tender-age)/is';
 			    $mij = '/(Missing Juvenile)/is';
 			    $mix = '/(Missing Juvenile \-)/is';
 			    $mip = '/(Missing Person)/is';
 			    $mia = '/(Missing Person \-)/is';
 			    $mif = '/(Missing Persons)/is';
 			    $mta = '/(Missing Tender age)/is';
 			    $new = '/(News)/is';
 			    $rob = '/(Robbery)/is';
 			    $tra = '/(Traffic Alerts)/is';
 			    $van = '/(Vandalism)/is';
 			    $wan = '/(Wanted|Wnated)/is';
 			    
 			    
 			    if(preg_match($crm,$cat)){
 			        return 'Crime Alerts';
 			    }
 			    else if(preg_match($dep,$cat)){
 			        return 'Decomposed';
 			    }
 			    else if(preg_match($hit,$cat)){
 			        return 'Hit and Run';
 			    }
 			    else if(preg_match($ina,$cat)){
 			        return 'Indecent Assault';
 			    }
 			    if(preg_match($lsp,$cat)){
 			        return 'Lost Property';
 			    }
 			    else if(preg_match($lur,$cat)){
 			        return 'Lure';
 			    }
 			    else if(preg_match($mep,$cat)){
 			        return 'Missing Endangerd Person';
 			    }
 			    else if(preg_match($mej,$cat)){
 			        return 'Missing Endangered Juvenile';
 			    }
 			    else if(preg_match($mep,$cat)){
 			        return 'Missing Endangered Person';
 			        
 			    }else if(preg_match($mex,$cat)){
 			        return 'Missing Endangered Tender-Age';
 			    }
 			    else if(preg_match($mij,$cat)){
 			        return 'Missing Juvenile';
 			    }
 			    else if(preg_match($mix,$cat)){
 			        return 'Missing Juvenile';
 			    }
 			    else if(preg_match($mip,$cat)){
 			        return 'Missing Person';
 			    }
 			    else if(preg_match($mia,$cat)){
 			        return 'Missing Person';
 			    }
 			    else if(preg_match($mif,$cat)){
 			        return 'Missing Persons';
 			    }
 			    else if(preg_match($mta,$cat)){
 			        return 'Missing Tender-Age';
 			    }
 			    else if(preg_match($new,$cat)){
 			        return 'News';
 			    }
 			    else if(preg_match($rob,$cat)){
 			        return 'Robbery';
 			    }
 			    else if(preg_match($tra,$cat)){
 			        return 'Traffic Alerts';
 			    }
 			    else if(preg_match($van,$cat)){
 			        return 'Vandalism';
 			    }
 			    else if(preg_match($wan,$cat)){
 			        return 'Wanted';
 			        
 			    }else{
 			        
 			        
 			        $emb = '/(Endangered Missing Baby)/is';
 			        $emp = '/(Endangered Missing Person )(-)/is';
 			        $fbi = '/(FBI\/PPD Violent Crimes Task Force Search for)/is';
 			        $mej = '/(Missing Endangered Juvenile)/is';
 			        $me9 = '/(Missing Endangered Person )/is';
 			        $mi2 = '/(Missing Juvenile )(-)/is';
 			        $mi9 = '/(Missing Juvenile Person )(-)/is';
 			        $mip = '/(Missing Person)/is';
 			        $sfc = '/(Suspect\(s\)|Suspects|Suspect)( for)/is';
 			        $miss = '/(Missing)|(Juvenile)/is';
 			        
 			        if(preg_match($emb,$title)){
 			            return 'Endangered Missing Baby';
 			        }else if(preg_match($emp,$title)){
 			            return 'Endangered Missing Person';
 			        }else if(preg_match($fbi,$title)){
 			            return 'FBI\/PPD Violent Crimes Task Force';
 			        }else if(preg_match($mej,$title)){
 			            return 'Missing Endangered Juvenile';
 			        }else if(preg_match($mi9,$title)){
 			            return 'Missing Juvenile Person';
 			        }else if(preg_match($me9,$title)){
 			            return 'Missing Endangered Person';
 			        }else if(preg_match($mi2,$title)){
 			            return 'Missing Juvenile';
 			        }else if(preg_match($mip,$title)){
 			            return 'Missing Person';
 			        }else if(preg_match($miss,$title)){
 			            return 'Missing Person';
 			        }else{
 			            
 			            if($cat == "Crime Alerts" || $cat == "crime alerts"){
 			                return 'Crime Alerts';
 			            }else if($cat == "Surveillance" || $cat == "surveillance"){
 			                return 'Surveillance';
 			            }else if($cat == "Burglary" || $cat == "burglary"){
 			                return 'Burglary';
 			            }else if($cat == "Decomposed" || $cat == "decomposed"){
 			                return 'Decomposed';
 			            }else if($cat == "Hit and Run" || $cat == "hit and run"){
 			                return 'Hit and Run';
 			            }else if($cat == "Indecent Assault" || $cat == "indecent assault"){
 			                return 'Indecent Assault';
 			            }
 			            
 			            else{
 			                echo $cat;
 			            }
 			        }
 			        
 			        
 			    }
			    
 			}
			
			
			function trimAdd($add){
			    
			    $nam = $add;
			    
			    $patt = "/(,)( )(Philadelphia)/is";
			    $patt1 = "/(,)( )(Phila)/is";
			    $patt2 = "/(,)( )(phila)/is";
			    $patt3 = "/(,)(Philadelphia)/is";
			    $patt4 = "/(Philadelphia,)( )(PA)/is";
			    $patt5 = "/(.)( )(Phila.)( )(Pa)/is";
			    
			    
			    if(preg_match($patt, $add)){
			        $hal = explode(", Philadelphia",$nam);
			        $nam = $hal[0];
			    }
			    
			    if(preg_match($patt1, $add)){
			        $hal = explode(", Phila",$nam);
			        $nam = $hal[0];
			    }
			    
			    if(preg_match($patt2, $add)){
			        $hal = explode(", phila",$nam);
			        $nam = $hal[0];
			    }
			    
			    if(preg_match($patt3, $add)){
			        $hal = explode(",Philadelphia",$nam);
			        $nam = $hal[0];
			    }
			    
			    if(preg_match($patt4, $add)){
			        $hal = explode("Philadelphia,",$nam);
			        $nam = $hal[0];
			    }
			    if(preg_match($patt5, $add)){
			        $hal = explode(". Phila.",$nam);
			        $nam = $hal[0];
			    }
			    
			    
			    return $nam;
			}
			
			function fixCapName($add){
			    
			    $nam = $add;
			    
			    $patt = "/(https)/is";
			    
			    if(!preg_match($patt, $add)){
			        $nam = "https://www.phillypolice.com".$add;
			        
			    }
			    
			    return $nam;
			}
			
			
			function convertDis($zig){
			    
			    $dNum = $zig;
			    
			    if($dNum == "1"){
			        $dNum = "1st";
			    }else if($dNum == "2"){
			        $dNum = "2nd";
			    }else if($dNum == "3"){
			        $dNum = "3rd";
			    }else if($dNum == "22"){
			        $dNum = "22nd";
			    }else{
			        $dNum .= "th";
			    }
			    
			    return $dNum;
			}
    				
    				
			function cleanUpHTML($ret){ // USED TO TRIM (HTML) THE DESCRIPTION OF THE NEW STORY
			    
			    $fire = $ret;
			    
			    if(strpos($fire,"&#8217;") >=1){
			        $fire = str_replace("&#8217;","'",$fire);
			    }
			    
			    if(strpos($fire,"&#8243;") >=1){
			        $fire = str_replace("&#8243;","'",$fire);
			    }
			    if(strpos($fire,"&#8220;") >=1){
			        $fire = str_replace("&#8220;","'",$fire);
			    }
			    
			    if(strpos($fire,"&#8221;") >=1){
			        $fire = str_replace("&#8221;","'",$fire);
			    }
			    if(strpos($fire,"&#8242;") >=1){
			        $fire = str_replace("&#8242;","'",$fire);
			    }
			    if(strpos($fire,"&#039;") >=1){
			        $fire = str_replace("&#039;","â€²",$fire);
			    }
			    if(strpos($fire,"&amp;") >=1){
			        $fire = str_replace("&amp;","&",$fire);
			    }
			    if(strpos($fire,"&#215;") >=1){
			        $fire = str_replace("&#215;","X",$fire);
			    }
			    if(strpos($fire,"&#8211;") >=1){
			    $fire = str_replace("&#8211;","â€“",$fire);
			    }
			    
			    return $fire;
			}
			
			
			function trimVideoTitle($url){
			    
			    $patt = '/(Theft From Auto)/is';
			    $patt1 = '/(Commercial Robbery)/is';
			    $patt2 = '/(Shooting Incident)/is';
			    $patt3 = '/(Burglary)/is';
			    $patt4 = '/(Home Invasion Robbery)/is';
			    $patt5 = '/(Multiple Commercial Robberies)/is';
			    $patt6 = '/(Robbery)/is';
			    $patt7 = '/(Commercial Burglary)/is';
			    $patt8 = '/(Multiple Robberies)/is';
			    $patt9 = '/(Assault)/is';
			    $patt10 = '/(Vandalism)/is';
			    $patt11 = '/(Theft)/is';
			    $patt12 = '/(Arson)/is';
			    $patt13 = '/(Homicide)/is';
			    $patt14 = '/(Home Invasion)/is';
			    $patt14 = '/(Fraud)/is';
			    $ddc = '/(DC\s\d{2}\s\d{2}\s\d{6})/is';
			    $last = '/(Attempted|Commercial |Victim |Robbery |Comercial |Shooting |Aggravated )/is';
			    
			    if(preg_match($patt,$url,$mat)){
			        $txt = preg_replace($patt,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt1,$url,$mat1)){
			        $txt = preg_replace($patt1,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt2,$url,$mat2)){
			        $txt = preg_replace($patt2,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt3,$url,$mat3)){
			        $txt = preg_replace($patt3,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt4,$url,$mat4)){
			        $txt = preg_replace($patt4,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt5,$url,$mat5)){
			        $txt = preg_replace($patt5,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt6,$url,$mat6)){
			        $txt = preg_replace($patt6,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt7,$url,$mat7)){
			        $txt = preg_replace($patt7,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt8,$url,$mat8)){
			        $txt = preg_replace($patt8,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt9,$url,$mat9)){
			        $txt = preg_replace($patt9,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt10,$url,$mat10)){
			        $txt = preg_replace($patt10,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt11,$url,$mat11)){
			        $txt = preg_replace($patt11,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt12,$url,$mat12)){
			        $txt = preg_replace($patt12,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt13,$url,$mat13)){
			        $txt = preg_replace($patt13,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt14,$url,$mat14)){
			        $txt = preg_replace($patt14,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else if(preg_match($patt14,$url,$mat14)){
			        $txt = preg_replace($patt14,"",$url);
			        if(preg_match($ddc,$txt,$opp)){
			            $txt2 = preg_replace($ddc,"",$txt);
			            if(preg_match($last,$txt2,$ruff)){
			                return preg_replace($last,"",$txt2);
			            }else{
			                return $txt2;
			            }
			        }else{
			            return $txt;
			        }
			    }else{
			        return $url;
			    }
			    
			    
			}
	
			
			function convertVideoTitle($url){
			    
			    $patt = '/(Theft From Auto)/is';
			    $patt1 = '/(Commercial Robbery)/is';
			    $patt2 = '/(Shooting Incident)/is';
			    $patt3 = '/(Burglary)/is';
			    $patt4 = '/(Home Invasion Robbery)/is';
			    $patt5 = '/(Multiple Commercial Robberies)/is';
			    $patt6 = '/(Robbery)/is';
			    $patt7 = '/(Commercial Burglary)/is';
			    $patt8 = '/(Multiple Robberies)/is';
			    $patt9 = '/(Assault)/is';
			    $patt10 = '/(Vandalism)/is';
			    $patt11 = '/(Theft)/is';
			    $patt12 = '/(Arson)/is';
			    $patt13 = '/(Homicide)/is';
			    $patt14 = '/(Home Invasion)/is';
			    $patt14 = '/(Fraud)/is';
			    
			    if(preg_match($patt,$url,$mat)){
			        return 'Theft From Auto';
			    }else if(preg_match($patt1,$url,$mat1)){
			        return 'Commercial Robbery';
			    }else if(preg_match($patt2,$url,$mat2)){
			        return 'Shooting Incident';
			    }else if(preg_match($patt3,$url,$mat3)){
			        return 'Burglary';
			    }else if(preg_match($patt4,$url,$mat4)){
			        return 'Home Invasion Robbery';
			    }else if(preg_match($patt5,$url,$mat5)){
			        return 'Multiple Commercial Robberies';
			    }else if(preg_match($patt6,$url,$mat6)){
			        return 'Robbery';
			    }else if(preg_match($patt7,$url,$mat7)){
			        return 'Commercial Burglary';
			    }else if(preg_match($patt8,$url,$mat8)){
			        return 'Multiple Robberies';
			    }else if(preg_match($patt9,$url,$mat9)){
			        return 'Assault';
			    }else if(preg_match($patt10,$url,$mat10)){
			        return 'Vandalism';
			    }else if(preg_match($patt11,$url,$mat11)){
			        return 'Theft';
			    }else if(preg_match($patt12,$url,$mat12)){
			        return 'Arson';
			    }else if(preg_match($patt13,$url,$mat13)){
			        return 'Homicide';
			    }else if(preg_match($patt14,$url,$mat14)){
			        return 'Home Invasion';
			    }else if(preg_match($patt14,$url,$mat14)){
			        return 'Fraud';
			    }else{
			        return $url;
			    }
			    
			    
			}
			///////END of FUNCTIONS/////////////////////
			////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////
			

		
//////////////////////////////////////////////// START CLIENT KEY EXCHANGE//////////////////////////////////////////////////////////////		
		
		///  CLIENT UPDATE CHECKER /////
    	if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['Update'] == "true" || $_SERVER['REQUEST_METHOD'] == 'POST' && $data['Update'] == "true"){
				
			// CHECK THE HASH FOR A MD5 HASH  
    		if(preg_match(R_MD5_MATCH, $data['DeviceID']) || preg_match(R_MD5_MATCH, $_GET['DeviceID'])){  //CHECK FOR VALID HASH
				
    		    $devID = mysqli_real_escape_string($data['DeviceID']); // ESCAPE MALFORM STRING
				
    				if(!empty($data['DeviceID'])){
    				    $devID = $data['DeviceID'];
    				}else if(!empty($_GET['DeviceID'])){
    				    $devID = $_GET['DeviceID'];
    				}
    				
				$isDev = "SELECT `DeviceID` FROM `Devices` WHERE `DeviceID` = '$devID'";
				$is_res = mysqli_query($CONN, $isDev) or die($MYSQL_ERROR. header('HTTP/1.1 403 Forbidden'));
				
					// IF THE DEVICE EXIST ALREDY IN THE DATABASE; UPDATE THE TIMESTAMP AND IP
					if(mysqli_num_rows($is_res) >=1){
							
						$up_res = "UPDATE `Devices` SET `TimeStamp` = NOW(), `LastRequestIP` = '$IP' WHERE `DeviceID` = '$devID'";
						$in_rec = mysqli_query($CONN, $up_res);
							
							if($in_rec){
								/// FETCH THE NEW HASHES
								$array = array();
								$get_nHash = "SELECT `HashName`, `Hash` FROM `CurrentHash`";
								$h_res = mysqli_query($CONN, $get_nHash);
									
									if(mysqli_num_rows($h_res) >=1){
											
										while($row = mysqli_fetch_array($h_res)){
											
											$hashName = $row['HashName'];
											$HASH = $row['Hash'];
											$obj = array("HashName"=>$hashName,"Hash"=>$HASH);
											
											array_push($array, $obj);
										}
										
											echo json_encode(array("HashKeys"=>$array,"error"=>"false"));
										
									}else{
										// FAILED TO GET HASH KEYS
									    echo $ZERO_HASH_KEYS;
									}
		
								
							}else{
								// UPDATE DEVICE EXHNACGE FAILED
							    echo $MYSQL_ERROR;
							}
					
					}else{
						// COULD NOT FIND THE DEVICE ID OF DEVICE
					    echo $INVALID_DEVICE;
					}
		

			}else{
				// NOT A VAILD HASHTAG SENT TO SERVER 
			    echo $INVALID_DEVICE_ID;
			}
		
		
		
		}
		
////////////////////////////////////////////////////////// END CLIENT KEY EXCHANGE //////////////////////////////////////////////////////////////			
		
		
		
		
		
////////////////////////////////////////////////////////// START LATEST NEWS UPDATE //////////////////////////////////////////////////////////////
		
		
	

		if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['LatestNews'] == 'true' || $_SERVER['REQUEST_METHOD'] == 'POST' && $data['LatestNews'] == 'true'){
		   
		    if(preg_match(NUM_ONLY, $data['Start']) && preg_match(NUM_ONLY, $data['End'])|| preg_match(NUM_ONLY, $_GET['Start']) && preg_match(NUM_ONLY, $_GET['End'])){  //CHECK FOR NEWSOBJECT NUMBER

		    
		    if(!empty($_GET['Start']) && !empty($_GET['End'])){
		        $srt = $_GET['Start'];
		        $end = $_GET['End'];
		    }else if(!empty($data['Start']) && !empty($data['End'])){
		        $srt = $data['Start'];
		        $end = $data['End'];
		    }else{
		        $srt = 0;
		        $end = 5;
		    }
		    
    		    $NEW_HASH = 0;
    		    
    		    
    		    $get_sql = "SELECT `TimeStamp`,`Hash` FROM `CurrentHash` WHERE `HashName` = 'NewsStory'";
    		    
    		    $res = mysqli_query($CONN, $get_sql);
    		    
    		    if(mysqli_num_rows($res) >=1){
    		        
    		        $row = mysqli_fetch_array($res);
    		        $NEW_HASH = $row['Hash'];
    		        $tamp = $row['TimeStamp'];
    		        $pDate = date('Y-m-d',strtotime($tamp));
    		        $array2 = array();
    		        $array = array();
    		        //$query = "SELECT SQL_CALC_FOUND_ROWS * FROM `NewsStory` WHERE `ScrapeHash` = '$NEW_HASH' ORDER BY `PubDate` DESC LIMIT $srt,$end";
    		        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM `NewsStory` WHERE `PubDate` LIKE '%$pDate%' ORDER BY `PubDate` DESC LIMIT $srt,$end";
    		        $cquery = "SELECT FOUND_ROWS() AS ROWS";
    		        $result = mysqli_query($CONN, $query);
    		        $cresult = mysqli_query($CONN, $cquery);
    		        $cat = mysqli_fetch_array($cresult);
    		        
    		        while($row = mysqli_fetch_array($result)){
    		            
    		            $newzObj = new NewsObject();
    		            $newzObj->setCategory(trimCat($row['Category'],$row['Title']));
    		            $newzObj->setPubDate(date('M j, g:i A',strtotime($row['PubDate'])));
    		            $newzObj->setStoryAuthor($row['StoryAuthor']);
    		            $newzObj->setNewsStoryID($row['ID']);
    		            $newzObj->setDescription(cleanUpHTML(utf8_encode($row['Description'])));
    		            $newzObj->setTitle(trimTitle($row['Title'],"false"));

    		            $imgURLL = $row['ImageURL'];
    		            $SQLL = "SELECT `LocalImageURL` FROM `Images` WHERE `RemoteImageURL` = '$imgURLL'";
    		            $REZZ = mysqli_query($CONN, $SQLL);

    		            if(mysqli_num_rows($REZZ) >=1){
    		                $ROWW = mysqli_fetch_array($REZZ);
    		                $pre = $ROWW['LocalImageURL'];
    		                $newzObj->setImageURL($IMG_DIR.$pre);
    		            }else{
   		                $newzObj->setImageURL($row['ImageURL']);
   		                }

    		            if(empty($row['TubeURL'])){
    		                $newzObj->setTubeURL("No Video");
    		            }else{
    		               $newzObj->setTubeURL($row['TubeURL']);
   		                }
                    		            
   		               array_push($array2,$newzObj);   
                        
    		        }
    		        
    		        echo json_encode(array("News"=>$array2,"TotalCount"=>$cat['ROWS']));
    		      
    		        
    		    }else{
    		        echo $ZERO_HASH_KEYS;
    		    }
		    
		}
		    
		    }
		    
		    
////////////////////////////////////////////////////////// END LATEST NEWS UPDATE //////////////////////////////////////////////////////////////
		    
		    
		    
		    
		    
////////////////////////////////////////////////////////// START DISTRICT INFO //////////////////////////////////////////////////////////////
		    
		    
		    
		

		    else if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['DistrictInfo'] == "true" || $_SERVER['REQUEST_METHOD'] == 'POST' && $data['DistrictInfo'] == "true"){
		        
		        if(!empty($_GET['DistrictNumber'])){
		            $dist_num = $_GET['DistrictNumber'];
		        }else if(!empty($data['DistrictNumber'])){
		            $dist_num = $data['DistrictNumber'];
		        }
		        
		        
		        $array = array();
		        $array1 = array();
		        $array2 = array();
		        $query = "SELECT * FROM `DistrictInfo` WHERE `DistrictNumber` = '$dist_num' ORDER BY `TimeStamp` DESC LIMIT 0,1";
		        $query1 = "SELECT * FROM `PSA` WHERE `DistrictNumber` = '$dist_num' AND `isCurrent` = 1 ORDER BY `PSAAreaNum` ASC";
		        //$query2 = "SELECT * FROM `DistrictCalendar` WHERE `DistrictNumber` = '$dist_num' AND `DistrictDate` > DATE_FORMAT(NOW(), '%b %e, %Y %h :%i %p') LIMIT 0,3";
		        $query2 = "SELECT * FROM `Calendar` WHERE `DistrictNumber` = '$dist_num' ORDER BY `ID` DESC LIMIT 0,7";
		        
		        $result = mysqli_query($CONN, $query)or die('Bad Query '.mysql_error());
		        if(mysqli_num_rows($result) >0){
		            $row = mysqli_fetch_array($result);        
		            
		            $disObj = new DistrictInfoObject();
		            $disObj->setDistrictNumber($row['DistrictNumber']);
		            $disObj->setLocationAddress($row['LocationAddress']);
		            $disObj->setPhoneNumber($row['Phone']);
		            $disObj->setEmailAddress($row['EmailAddress']);
		            $disObj->setCaptainName($row['CaptainName']);
		            $disObj->setCaptainImageURL(fixCapName($row['CaptainURL']));
		            $disObj->setDetectiveDivision($row['DetectiveDivision']);

		            
		        }
		        
		        
		        $result1 = mysqli_query($CONN, $query1) or die('Bad query 1');
		        while($row1 = mysqli_fetch_array($result1)){

		            $psaObj = new PSAObject();
		            $psaObj->setPSAID($row1['ID']);
		            $psaObj->setDistrictNumber($row1['DistrictNumber']);
		            $psaObj->setEmailAddress($row1['Email']);
		            $psaObj->setPSAAreaNumber($row1['PSAAreaNum']);
		            $psaObj->setLieutenantName(trimLieName($row1['LieutenantName']));
		            array_push($array1,$psaObj);

		            
		        }
		        
		        
		        
		        $result2 = mysqli_query($CONN, $query2)or die('Bad query2');
		        while($row2 = mysqli_fetch_array($result2)){

		            $calObj = new CalendarObject();
		            $calObj->setTimeStamp($row2['TimeStamp']);
		            $calObj->setDistrictNumber($row2['DistrictNumber']);
		            $calObj->setTitle($row2['Title']);
		            $calObj->setMeetDate($row2['MeetDate']);
		            $calObj->setMeetLocation(trimAdd($row2['MeetLocation']));
		            array_push($array2, $calObj);
		            
		            
		        }
		        
		        echo json_encode(array("DistrictInfo"=>$disObj,"PSAInfo"=>$array1,"CalenderInfo"=>$array2));
		        
}




////////////////////////////////////////////////////////// END DISTRICT INFO //////////////////////////////////////////////////////////////





////////////////////////////////////////////////////////// START DISTRICT NEWS //////////////////////////////////////////////////////////////



else if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['DistrictNews'] == "true" || $_SERVER['REQUEST_METHOD'] == 'POST' && $data['DistrictNews'] == "true"){
              
    if(preg_match(NUM_ONLY, $data['DistrictNumber']) || preg_match(NUM_ONLY, $_GET['DistrictNumber'])){
        
        if(!empty($_GET['DistrictNumber'])){
            $district = $_GET['DistrictNumber'];
            if(!empty($_GET['End']) && !empty($_GET['Start'])){
                $srt = $_GET['Start'];
                $end = $_GET['End'];
            }else{
                $srt = 0;
                $end = 5;
            }
        }else if(!empty($data['DistrictNumber'])){
            $district = $data['DistrictNumber'];
            if(!empty($data['End']) && !empty($data['Start'])){
                $srt = $data['Start'];
                $end = $data['End'];
            }else{
                $srt = 0;
                $end = 5;
            }
        }
        
        
        //$district = $_GET['d'];
        $array = array();
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM `NewsStory` WHERE `DistrictNumber` = $district ORDER BY `TimeStamp` DESC LIMIT $srt,$end";
        $cquery = "SELECT FOUND_ROWS() AS ROWS";
        
        $result = mysqli_query($CONN, $query) or die('Bad Query '.mysql_error());
        $cresult = mysqli_query($CONN, $cquery);
        
        $ct = mysqli_fetch_array($cresult);
        while($row = mysqli_fetch_array($result)){ 
            
//             $captionURL = $row['ImageURL'];
//             $sel = "SELECT `LocalImageURL` FROM `Images` WHERE `RemoteImageURL` = '$captionURL'";
//             $r_Q = mysqli_query($CONN, $sel);
            
//             if(mysqli_num_rows($r_Q) >=1){
//                 $rowz = mysqli_fetch_array($r_Q);
//                 $pre = $rowz['LocalImageURL'];
//                 $captionURL = $IMG_DIR.$pre;
//                 $newzObj->setImageURL($IMG_DIR.$pre);
                
//             }
            
            
            
            $newzObj = new NewsObject();
            $newzObj->setImageURL($row['ImageURL']);
            $newzObj->setNewsStoryID($row['ID']);
            $newzObj->setDistrictNumber($row['DistrictNumber']);
            $excert = cleanUpHTML(utf8_encode($row['Description']));
            $newzObj->setDescription($excert);
            $date = date('M j, g:i A',strtotime($row['PubDate']));
            $newzObj->setPubDate($date);
            $storyURL = trim($row['URL']);
            $newzObj->setURL($storyURL);
            $newzObj->setTitle(trimTitle($row['Title'],"true"));
            $videoURL = $row['TubeURL'];
            if(empty($videoURL)){
                $videoURL = "No Video";
            }
            $newzObj->setTubeURL($videoURL);
            $cat = $row['Category'];
            
            if($cat == "missing person"){
                $cat == "Missing Person";
            }
            $newzObj->setCategory(trimCat($cat,$row['Title']));
                $newzObj->setStoryAuthor($row['StoryAuthor']);
               
            array_push($array, $newzObj);
            
       
            
        }
        
        echo json_encode(array("Articles"=>$array,"TotalCount"=>$ct['ROWS']));
        //writeToLog(json_encode(array("Articles"=>$array,"TotalCount"=>$ct['ROWS'])));
   
    }else{
        
        echo $INVALID_DIS;
        
    }

}

		
        
		
////////////////////////////////////////////////////////// END DISTRICT NEWS //////////////////////////////////////////////////////////////
        
        
		


//////////////////////////////////////////////////////////////////////////////////// UCVIDEOS API //////////////////////////////////////////////////////////////////////////////////////////////////////////////



        else if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['UCVideos'] == "true" || $_SERVER['REQUEST_METHOD'] == 'POST' && $data['UCVideos'] == "true"){
            
            
            if(!empty($_GET['UCVideos'])){
                $district = $_GET['District'];
                if(!empty($_GET['End']) && !empty($_GET['Start'])){
                    $start = $_GET['Start'];
                    $end = $_GET['End'];
                }else{
                    $start = 0;
                    $end = 5;
                }
            }else if(!empty($data['District'])){
                $district = $data['District'];
                if(!empty($data['End']) && !empty($data['Start'])){
                    $start = $data['Start'];
                    $end = $data['End'];
                }else{
                    $start = 0;
                    $end = 5;
                }
            }
            
            
            
            $array = array();
            //$query = "SELECT SQL_CALC_FOUND_ROWS * FROM `UCVideos` WHERE `PoliceDivision` = '$div' AND `VideoID` != '0' AND `VideoDate` > DATE_FORMAT(NOW(), '%b %e, %Y') ORDER BY STR_TO_DATE( `VideoDate` , '%b %e, %Y' ) DESC LIMIT $start,$end";
            if(!empty($district)){
                $query = "SELECT SQL_CALC_FOUND_ROWS * FROM `UCVideos` WHERE `VideoID` != '0' AND `DistrictNumber` = '$district'  ORDER BY  `TimeStamp` DESC LIMIT $start,$end";
                
            }else{
                $query = "SELECT SQL_CALC_FOUND_ROWS * FROM `UCVideos` WHERE `VideoID` != '0' AND `DistrictNumber` != 0  ORDER BY  `TimeStamp` DESC LIMIT $start,$end";
                
            }
            
            
            $cquery = "SELECT FOUND_ROWS() AS ROWS";
            $result = mysqli_query($CONN, $query) or die('Bad Query '.mysql_error());
            $cresult = mysqli_query($CONN, $cquery);
            $cat = mysqli_fetch_array($cresult);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)){

                    $ucVid = new UCVideoObject();
                    $ucVid->setUCVideoID($row['ID']);
                    $ucVid->setNewsStoryID($row['NewsID']);
                    $ucVid->setTimeStamp($row['TimeStamp']);
                    $title = $row['VideoTitle'];
                    if(preg_match('/(DC )(\d{2}( )\d{2}( )\d{6})/is',$title,$top)){
                        $dcNUM = $top[0];
                        $ucVid->setDCNumber($dcNUM);
                    }else{
                        $dcNUM = "0";
                        $ucVid->setDCNumber($dcNUM);
                    }
                    
                    if(strpos($title," DC ") >=1){
                        $txt = explode(" DC",$title);
                        $title = $txt[0];
                    }
                    $ucVid->setVideoTitle(html_entity_decode(trimVideoTitle($title)));
                    $vid_ID = $row['VideoID'];
                    $chg = "SELECT `Description` FROM `NewsStory` WHERE `TubeURL` LIKE '%$vid_ID%' LIMIT 0,1";
                    $r_chg = mysqli_query($CONN, $chg);
                    if(mysqli_num_rows($r_chg) <= 0){
                        $desc = cleanUpHTML(utf8_encode($row['Description']));
                    }else{
                        $azzy = mysqli_fetch_array($r_chg);
                        $desc = cleanUpHTML(utf8_encode($azzy['Description']));
                    }
                    $ucVid->setDescription($desc);
                    $ucVid->setVideoID($row['VideoID']);
                    $ucVid->setVideoImageURL($row['VideoImageURL']);
                    $ucVid->setVideoDate($row['VideoDate']);
                    $divv = convertdiv($row['DistrictNumber']);
                    $ucVid->setDistrictDivision($divv);
                    $ucVid->setDistrictNumber($row['DistrictNumber']);
                    $ucVid->setCrimeType(convertVideoTitle($row['VideoTitle']));
                    $videoURL = "https://www.youtube.com/embed/".$vid_ID;
                    $ucVid->setTubeURL($videoURL);

                    array_push($array,$ucVid);
                    
                    
                }
                
                echo json_encode(array("Videos"=>$array,"TotalCount"=>$cat['ROWS'],"error"=>"false"));
            }
            
        }




//////////////////////////////////////////////////////////////////////////////// END of UCVIDEOS API ///////////////////////////////////////////////////////////////////




        
        
//////////////////////////////////////////////////////////////////////////////////// START SHOOTING API //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        
        
        else if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['Shooting'] == "true" || $_SERVER['REQUEST_METHOD'] == 'POST' && $data['Shooting'] == "true")
        {
            
            
            if(!empty($_GET['Latest'])){
                $isLate = $_GET['Latest'];
                if($isLate == "true"){
                    $isLate = "true";
                }else{
                    // NOT equal to true
                }
                
               
            }else if(!empty($data['Latest'])){
                $isLate = $data['Latest'];
                if($isLate == "true"){
                    $isLate = "true";
                }else{
                    // NOT equal to true
                }
                
            }
            
            if($isLate == "true"){
                
                $objArr = array();
                $sel = "SELECT `Hash` FROM `CurrentHash` WHERE `HashName` = 'Shootings'";
                $resHash = mysqli_query($CONN, $sel) or die('Bad Query '.mysql_error());
                $hast = mysqli_fetch_array($resHash);
                $hash = $hast['Hash'];
                
                $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `Shooting` WHERE `HashTag` = '$hash'";
                $sqlC = "SELECT FOUND_ROWS() AS ROWS";
                $resLat = mysqli_query($CONN, $sql) or die('Bad Query '.mysql_error());
                $resC = mysqli_query($CONN, $sqlC) or die('Bad Query '.mysql_error());
                $arrC = mysqli_fetch_array($resC);
                $ttCount = $arrC['ROWS'];
                
                if(mysqli_num_rows($resLat) >=1){
                    
                    while($row = mysqli_fetch_array($resLat)){
                        $shootObj = new ShootingObject();
                        $shootObj->setShootingID($row['ID']);
                        $shootObj->setTimeStamp($row['TimeStamp']);
                        $shootObj->setIsInside($row['isInside']);
                        $shootObj->setIsOutside($row['isOutside']);
                        $num = convertDis($row['DistrictNumber']);
                        $shootObj->setDistrictNumber($num);
                        $shootObj->setCrimeTime($row['CrimeTime']);
                        $dcNum = $row['DCNumber'];
                        $Fdate = substr($dcNum, 0, 4);
                        $Fdistrict = substr($dcNum, 4, 2);
                        $Frest = substr($dcNum, 6, 7);
                        $DCN = $Fdate."-".$Fdistrict."-".$Frest;
                        $shootObj->setDCNumber($DCN);
                        $newDate = date("D. M j, Y", strtotime($row['CrimeDate']));
                        $shootObj->setCrimeDate($newDate);
                        $race = $row['Race'];
                        if($race == "B"){
                            $race = "black";
                        }else if($race == "W"){
                            $race = "white";
                        }else if($race == "A"){
                            $race = "asian";
                        }
                        $shootObj->setRace($race);
                        if($row['Gender'] == "M"){
                            $shootObj->setGender("male");
                        }else if($row['Gender'] == "F"){
                            $shootObj->setGender("female");
                        }
                        
                        $shootObj->setAge($row['Age']);
                        $shootObj->setWound($row['Wound']);
                        $isOFF = $row['isOfficerInvolved'];
                        if($isOFF == "0" || $isOFF == 0){
                            $isOFF = "false";
                        }else if($isOFF == "1" || $isOFF == 1){
                            $isOFF = "true";
                        }
                        $shootObj->setisOfficerInvolved($isOFF);
                        $loca = str_replace(" BLOCK"," blk of",$row['LocationAddress']);
                        $shootObj->setLocationAddress($loca);
                        $shootObj->setLocationX($row['LocationX']);
                        $shootObj->setLocationY($row['LocationY']);
                        $isFat = $row['isFatal'];
                        if($isFat == "0" || $isFat == 0){
                            $isFat = "false";
                        }else if($isFat == "1" || $isFat == 1){
                            $isFat = "true";
                        }
                        $shootObj->setisFatal($isFat);
                        array_push($objArr,$shootObj);
                    }
                    
                    
                    echo json_encode(array("Shootings"=>$objArr,"TotalCount"=>$ttCount));
                    
                }else{
                    // no record returned
                }
                
                
                
            }
                
            
            
            
            
        }
        
        
        
        
//////////////////////////////////////////////////////////////////////////////// END of SHOOTING API ///////////////////////////////////////////////////////////////////
        
        
        
        
        
        
//////////////////////////////////////////////////////////////////////////////////// START UNSOLVED MURDERS DATA API //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        
        
        
        else if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['USMurders'] == "true" || $_SERVER['REQUEST_METHOD'] == 'POST' && $data['USMurders'] == "true")
        {
        
        
            if(!empty($_GET['DistrictNumber'])){
                
                if(preg_match(NUM_ONLY, $_GET['DistrictNumber'])){
                    $dNum = $_GET['DistrictNumber'];
                }else{
                    $dNum = 0;
                }
                
                
            }else if(!empty($data['DistrictNumber'])){
                
                if(preg_match(NUM_ONLY, $data['DistrictNumber'])){
                    $dNum = $data['DistrictNumber'];
                }else{
                    $dNum = 0;
                }
                
            }else{
                $dNum = 0;
            }
            
            if(!empty($_GET['Start']) && !empty($_GET['End'])){
                $srt = $_GET['Start'];
                $end = $_GET['End'];
            }else if(!empty($data['Start']) && !empty($data['End'])){
                $srt = $data['Start'];
                $end = $data['End'];
            }else{
                $srt = 0;
                $end = 5;
            }
            
            mysqli_set_charset($CONN, 'utf8mb4');
            $array = array();
            
            
            $sel = "SELECT SQL_CALC_FOUND_ROWS * FROM `UnsolvedMurders` WHERE `DCNumber` LIKE '%-$dNum-%' ORDER BY `MurderDate` DESC LIMIT $srt,$end";
            $sel_r = "SELECT FOUND_ROWS() AS ROWS";
            $res = mysqli_query($CONN, $sel);
            $res_r = mysqli_query($CONN, $sel_r);
            $nox = mysqli_fetch_array($res_r);
            
            if(mysqli_num_rows($res) >= 1){
                
                while($rows = mysqli_fetch_array($res)){
                    $dcn = $rows['DCNumber'];
                    $iDD = $rows['ID'];
                    $chl = "SELECT `UCMurderURL` FROM `USMImages` WHERE `DCNumber` ='$dcn'";
                    $isP = mysqli_query($CONN, $chl);
                    if(mysqli_num_rows($isP) >= 1){
                        $array1 = array();
                        while($rot = mysqli_fetch_array($isP)){
                            array_push($array1,$rot['UCMurderURL']);
                        }
                    }else{
                        $array1 = array();
                    }
                    
                    if(preg_match('/(DC# )/is',$dcn,$damatch)){
                        $ezy = str_replace($damatch[0],"",$dcn);
                    }else{
                        $ezy = $dcn;
                    }
                    
                    $cho = "SELECT * FROM `NewsStory` WHERE `Description` LIKE '%$ezy%'";
                    $zer = mysqli_query($CONN, $cho);
                    if(mysqli_num_rows($zer) >= 1){
                        $array2 = array();
                        while($rot = mysqli_fetch_array($zer)){
                            $newzObj = new NewsObject();
                            $newzObj->setImageURL($rot['ImageURL']);
                            $newzObj->setNewsStoryID($rot['ID']);
                            $newzObj->setDistrictNumber($rot['DistrictNumber']);
                            $excert = cleanUpHTML(utf8_encode($rot['Description']));
                            $excert = str_replace("\xc2\xa0","",$excert);
                            $newzObj->setDescription(trim($excert));
                            $date = date('M j, g:i A',strtotime($rot['PubDate']));
                            $newzObj->setPubDate($date);
                            $storyURL = trim($rot['URL']);
                            $newzObj->setURL($storyURL);
                            $newzObj->setTitle(trimTitle($rot['Title'],"true"));
                            $videoURL = $rot['TubeURL'];
                            if(empty($videoURL)){
                                $videoURL = "No Video";
                            }
                            $newzObj->setTubeURL($videoURL);
                            $cat = $rot['Category'];
                            
                            if($cat == "missing person"){
                                $cat == "Missing Person";
                            }
                            $newzObj->setCategory(trimCat($cat,$rot['Title']));
                            $newzObj->setStoryAuthor($rot['StoryAuthor']);
                            
                            array_push($array2, $newzObj);
                            
                        }
                    }else{
                        $array2 = array();
                    }
                    
                    $vcn = $rows['VictimName'];
                    if(preg_match('/(DC# \d{2}(-)\d{2}(-)\d{6})/is',$vcn,$tt)){
                        $vcn = str_replace($tt[0],"",$vcn);
                    }
                        
                    $nrl = $rows['NewsURL'];
                    $mda = date('l F jS, Y',strtotime($rows['MurderDate']));
                    $ops = str_replace('"',"",$rows['Description']);
                    $des = str_replace("\xc2\xa0","",$ops);
                    $srp = $rows['ScrapeHash'];
                    
                    $obj = array("USMurderID"=>$iDD,"DCNumber"=>$dcn,"VictimName"=>$vcn,"MurderDate"=>$mda,"NewsURL"=>$nrl,"Description"=>$des,"ScrapeHash"=>$srp,"Images"=>$array1,"NewsStory"=>$array2);
                    array_push($array,$obj);
                    
                }
               
            }else{
                // NO MURDERS
            }
        
        
            echo json_encode(array("Unsolved Murders"=>$array,"TotalCount"=>$nox['ROWS']));
        
        
        
        
        
        
        
        }
        
        
        
//////////////////////////////////////////////////////////////////////////////////// END  of UNSOLVED MURDERS DATA API //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        
        
        
        
        
        
//////////////////////////////////////////////////////////////////////////////////// START CRIMES DATA API //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        
        
        
        
        else if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['CrimeIncidents'] == "true" || $_SERVER['REQUEST_METHOD'] == 'POST' && $data['CrimeIncidents'] == "true")
        {
            
            if(!empty($_GET['Latest'])){
                $isLate = $_GET['Latest'];
                if($isLate == "true"){
                    $isLate = "true";
                }else{
                    // NOT equal to true
                    $isLate = "false";
                }
                
                
            }else if(!empty($data['Latest'])){
                $isLate = $data['Latest'];
                if($isLate == "true"){
                    $isLate = "true";
                }else{
                    // NOT equal to true
                    $isLate = "false";
                }
                
            }
 
                if(!empty($_GET['DistrictNumber'])){
                    
                    if(preg_match(NUM_ONLY, $_GET['DistrictNumber'])){
                        $dNum = $_GET['DistrictNumber']; 
                    }else{
                        $dNum = 0; 
                    }
                    
                    
                }else if(!empty($data['DistrictNumber'])){
                    
                    if(preg_match(NUM_ONLY, $data['DistrictNumber'])){
                        $dNum = $data['DistrictNumber'];
                    }else{
                        $dNum = 0;
                    }

                }
                
                if(!empty($_GET['Start']) && !empty($_GET['End'])){
                    $srt = $_GET['Start'];
                    $end = $_GET['End'];
                }else if(!empty($data['Start']) && !empty($data['End'])){
                    $srt = $data['Start'];
                    $end = $data['End'];
                }else{
                    $srt = 0;
                    $end = 10;
                }
                
                
                
 
            
            if($isLate == "true"){
            
            
                $objArray = array();
                $query = "SELECT `Hash` FROM `CurrentHash` WHERE `HashName` = 'CrimeIncidents'";
                $r_hash = mysqli_query($CONN, $query);
                
                if(mysqli_num_rows($r_hash) >=1){
                    $var = mysqli_fetch_array($r_hash);
                    $hash = $var['Hash'];
                    
                }else{
                    // NO HAS RETURNED
                }
                    
                    if($dNum != 0){
                        $sql = "SELECT SQL_CALC_FOUND_ROWS `ID`,`TimeStamp`,`DistrictNumber`,`PSAArea`,`DispatchTime`,`DispatchDate`,`AddressBlock`,`CrimeName`,`CrimeCode`,`LocationX`,`LocationY` FROM `CrimeIncidents` WHERE `HashTag` = '$hash' AND `DistrictNumber` = '$dNum' ORDER BY `CrimeName` LIMIT $srt,$end";
                        
                    }else{
                        $sql = "SELECT SQL_CALC_FOUND_ROWS `ID`,`TimeStamp`,`DistrictNumber`,`PSAArea`,`DispatchTime`,`DispatchDate`,`AddressBlock`,`CrimeName`,`CrimeCode`,`LocationX`,`LocationY` FROM `CrimeIncidents` WHERE `HashTag` = '$hash' ORDER BY `CrimeName` LIMIT $srt,$end";
                        
                    }
                    
            }else if($isLate == "false"){
                
                if($dNum != 0){
                    $sql = "SELECT SQL_CALC_FOUND_ROWS `ID`,`TimeStamp`,`DistrictNumber`,`PSAArea`,`DispatchTime`,`DispatchDate`,`AddressBlock`,`CrimeName`,`CrimeCode`,`LocationX`,`LocationY` FROM `CrimeIncidents` AND `DistrictNumber` = '$dNum' ORDER BY `CrimeName` LIMIT $srt,$end";
                    
                }else{
                    $sql = "SELECT SQL_CALC_FOUND_ROWS `ID`,`TimeStamp`,`DistrictNumber`,`PSAArea`,`DispatchTime`,`DispatchDate`,`AddressBlock`,`CrimeName`,`CrimeCode`,`LocationX`,`LocationY` FROM `CrimeIncidents` ORDER BY `CrimeName` LIMIT $srt,$end";
                    
                }
            }
                    
                    $sql_c = "SELECT FOUND_ROWS() AS ROWS";
                    $rez = mysqli_query($CONN,$sql);
                    $rez_c = mysqli_query($CONN,$sql_c);
                    $roww_c = mysqli_fetch_array($rez_c);
                    
                    while($roww = mysqli_fetch_array($rez)){
                        $thing = $roww['DispatchDate']." ".$roww['DispatchTime'];
                        $tt = date('D M j, g:i a',strtotime($thing));
                        $crimeObj = new CrimeObject();
                        $crimeObj->setCrimeID($roww['ID']);
                        $crimeObj->setTimeStamp($roww['TimeStamp']);
                        $crimeObj->setDistrictNumber($roww['DistrictNumber']);
                        $crimeObj->setPSAArea($roww['PSAArea']);
                        $crimeObj->setDispatchTime($roww['DispatchTime']);
                        $crimeObj->setDispatchDate($tt);
                        $crimeObj->setAddress($roww['AddressBlock']);
                        $crimeObj->setCrimeType($roww['CrimeName']);
                        $crimeObj->setCrimeCode($roww['CrimeCode']);
                        $crimeObj->setLocationX($roww['LocationX']);
                        $crimeObj->setLocationY($roww['LocationY']);
                        array_push($objArray,$crimeObj);

                        
                    }
                    
                    echo json_encode(array("CrimeIncidents"=>$objArray,"TotalCount"=>$roww_c['ROWS']));
                    
                
                
                
            
            
            
            
            
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
//////////////////////////////////////////////////////////////////////////////// END of CRIMES DATA API ///////////////////////////////////////////////////////////////////
        
        
        
        
		
		
///////////////////////////////////////////////////////// START CLIENT APIs ///////////////////////////////////////////////////////////////////		
		
		
		/////////START DEVICE REGISTRATION///////////////////////////////////////////////////////////
		
		else if($_SERVER['REQUEST_METHOD'] == 'POST' && $data['isAgreement'] == 'true'){
			$devID = $data['DeviceID'];
				
				if(preg_match(R_MD5_MATCH, $devID)){
					
					$q = "SELECT `DeviceID` FROM `Devices` WHERE `DeviceID` = '$devID'";
					$res1 = mysqli_query($CONN, $q);
						
						if(mysqli_num_rows($res1) >=1){
							// record already exist
							echo json_encode(array("error"=>"false","msg"=>"success"));
						}else{
							// No Record exist please input
							$in = "INSERT INTO `Devices` (`DeviceID`,`LastRequestIP`)VALUES('$devID','$IP')";
							$res = mysqli_query($CONN, $in) or die($MYSQL_ERROR. header('HTTP/1.1 403 Forbidden'));
						
								if($res){
									echo json_encode(array("error"=>"false","msg"=>"success"));
								}else{
									echo json_encode(array("error"=>"true","msg"=>mysql_error()));
								}
						}
						
					
				}else{
					echo json_encode(array("error"=>"true","msg"=>"INVAID DEVICE ID"));
				}
				
				
				
		  }
		  
		  /////////END DEVICE REGISTRATION///////////////////////////////////////////
			
			
			
			else if($_SERVER['REQUEST_METHOD'] == 'POST' && $data['District_Update'] == 'true'){
				//else if($_SERVER['REQUEST_METHOD'] == 'GET' && $data['District_Update'] == 'true'){
					
				$array = array();	
				$devID = $data['DeviceID'];
				$dis_arr = $data['Districts'];
				$is_Vid = $data['UC_Videos'];
				$hash_T = $data['HashTag'];
				$hash = 0;


					if(preg_match(R_MD5_MATCH, $devID)){
						
						$q = "SELECT `DeviceID` FROM `Devices` WHERE `DeviceID` = '$devID'";
						$res = mysqli_query($CONN, $q) or die($MYSQL_ERROR. header('HTTP/1.1 403 Forbidden'));
							
							if(mysqli_num_rows($res) >=1 ){
								
								if(!is_null($hash_T) && !is_null($dis_arr)){
									
									$ob_ay = array();
									$oj_ary1 = array();
									$tall = join("', '", $dis_arr);
									$gq = "SELECT SQL_CALC_FOUND_ROWS * FROM `NewsStory` WHERE `ScrapeHash` = '$hash_T' AND `DistrictNumber` IN ('$tall')";
									$gqq = "SELECT SQL_CALC_FOUND_ROWS * FROM `UCVideos` WHERE `HashTag` = '$hash_T'";
									$gq1 = "SELECT FOUND_ROWS() AS ROWS";
									$gqq1 = "SELECT FOUND_ROWS() AS ROWS";
									$timeSTP = "";
									
									$rqq = mysqli_query($CONN, $gq);
									$rq2 = mysqli_query($CONN, $gq1);
									$nob = mysqli_fetch_array($rq2);
									
									$rqqq = mysqli_query($CONN, $gqq);
									$rqq2 = mysqli_query($CONN, $gqq1);
									$vob = mysqli_fetch_array($rqq2);
										
										if(mysqli_num_rows($rqq) >=1){
												
											while($rop = mysqli_fetch_array($rqq)){
												$dist = $rop['DistrictNumber'];
												$title = $rop['Title'];
												$desc = utf8_encode($rop['Description']);
												$s_date = date('M j, g:i A',strtotime($rop['PubDate']));
												$timeSTP = date('M j, g:i A',strtotime($rop['TimeStamp']));
												$cat = $rop['Category'];
												$img = $rop['ImageURL'];
												
												$sel = "SELECT `LocalImageURL` FROM `Images` WHERE `RemoteImageURL` = '$img'";
												$r_Q = mysqli_query($CONN, $sel);
												if(mysqli_num_rows($r_Q) >=1){
												    $roq = mysqli_fetch_array($r_Q);
												    $pre = $roq['LocalImageURL'];
												    $img = $PROTO1."images/".$pre;
												}
												
												$news_id = $rop['ID'];
												$vid_URL = $rop['TubeURL'];
												$author = $rop['StoryAuthor'];
												
												$n_obj = array("NewsID"=>$news_id,"District"=>$dist,"Title"=>$title,"Description"=>$desc,"Author"=>$author,
																	"PubDate"=>$s_date,"Category"=>$cat,"ImageURL"=>$img,"TubeURL"=>$vid_URL);
											
												array_push($ob_ay,$n_obj);
											}
											
										}else{
											/// NO NEWS STORIES
										}
										
										if(mysqli_num_rows($rqqq) >=1){
											
											while($rog = mysqli_fetch_array($rqqq)){
												$id = $rog['ID'];
												$vidT = $rog['VideoTitle'];
												$des = $rog['Description'];
												$vID = $rog['VideoID'];
												$vimgURL = $rog['VideoImageURL'];
												$vidDate = $rog['VideoDate'];
												$Pdiv = $rog['PoliceDivision'];
												$crimeT = $rog['CrimeType'];
												
												$ucc_obj = array("ID"=>$id,"VideoTitle"=>$vidT,"Description"=>$des,"VideoID"=>$vID,"VideoImageURL"=>$vimgURL,
																	"VideoDate"=>$vidDate,"PoliceDivision"=>$Pdiv,"CrimeType"=>$crimeT);
												
												array_push($oj_ary1, $ucc_obj);
											}
											
										}else{
											// NO VIDEO RECORDS
										}
										
										
										echo json_encode(array("NewsStories"=>$ob_ay,"NewsTotalCount"=>$nob['ROWS'],"VideoObjects"=>$oj_ary1,"VideoTotalCount"=>$vob['ROWS'],"TimeStamp"=>$timeSTP));
										
								}
								
									if($is_Vid == "false"){
											
										$query = "SELECT `Hash` FROM `CurrentHash` WHERE `HashName` = 'NewsStory'";
										$r_hash = mysqli_query($CONN, $query);
										
											if(mysqli_num_rows($r_hash) >=1){
													
												$var = mysqli_fetch_array($r_hash);
												$hash = $var['Hash'];
											
											}else{
													
												$hash = "";
											}
								
											$tall = join("', '", $dis_arr);
											$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `NewsStory` WHERE `ScrapeHash` = '$hash' AND `DistrictNumber` IN ('$tall')";
											$sql1 = "SELECT FOUND_ROWS() AS ROWS";
											$res1 = mysqli_query($CONN, $sql);
											$res2 = mysqli_query($CONN, $sql1);
											$ct = mysqli_fetch_array($res2);
											$obj_array = array();	
											$timeSTP = "";
											
												while($row = mysqli_fetch_array($res1)){
														
													$dist = $row['DistrictNumber'];
													$title = $row['Title'];
													$desc = utf8_encode($row['Description']);
													$s_date = date('M j, g:i A',strtotime($row['PubDate']));
													$timeSTP = date('M j, g:i A',strtotime($rop['TimeStamp']));
													$cat = $row['Category'];
													$img = $row['ImageURL'];
													
													$sel = "SELECT `LocalImageURL` FROM `Images` WHERE `RemoteImageURL` = '$img'";
													$r_Q = mysqli_query($CONN, $sel);
												if(mysqli_num_rows($r_Q) >=1){
												    $roww = mysqli_fetch_array($r_Q);
												    $pre = $roww['LocalImageURL'];
												    $img = $PROTO1."images/".$pre;
												}
													
													$news_id = $row['ID'];
													$vid_URL = $row['TubeURL'];
													$author = $row['StoryAuthor'];
													
													$n_obj = array("NewsID"=>$news_id,"District"=>$dist,"Title"=>$title,"Description"=>$desc,"Author"=>$author,
																		"PubDate"=>$s_date,"Category"=>$cat,"ImageURL"=>$img,"TubeURL"=>$vid_URL);
												
													array_push($obj_array,$n_obj);
												
												}
									
											
												echo json_encode(array("NewsTotalCount"=>$ct['ROWS'],"NewsObjects"=>$obj_array,"VideoTotalCount"=>"0","VideoObjects"=>"0","TimeStamp"=>$timeSTP));
										
										
										
										
										
									}else if($is_Vid == "true"){
											
										$query2 = "SELECT `Hash` FROM `CurrentHash` WHERE `HashName` = 'UCVideos'";
										$query3 = "SELECT `Hash` FROM `CurrentHash` WHERE `HashName` = 'NewsStory'";
										$uc_hash = 0;
										$ns_hash = 0;
										$obj_array = array();
										$obj_array1 = array();
										$r_hash2 = mysqli_query($CONN, $query2);
										$r_hash3 = mysqli_query($CONN, $query3);
											
											if(mysqli_num_rows($r_hash2) >=1){
													
												$var2 = mysqli_fetch_array($r_hash2);
												$uc_hash = $var2['Hash'];
											
											}else{
													
												$uc_hash = "";
											}
											
											
											if(mysqli_num_rows($r_hash3) >=1 ){
													
												$var3 = mysqli_fetch_array($r_hash3);
												$ns_hash = $var3['Hash'];
											
											}else{
												
												$ns_hash = "";
											}
											
											$tall = join("', '", $dis_arr);
											$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `NewsStory` WHERE `ScrapeHash` = '$ns_hash' AND `DistrictNumber` IN ('$tall')";
											$sql1 = "SELECT FOUND_ROWS() AS ROWS";
											$res1 = mysqli_query($CONN, $sql);
											$res2 = mysqli_query($CONN, $sql1);
											$ct = mysqli_fetch_array($res2);
												
											
												while($row = mysqli_fetch_array($res1)){
														
													$dist = $row['DistrictNumber'];
													$title = $row['Title'];
													$desc = utf8_encode($row['Description']);
													$s_date = date('M j, g:i A',strtotime($row['PubDate']));
													$cat = $row['Category'];
													$img = $row['ImageURL'];
													
													$sel = "SELECT `LocalImageURL` FROM `Images` WHERE `RemoteImageURL` = '$img'";
													$r_Q = mysqli_query($CONN, $sel);
												if(mysqli_num_rows($r_Q) >=1){
												    $rowx = mysqli_fetch_array($r_Q);
												    $pre = $rowx['LocalImageURL'];
												    $img = $PROTO1."images/".$pre;
												}
													
													$news_id = $row['ID'];
													$vid_URL = $row['TubeURL'];
													$author = $row['StoryAuthor'];
													
													$n_obj = array("NewsID"=>$news_id,"District"=>$dist,"Title"=>$title,"Description"=>$desc,"Author"=>$author,
																		"PubDate"=>$s_date,"Category"=>$cat,"ImageURL"=>$img,"TubeURL"=>$vid_URL);
												
													array_push($obj_array,$n_obj);
												
												}


												$qvid = "SELECT * FROM `UCVideos` WHERE `HashTag` = '$uc_hash'";
												$sql5 = "SELECT FOUND_ROWS() AS ROWS";
												$res5 = mysqli_query($CONN, $qvid);
												$res6 = mysqli_query($CONN, $sql5);
												$ct1 = mysqli_fetch_array($res6);
											
													while($row1 = mysqli_fetch_array($res5)){
														
														$vidT = $row1['VideoTitle'];
														$des = $row1['Description'];
														$vID = $row1['VideoID'];
														$vimgURL = $row1['VideoImageURL'];
														$vidDate = $row1['VideoDate'];
														$Pdiv = $row1['PoliceDivision'];
														$crimeT = $row1['CrimeType'];
														
														$ucc_obj = array("VideoTitle"=>$vidT,"Description"=>$des,"VideoID"=>$vID,"VideoImageURL"=>$vimgURL,
																			"VideoDate"=>$vidDate,"PoliceDivision"=>$Pdiv,"CrimeType");
														
														array_push($obj_array1, $ucc_obj);	
														
																		
													}
											
											
													echo json_encode(array("NewsTotalCount"=>$ct['ROWS'],"NewsObjects"=>$obj_array,"VideoTotalCount"=>$ct1['ROWS'],"VideoObjects"=>$obj_array1));
											
											
									}
									
								
									
										
									
								
							}else if(mysqli_num_rows($res) <=0 ) {
									
								echo json_encode(array("error"=>"true","msg"=>"No Record INVaIlid DEviCe"));
							
							}else{
									
								echo json_encode(array("error"=>"true","msg"=>mysql_error()));
							
							}
					}
			}
		
		




/////////////////////////////////////////////////////////////////////////////////////// Bookmark API FEED ///////////////////////////////////////////////////////////////////

						else if($_SERVER['REQUEST_METHOD'] == 'POST' && $data['Bookmark'] == "true"){
						//	else if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['Bookmark'] == "true"){
								$device = $data['DeviceID'];
								//$device = '1106d78f7c334ac537b71b53e40bcc5f';
								$isNews = $data['Bookmark_NewsStory'];
								$isUCVids = $data['Bookmark_UCVideos'];
								$isBkSub = $data['Bookmark_Submit'];
								$isBkNews = $data['BookmarkNews'];
								$isVid = $data['BookmarkVideos'];
								$isUSM_News = $data['BookmarkUSMurderNews'];
								$isUSM_List = $data['BookmarkUSMurderList'];
								$story_ID = $data['BookmarkID'];
								$rmBook = $data['BookmarkRemove'];
								$is_news_id = $data['News'];
								$is_vid_id = $data['Video'];
								$is_us_mur_is = $data['USMurder'];
								$is_usmur_id = $data['USMurderID'];
								$obj_news_id = $data['NewsID'];
								$obj_vid_id = $data['VideoID'];
								
								$news = array();
								$news_array = array();
								$videos = array();
								$videos_array = array();
								
								if(preg_match(R_MD5_MATCH, $device)){
										
									
									if($isNews == "true"){
												
										$sql = "SELECT `DeviceID` FROM `Bookmarks` WHERE `DeviceID` = '$device' AND `NewsStoryID` != 0";
										$isDev = mysqli_query($CONN, $sql);
									
										if(mysqli_num_rows($isDev) >=1){
												
											$getAll = "SELECT SQL_CALC_FOUND_ROWS * FROM `Bookmarks` WHERE `DeviceID` = '$device' AND `NewsStoryID` != 0";
											$cquery = "SELECT FOUND_ROWS() AS ROWS";
											$val = mysqli_query($CONN, $getAll);
											$cresult = mysqli_query($CONN, $cquery);
											$count = mysqli_fetch_array($cresult);
											
												while($rows = mysqli_fetch_array($val)){
											
													 if($rows['UCVideoID'] == 0){
														array_push($news, $rows['NewsStoryID']);
													}
												
												}
												
												///CHCEK FOR EMPTY VALUES IN NEWS ARRAY
													if(empty($news)){
														
													}else{
															
														$s_vals = join(',', $news);
														$news_story = "SELECT * FROM `NewsStory` WHERE `ID` IN ($s_vals) LIMIT 0,5";
														$res = mysqli_query($CONN, $news_story);
														
															while($n_row = mysqli_fetch_array($res)){
																	
																$dist = $n_row['DistrictNumber'];
																$title = trim(trimTitle($n_row['Title'],"true"));
																$desc = cleanUpHTML(utf8_encode(trim($n_row['Description'])));
																if(preg_match('/(DC )(\d{2}\-\d{2}\-\d{6})/is',$n_row['Description'],$may)){
																    $dcNumb = str_replace("DC ","DC# ",$may[0]);
																}else{
																    $dcNumb = "0";
																}
																$s_date = date('M j, Y',strtotime($n_row['PubDate']));
																$cat = trimCat($n_row['Category'],$n_row['Title']);
																$iimg = $n_row['ImageURL'];
																$news_id = $n_row['ID'];
																$vid_URL = $n_row['TubeURL'];
																$author = $n_row['StoryAuthor'];
																$divv = convertdiv($dist);
																
																
																if(empty($vid_URL)){
																	$vid_URL = "No Video";
																}
																
																
																$n_obj = array("NewsID"=>$news_id,"District"=>$dist,"Title"=>$title,"Description"=>$desc,"Author"=>$author,
																				"PubDate"=>$s_date,"Category"=>$cat,"ImageURL"=>$iimg,"TubeURL"=>$vid_URL,"Division"=>$divv,"DCNumber"=>$dcNumb);				
															
																array_push($news_array,$n_obj);
																
															}
															
																				
													}
													

												echo json_encode(array("Bookmarks"=>array("NewsStory"=>$news_array),"TotalCount"=>$count['ROWS']),JSON_NUMERIC_CHECK);
												//writeToLog(json_encode(array("Bookmarks"=>array("NewsStory"=>$news_array),"TotalCount"=>$count['ROWS']),JSON_NUMERIC_CHECK));
												
										}else{
											echo json_encode(array("error"=>"INVALID DEVICE"));
										}	
										
									}
									
									else if($isUSM_List == "true"){
									       
									    $sql = "SELECT `ID`,`USMurderID` FROM `Bookmarks` WHERE `DeviceID` = '$device' AND `USMurderID` != 0 ORDER BY `TimeStamp` DESC LIMIT 0,5";
									    $sql_c = "SELECT COUNT(`DeviceID`) AS ROWS FROM `Bookmarks` WHERE `USMurderID` != 0 AND `DeviceID` = '$device'";
									    $res = mysqli_query($CONN, $sql);
									    $res1 = mysqli_query($CONN, $sql_c);
									    $rep = mysqli_fetch_array($res1);
									    
									    $usm_array = array();
									    $obj_arr = array();
									    
									    if(mysqli_num_rows($res) >=1 ){
									        // ROWS RETURND
									       
									        while($roz = mysqli_fetch_array($res)){
									              array_push($usm_array,$roz['USMurderID']);
									        }
									        
									    }else{
									        // NO ROWS RETURNED
									    }
									    
									    if(empty($usm_array)){
									        
									    }else{
									        
									        $vals = join(',', $usm_array);
									        $usm_story = "SELECT * FROM `UnsolvedMurders` WHERE `ID` IN ($vals) LIMIT 0,5";
									        $rex = mysqli_query($CONN, $usm_story);
									        
									        
									        if(mysqli_num_rows($rex) >=1 ){
									            while($tor = mysqli_fetch_array($rex)){
									                $dcn = $tor['DCNumber'];
									                $mID = $tor['ID'];
									                $urlImg = "0";
									                $qxr = "SELECT `UCMurderURL` FROM `USMImages` WHERE `DCNumber` = '$dcn'";
									                $txr = mysqli_query($CONN, $qxr);
									                if(mysqli_num_rows($txr) >= 1){
									                    $aee = mysqli_fetch_array($txr);
									                    $urlImg = $aee['UCMurderURL'];
									                }else{
									                    $nDD = str_replace("DC#","DC",$tor['DCNumber']);
									                    $sdl = "SELECT `ImageURL` FROM `NewsStory` WHERE `Description` LIKE '%$nDD%'";
									                    $ety = mysqli_query($CONN, $sdl);
									                    if(mysqli_num_rows($ety) >= 1){
									                        $zer = mysqli_fetch_array($ety);
									                        $urlImg =  $zer['ImageURL'];
									                    }
									                }
									                $vicn = $tor['VictimName'];
									                $mdd = date('D. F jS, Y',strtotime($tor['MurderDate']));
									                $nurl = $tor['NewsURL'];
									                $ndes = cleanUpHTML(utf8_encode($tor['Description']));
									                
									                $obj = array("USMurderID"=>$mID,"DCNumber"=>$dcn,"VictimName"=>$vicn,"MurderDate"=>$mdd,"NewsURL"=>$nurl,"Description"=>$ndes,"ImageURL"=>$urlImg);
									                array_push($obj_arr,$obj);
									                
									            }
									            
									        }else{
									            
									        }
									        
									        
									        
									    }
									    
									    
									    echo json_encode(array("Bookmarks"=>array("USMurders"=>$obj_arr),"TotalCount"=>$rep['ROWS']),JSON_NUMERIC_CHECK);
									     
									    
									}
									
									else if($isUCVids == "true"){
										
										
										$sql = "SELECT `DeviceID` FROM `Bookmarks` WHERE `DeviceID` = '$device' AND `UCVideoID` != 0";
										$isDev = mysqli_query($CONN, $sql) or die("Bad Query ".mysql_error());
									
										if(mysqli_num_rows($isDev) >=1){
												
											$getAll = "SELECT SQL_CALC_FOUND_ROWS * FROM `Bookmarks` WHERE `DeviceID` = '$device' AND `UCVideoID` != 0";
											$cquery = "SELECT FOUND_ROWS() AS ROWS";
											$val = mysqli_query($CONN, $getAll);
											$cresult = mysqli_query($CONN, $cquery);
											$count = mysqli_fetch_array($cresult);
											
												while($rows = mysqli_fetch_array($val)){
													
													if($rows['NewsStoryID'] == 0){
														array_push($videos, $rows['UCVideoID']);
													}
												
												}

													if(empty($videos)){
														
													}else{
															
														$v_vals = join(',', $videos);
														$vid_story = "SELECT * FROM `UCVideos` WHERE `ID` IN ($v_vals) LIMIT 0,5";
														$res_v = mysqli_query($CONN, $vid_story);
														
															while($v_row = mysqli_fetch_array($res_v)){

															    $vid_title = trim(trimVideoTitle($v_row['VideoTitle']));
															    $desc = cleanUpHTML(utf8_encode($v_row['Description']));
																$vid_ID = $v_row['ID'];
																$imgURL = $v_row['VideoImageURL'];
																$vid_Date = $v_row['VideoDate'];
																$div = $v_row['PoliceDivision'];
																$distNum = $v_row['DistrictNumber'];
																$cat = convertVideoTitle($v_row['CrimeType']);
																$vid_URL = "https://www.youtube.com/embed/".$vid_ID;
																$divv = convertdiv($v_row['DistrictNumber']);
																
    																if(strpos($vid_title," DC ") >=1){
    																    $txt = explode(" DC",$vid_title);
    																    $vid_title = $txt[0];
    																}
																
																$v_obj = array("VideoID"=>$vid_ID,"Division"=>$div,"Title"=>$vid_title,"Description"=>$desc,"District"=>$distNum,
																				"PubDate"=>$vid_Date,"Category"=>$cat,"ImageURL"=>$imgURL,"TubeURL"=>$vid_URL,"Division"=>$divv);
																				
																array_push($videos_array,$v_obj);
																
															}
															
															
															
													}
												
												
												echo json_encode(array("Bookmarks"=>array("UCVideos"=>$videos_array),"TotalCount"=>$count['ROWS']),JSON_NUMERIC_CHECK);
												writeToLog(json_encode(array("Bookmarks"=>array("UCVideos"=>$videos_array),"TotalCount"=>$count['ROWS']),JSON_NUMERIC_CHECK));
												
												
										}else{
											echo json_encode(array("error"=>"INVALID DEVICE"));
										}
										
										
										
									}else if ($isBkSub == "true"){
										
										$device = $data['DeviceID'];
								//$device = '1106d78f7c334ac537b71b53e40bcc5f';
								$isNews = $data['Bookmark_NewsStory'];
								$isUCVids = $data['Bookmark_UCVideos'];
								$isBkSub = $data['Bookmark_Submit'];
								$news = array();
								$news_array = array();
								$videos = array();
								$videos_array = array();
										
										
										$sql = "SELECT `DeviceID` FROM `Devices` WHERE `DeviceID` = '$device'";
										$res_q = mysqli_query($CONN, $sql) or die($MYSQL_ERROR. header('HTTP/1.1 403 Forbidden'));
											
											if(mysqli_num_rows($res_q) >=1){
												
												if($isBkNews == "true"){ // IF USER SENDS A NEWS STORY
														
													$sql1 = "SELECT `DeviceID` FROM `Bookmarks` WHERE `NewsStoryID` = '$story_ID' AND `DeviceID` = '$device'";
													$res_q1 = mysqli_query($CONN, $sql1); //LOOK FOR BOOKMARK BEFOR INSERT
														
														if(mysqli_num_rows($res_q1) >=1){
															// BOOKMARK ALREADY EXIST
															echo json_encode(array("error"=>"true","Insert Record"=>"false","msg"=>"Record Already Exist"));
														
														}else{
																
															$in_q = "INSERT INTO `Bookmarks` (`DeviceID`,`NewsStoryID`,`UCVideoID`)VALUES('$device','$story_ID','0')";
															$res_in = mysqli_query($CONN, $in_q);
															
																if($res_in){
																	echo json_encode(array("error"=>"false","Insert Record"=>"true","msg"=>"success"));
																}else{
																	echo json_encode(array("error"=>"true","Insert Record"=>"false","msg"=>mysql_error()));
																}
														}
														
														
												}else if($isVid == "true"){
													
													$sql = "SELECT `DeviceID` FROM `Bookmarks` WHERE `UCVideoID` = '$story_ID' AND `DeviceID` = '$device'";
													$res = mysqli_query($CONN, $sql);
														
														if(mysqli_num_rows($res) >=1){
														
															echo json_encode(array("error"=>"false","Insert Record"=>"true","msg"=>"success"));
														
														}else{
																	
															$in = "INSERT INTO `Bookmarks` (`DeviceID`,`NewsStoryID`,`UCVideoID`)VALUES('$device','0','$story_ID')";	
															$in_res = mysqli_query($CONN, $in);
															
																if($in_res){
																	echo json_encode(array("error"=>"false","Insert Record"=>"true","msg"=>"success"));
																}else{
																	echo json_encode(array("error"=>"true","Insert Record"=>"false","msg"=>mysqli_error($CONN)));
																}
															
														}
													
												}else if($isUSM_News == "true"){
						
												    
												    $sql1 = "SELECT `DeviceID` FROM `Bookmarks` WHERE `USMurderID` = '$story_ID' AND `DeviceID` = '$device'";
												    $res_q1 = mysqli_query($CONN, $sql1); //LOOK FOR BOOKMARK BEFOR INSERT
												    
												    if(mysqli_num_rows($res_q1) >=1){
												        // BOOKMARK ALREADY EXIST
												        echo json_encode(array("error"=>"true","Insert Record"=>"false","msg"=>"Record Already Exist"));
												        
												    }else{
												        
												        $in_q = "INSERT INTO `Bookmarks` (`DeviceID`,`NewsStoryID`,`UCVideoID`,`USMurderID`)VALUES('$device','0','0','$story_ID')";
												        $res_in = mysqli_query($CONN, $in_q);
												        
												        if($res_in){
												            echo json_encode(array("error"=>"false","Insert Record"=>"true","msg"=>"success"));
												        }else{
												            echo json_encode(array("error"=>"true","Insert Record"=>"false","msg"=>mysqli_error($CONN)));
												        }
												    }

												    
												}
												
												
											}else{
												// DEVICE DONES NOT EXIST IN DEVICES TABLE NEED TO STOP THE PROGRAM  HERE !!!!!!
											}
										
									}
									
										else if($rmBook == "true"){
											
											if($is_news_id == "true" && $is_vid_id == "false"){
												
												$sql = "SELECT `ID`,`DeviceID`,`NewsStoryID` FROM `Bookmarks` WHERE `DeviceID` = '$device' AND `NewsStoryID` = '$obj_news_id'";
												$res = mysqli_query($CONN, $sql) or die($MYSQL_ERROR. header('HTTP/1.1 403 Forbidden'));
													
													if(mysqli_num_rows($res)>=1){
															
														$row = mysqli_fetch_array($res);
														$rID = $row['ID'];
														$del = "DELETE FROM `Bookmarks` WHERE `ID` = '$rID'";
														$res1 = mysqli_query($CONN, $del);
															
															if($res1){
																	
																echo json_encode(array("error"=>"false","msg"=>"Record Deleted"));
															
															}else{
																// DEL RECORD FAILED
																echo json_encode(array("error"=>"true","msg"=>"Record Deleted FAILED !!"));
															}
													
													}else{
														//NO REcord RETUNED 
														echo json_encode(array("error"=>"true","msg"=>"Record Does Not Exist"));
													}
												
												}else if($is_vid_id == "true" && $is_news_id == "false"){
														
													$sql = "SELECT `ID`,`DeviceID`,`UCVideoID` FROM `Bookmarks` WHERE `DeviceID` = '$device' AND `UCVideoID` = '$obj_vid_id'";
													$res = mysqli_query($CONN, $sql) or die($MYSQL_ERROR. header('HTTP/1.1 403 Forbidden'));
														
														if(mysqli_num_rows($res)>=1){
																
															$row = mysqli_fetch_array($res);
															$rID = $row['ID'];
															$del = "DELETE FROM `Bookmarks` WHERE `ID` = '$rID'";
															$res1 = mysqli_query($CONN, $del);
																
																if($res1){
																		
																	echo json_encode(array("error"=>"false","msg"=>"Record Deleted"));
																
																}else{
																	// DEL RECORD FAILED
																	echo json_encode(array("error"=>"true","msg"=>"Record Deleted FAILED !!"));
																}
														}else{
														
														echo json_encode(array("error"=>"true","msg"=>"Record Does Not Exist"));
							
													}
													
													
												}else if($is_us_mur_is == "true"){
												 
												    $chl = "SELECT `ID` FROM `Bookmarks` WHERE `DeviceID` = '$device' AND `USMurderID` = '$is_usmur_id'";
												    $ret = mysqli_query($CONN, $chl);
												    if(mysqli_num_rows($ret) >=1){
												        $rop = mysqli_fetch_array($ret);
												        $id = $rop['ID'];
												        $rv = "DELETE FROM `Bookmarks` WHERE `ID` = '$id'";
												        $you = mysqli_query($CONN, $rv);
												        if($you){
												            
												            echo json_encode(array("error"=>"false","msg"=>"Record Deleted"));
												            
												        }else{
												            // DEL RECORD FAILED
												            echo json_encode(array("error"=>"true","msg"=>"Record Deleted FAILED !!"));
												        }
												    }else{
												        echo json_encode(array("error"=>"true","msg"=>"Record Does Not Exist"));
												    }
												    
												}
											
											
										}
									
									
								}
						}

			
			
/////////////////////////////////////////////////////////////////////////////////////// END Bookmark API FEED ///////////////////////////////////////////////////////////////////			
			
			
			
			
			
			
			else if($_SERVER['REQUEST_METHOD'] == 'POST' && $data['ImageUpdate'] == "true"){
						    $devID  = $data['DeviceID'];
						    
						 //   if(preg_match(R_MD5_MATCH, $data['DeviceID']) && == "6D0CB6E184986C431DA3EB154B127E6B"){
						        
						        $data = $data['Images'];
						          foreach($data as $item){
						            $iURL = $item['RemoteImageURL'];
						            $chk = "SELECT `RemoteImageURL` FROM  `Images` WHERE `RemoteImageURL` = '$iURL'";
						            $res = mysqli_query($CONN, $chk);
						            
						              if(mysqli_num_rows($res)>=1){
						                  // RECORD EXIST
						              }else{
						                  $r_url = $item['RemoteImageURL'];
						                  $i_url = $item['LocalImageURL'];
						                  $in = "INSERT INTO `Images` (`LocalImageURL`,`RemoteImageURL`)VALUES('$i_url','$r_url')";
						                  $in_res = mysqli_query($CONN, $in);
						              }
						            
						          }
						        
						      //}
						}
			
			
			
			else{
			    header("HTTP/1.0 404 Not Found");
				exit();
			}
		
		
		
	


?>
