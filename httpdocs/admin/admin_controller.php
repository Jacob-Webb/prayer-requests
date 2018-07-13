<?php
require "dashboard_logic.php";

/******************************************************************************
~~~~~~~~~~~~~ Functions for admin/index.php  ~~~~~~~~~~~~~~~
******************************************************************************/
function getDateParameters() {	
	//Get the date range passed to this page and set the date_select variable
	$given_start_date = isset($_GET['begin-date']) ? $_GET['begin-date'] : "";
	$given_end_date = isset($_GET['begin-date']) ? $_GET['begin-date'] : "";

	//If a beginning or ending date were given as parameters, the time period should be a range selection
	if($given_start_date || $given_end_date){
		$date['time_period'] = 'range';
		$date['begin_date'] = $given_start_date;
		$date['end_date'] = $given_end_date;
	//Otherwise the time period will have been one of the other choices and the beginning and ending dates will reflect that
	} else {
		$date['time_period'] = isset($_GET['date-range']) ? $_GET['date-range'] : "";
		$date['begin_date'] = getBeginDate()
	    $time_period = isset($_GET['date-range']) ? $_GET['date-range'] : "";
    	$begin_date = getBeginDate($time_period);
    	$end_date = getEndDate($time_period);
	}

return $dates;
}
?>