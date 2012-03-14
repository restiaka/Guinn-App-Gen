<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Export_m extends CI_Model {
	
	protected $CI;
	
	function __construct(){
		parent::__construct();
		//$this->CI = get_instance();
		$this->load->model('media_m','media');
		$this->load->model('customer_m','customer');
		$this->load->model('campaign_m','campaign');
		
	}
	
	function exportUploadedLists($gid)
	{
	 require_once "Spreadsheet/Excel/Writer.php";
	 $column = ''; $row = 0;
     $mediaRow = $this->media->retrieveMedia(array('campaign_media.GID'=>$gid),array('fields'=>'campaign_media.media_title,campaign_media.media_description,campaign_media.media_url,campaign_media.media_uploaded_date,campaign_media.media_status,campaign_media_owner.uid'));
	 if(!$mediaRow) return false;
	
	 $campaign = $this->campaign->detailCampaign($gid);
      
	  $xls = new Spreadsheet_Excel_Writer();
	  $xls->send("campaign_".$gid."_uploadedlists_".date('dFY').".xls");
	  $worksheet=&$xls->addWorksheet('Export');

	//Setup different styles
	$sheetTitleFormat =& $xls->addFormat(array('bold'=>1,'size'=>10));
	$columnTitleFormat =& $xls->addFormat(array('bold'=>1,'top'=>1,'bottom'=>1 ,'size'=>9));
	$regularFormat =& $xls->addFormat(array('size'=>9,'align'=>'left','textWrap'=>1));
	
	//Write sheet title in upper left cell
	$worksheet->write($row, $column, "Campaign , generate on ".date("d F Y"), $sheetTitleFormat);
	$worksheet->write(++$row, $column, "Title : ".$campaign['title'], $sheetTitleFormat);
	$worksheet->write(++$row, $column, "Date : ".$campaign['startdate']." - ".$campaign['enddate'], $sheetTitleFormat);
	 
	//Write column titles two rows beneath sheet title
	$row += 4;
	$columnTitles = array('Title','Description','URL','Uploaded Date','Status','FB UID');
	foreach ($columnTitles as $title) {
	  $worksheet->write($row, $column, $title, $columnTitleFormat);
	  $column++;
	}
	 
	//Write each datapoint to the sheet starting one row beneath
	$row++;
	  
	 foreach ($mediaRow as $v){
	  $column = 0;
		  foreach ($v as $key => $value) {
			$worksheet->write($row, $column, $value, $regularFormat);
			$column++;
		  }
	  $row++;	
	 }
	 $xls->close(); 
		
	 //dg($list);
	}
	
	function exportUploadedFiles($gid){
	require_once "File/Archive.php";

		File_Archive::setOption('zipCompressionLevel', 0);
		$baseDir = CUSTOMER_IMAGE_DIR.$gid;

	$files = list_files_dir($baseDir);	
		File_Archive::extract(
			$files,
			File_Archive::toArchive(
				"campaign_".$gid."_uploadedfiles_".date('dFY').".zip",
				File_Archive::toOutput()
			)
		);
	
	}
	
	function exportCustomerByAppID($appid){
	  require_once "Spreadsheet/Excel/Writer.php";
	   $column = ''; $row = 0;
	  if(!$customers = $this->customer->getCustomerByAppID($appid)){
	    $this->notify->set_message('error', 'SORRY! NO DATA YET');
	   	redirect(site_url('admin/app/lists'));
		return false;
	  }
	  
     	  $xls = new Spreadsheet_Excel_Writer();
	  $xls->send("customer_byfacebookappid_".date('dFY').".xls");
	  $worksheet= & $xls->addWorksheet('Export');
	  
	  	//Setup different styles
	$sheetTitleFormat =& $xls->addFormat(array('bold'=>1,'size'=>10));
	$columnTitleFormat =& $xls->addFormat(array('bold'=>1,'top'=>1,'bottom'=>1 ,'size'=>9));
	$regularFormat =& $xls->addFormat(array('size'=>9,'align'=>'left','textWrap'=>1));
	
	//Write sheet title in upper left cell
	$worksheet->write($row, $column, "Customer By Facebook AppID $appid , generate on ".date("d F Y"), $sheetTitleFormat);
	  
		//Write column titles two rows beneath sheet title
		$row += 4;
		$columnTitles = array('FACEBOOK APP ID','CUSTOMER_ID','FIRSTNAME','LASTNAME','EMAIL','ADDRESS','MOBILE','SUBSCRIPTION');
		foreach ($columnTitles as $title) {
		  $worksheet->write($row, $column, $title, $columnTitleFormat);
		  $column++;
		}	  
		
				//Write each datapoint to the sheet starting one row beneath
		$row++;
		  
		 foreach ($customers as $v){
		  $column = 0;
			  foreach ($v as $key => $value) {
				$worksheet->write($row, $column, $value, $regularFormat);
				$column++;
			  }
		  $row++;	
		 }
		 $xls->close(); 	  
	 	
	}
	
	function exportCustomerByCampaign($gid){
	 $column = ''; $row = 0;
	  require_once "Spreadsheet/Excel/Writer.php";
	  if(!$customers = $this->customer->getCustomerByCampaign($gid)){
	    $this->notify->set_message('error', 'SORRY! NO DATA YET');
	   	redirect(site_url('admin/campaign/lists'));
		return false;
	   }
	  	
	  $xls = new Spreadsheet_Excel_Writer();
	  $xls->send("customer_bycampaign_".date('dFY').".xls");
	  $worksheet= & $xls->addWorksheet('Export');
	  
	 //Setup different styles
	$sheetTitleFormat =& $xls->addFormat(array('bold'=>1,'size'=>10));
	$columnTitleFormat =& $xls->addFormat(array('bold'=>1,'top'=>1,'bottom'=>1 ,'size'=>9));
	$regularFormat =& $xls->addFormat(array('size'=>9,'align'=>'left','textWrap'=>1));
	
	//Write sheet title in upper left cell
	$worksheet->write($row, $column, "Customer by Campaign , generate on ".date("d F Y"), $sheetTitleFormat);
	  
		//Write column titles two rows beneath sheet title
		$row += 4;
		$columnTitles = array('CampaignID','Campaign Title','CUSTOMER_ID','FIRSTNAME','LASTNAME','EMAIL','ADDRESS','MOBILE','SUBSCRIPTION');
		foreach ($columnTitles as $title) {
		  $worksheet->write($row, $column, $title, $columnTitleFormat);
		  $column++;
		}	  
		
				//Write each datapoint to the sheet starting one row beneath
		$row++;
		  
		 foreach ($customers as $v){
		  $column = 0;
			  foreach ($v as $key => $value) {
				$worksheet->write($row, $column, $value, $regularFormat);
				$column++;
			  }
		  $row++;	
		 }
		 $xls->close(); 
	
	}
	
	
	

}