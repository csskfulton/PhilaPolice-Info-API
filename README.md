# Phila Police Info API

This API provides JSON formated news updates of the Philadelphia Police Department.

## Hash Key Exchange
* **URL**
`/api/v1/phila_pd_api.php`

* **Method:**

  `GET`
  
*  **GET Params**

   **Required:**
 
    + `Update` - (String) true;
    + `DeviceID` - (String) MD5 Hash;

    _Example_ 
    `GET /api/v1/phila_pd_api.php?Update=true&DeviceID=CFCD208495D565EF66E7DFF9F98764DA`.

* **Success Response:**

  * **Code:** 200 <br />
  * **Returns:** 

``` 
{
	"HashKeys": [{
		"HashName": "NewsStory",
		"Hash": "MD5 Hash"
	}, {
		"HashName": "Calendar",
		"Hash": "MD5 Hash"
	}, {
		"HashName": "UCVideos",
		"Hash": "MD5 Hash"
	}],

	"error": "false"

}
```
 
* **Error Response:**

  * **Code:** 404 NOT FOUND <br />
    **Content:** ```{"error":"true","msg":"NOT A VALID DEVICE BEING USED !"}```



## Lastest News Update
* **URL**
`/api/v1/phila_pd_api.php`

* **Method:**

  `GET`
  
*  **GET Params**

   **Required:**
 
    + `LatestNews` - (String) true;
    
    **Optional:**
    
    + `Start` - (String) Number;
    + `End` - (String) Number;

    _Example_ 
    `GET /api/v1/phila_pd_api.php?LatestNews=true&Start=0&End=10`.

* **Success Response:**

  * **Code:** 200 <br />
  * **Returns:** 

``` 
{
	"TotalCount": 10,
	"News": [{
		"StoryID": "105",
		"AlertType": "Wanted",
		"StoryDate": "Jan 9, 11:53 AM",
		"StoryAuthor": "Gary Mercante",
		"ImageURL": "http:\/\/phillypd.info\/apps\/philapd\/images\/0_1515518113_image.jpg",
		"StoryTitle": "Suspect for Commercial Burglary in the 8th District",
		"StoryExcert": "On January 8, 2018, at 2:50 pm, an unknown male entered the Calvary......",
		"TubeURL": "https:\/\/www.youtube.com\/embed\/wlJ_Ih42k6g?rel=0"
	}, {
		"StoryID": "106",
		"AlertType": "Samantha Vargas",
		"StoryDate": "Jan 9, 7:58 AM",
		"StoryAuthor": "Media Relations",
		"ImageURL": "http:\/\/phillypd.info\/apps\/philapd\/images\/0_1515503713_image.jpg",
		"StoryTitle": "Missing Person \u2013 Samantha Vargas \u2013 From the 25th District",
		"StoryExcert": "The Philadelphia Police Department needs the publ...... ",
		"TubeURL": "No Video"
	}]
}
```
 
* **Error Response:**

  * **Code:** 404 NOT FOUND <br />
    **Content:** ```{"error":"true","msg":"NO HASH HAS BEEN SET"}```

## Police District Info
* **URL**
`/api/v1/phila_pd_api.php`

* **Method:**

  `GET`
  
*  **GET Params**

   **Required:**
 
    + `DistrictInfo` - (String) true;
    + `DistrictNumber` - (String) Number;
    
    _Example_ 
    `GET /api/v1/phila_pd_api.php?DistrictNumber=12&DistrictInfo=true`

* **Success Response:**

  * **Code:** 200 <br />
  * **Returns:** 

``` 
{
	"District": {
		"DistrictNumber": "12",
		"DistrictAddress": "65th St. and Woodland Ave. \r\n Philadelphia, PA 19142",
		"CaptainName": "DeShawn Beaufort",
		"CaptainImageURL": "https:\/\/www.phillypolice.com\/assets\/Uploads\/Capt-Deshawn-Beaufort.jpg",
		"DistrictEmail": "police.co_12@phila.gov",
		"DistrictPhone": "215-686-3120\r\n"
	},
	"PSAInfo": [{
		"PSAAreaNum": "PSA 1 ",
		"LTName": "LT Joseph Galie",
		"LTEmail": "ppd.12_psa1@phila.gov"
	}, {
		"PSAAreaNum": "PSA 2 ",
		"LTName": "LT Christopher Kinslow",
		"LTEmail": "ppd.12_psa2@phila.gov"
	}],
	"CalenderInfo": [{
		"MeetingName": "",
		"MeetingDate": "December 28th, 2017 5:00 PM",
		"MeetingLocation": "6448 Woodland, Philadelphia Pa"
	}, {
		"MeetingName": "PDAC",
		"MeetingDate": "January 2nd, 2018 1:00 PM",
		"MeetingLocation": "6448 Woodland, Philadelphia Pa"
	}]
}
```
 
* **Error Response:**

  * **Code:** 404 NOT FOUND <br />
    **Content:** ```{"error":"true","msg":"NO HASH HAS BEEN SET"}```

## Lastest News by Police District
* **URL**
`/api/v1/phila_pd_api.php`

* **Method:**

  `GET`
  
*  **GET Params**

   **Required:**
 
    + `DistrictNews` - (String) true;
    + `DistrictNumber` - (String) Number;
    
    **Optional:**
    
    + `Start` - (String) Number;
    + `End` - (String) Number;

    _Example_ 
    `GET /api/v1/phila_pd_api.php?DistrictNews=true&DistrictNumber=12&Start=0&End=10`.

* **Success Response:**

  * **Code:** 200 <br />
  * **Returns:** 

``` 
{
	"Articles": [{
		"StoryID": "102",
		"AlertType": "Wanted",
		"District": "12",
		"Title": "Suspect for Theft From an ATM in the 12th District",
		"Excert": "On December 24th, 2017, at 5;04 am, an unknown male........",
		"StoryDate": "Jan 8, 9:26 AM",
		"CaptionURL": "http:\/\/phillypd.info\/apps\/philapd\/images\/4_1515431712_image.jpg",
		"StoryURL": "https:\/\/pr.phillypolice.com\/2018\/01\/wanted-suspect-for-theft-from-an-atm-in-the-12th-district-video\/",
		"VideoURL": "https:\/\/www.youtube.com\/embed\/xTDYwAHvgtk?rel=0",
		"Author": "Media Relations"
	}, {
		"StoryID": "92",
		"AlertType": "Peter Williams",
		"District": "12",
		"Title": "Missing Person \u2013 Peter Williams- from the 12th District",
		"Excert": "The Philadelphia Police Department needs the p.....",
		"StoryDate": "Jan 3, 8:25 AM",
		"CaptionURL": "http:\/\/phillypd.info\/apps\/philapd\/images\/1_1514999712_image.jpg",
		"StoryURL": "https:\/\/pr.phillypolice.com\/2018\/01\/missing-person-peter-williams-from-the-12th-district\/",
		"VideoURL": "No Video",
		"Author": "Gary Mercante"
	}],
	"TotalCount": "5"
}
```
 
* **Error Response:**

  * **Code:** 404 NOT FOUND <br />
    **Content:** ```{"error":"true","msg":"NO HASH HAS BEEN SET"}```








