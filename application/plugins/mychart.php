<?php
class mychart
{
    function createDateRangeArray($strDateFrom,$strDateTo) 
    {    
        $aryRange=array();
        $iDateFrom=mktime(0,0,0,substr($strDateFrom,5,2),substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(0,0,0,substr($strDateTo,5,2),substr($strDateTo,8,2),substr($strDateTo,0,4));
        if ($iDateTo>=$iDateFrom) 
        {
                array_push($aryRange,date('d/m/Y', $iDateFrom));
                while ($iDateFrom<$iDateTo) 
                {
                    $iDateFrom+=86400;
                    array_push($aryRange,date('d/m/Y',$iDateFrom));
                }
        }
        return $aryRange;
    }
	function createDateRangeArrayInsert($strDateFrom,$strDateTo) 
    {    
        $aryRange=array();
        $iDateFrom=mktime(0,0,0,substr($strDateFrom,5,2),substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(0,0,0,substr($strDateTo,5,2),substr($strDateTo,8,2),substr($strDateTo,0,4));
        if ($iDateTo>=$iDateFrom) 
        {
                array_push($aryRange,date('Y-m-d', $iDateFrom));
                while ($iDateFrom<$iDateTo) 
                {
                    $iDateFrom+=86400;
                    array_push($aryRange,date('Y-m-d',$iDateFrom));
                }
        }
        return $aryRange;
    }
	function createData($arrayDate,$fromdate,$data,$type='ttrc'){
		if($type==1)
		$type='ttv';
		elseif($type==2)
		$type='ttrc';
		
		$fromTime=mktime(0,0,0,substr($fromdate,5,2),substr($fromdate,8,2),substr($fromdate,0,4));
			$arrData=array_fill(0,count($arrayDate),0);	
			$aaa=0;			
			foreach($data as $cd){
				$key=ceil((strtotime($cd['date'])-$fromTime)/86400);
				$arrData[$key]=(int)$cd[$type];
				$aaa = $aaa+(int)$cd[$type];
			}
		return $arrData;
	}
	function createDataCPM($arrayDate,$fromdate,$data,$type){
		if($type==1)
		$type='charge';
		else
		$type='cpm';
		
		$fromTime=mktime(0,0,0,substr($fromdate,5,2),substr($fromdate,8,2),substr($fromdate,0,4));
			$arrData=array_fill(0,count($arrayDate),0);	
			$aaa=0;			
			foreach($data as $cd){
				$key=ceil((strtotime($cd['date'])-$fromTime)/86400);
				$arrData[$key]=(int)$cd[$type];
				$aaa = $aaa+(int)$cd[$type];
			}
		return $arrData;
	}
	function createDataHour($fromdate,$data,$arrBannerid=array()){
		$fdate = $fromdate;
		$today = date("Y-m-d");
		$yesterday = date("Y-m-d", strtotime("yesterday"));
		$hour = getdate();
		$hour = $hour['hours'];
		$arrayHour=range(0, $hour, 1);
		if(empty($arrBannerid)){
			$arrData1=array_fill(0,count($arrayHour),0);		
			foreach($data as $cd){
				$key=(int)str_replace('h','',$cd['hour']);
				if(isset($cd[2])){
					$arrData1[$key]=(int)$cd[2];
				}else{
					$arrData1[$key]=(int)$cd[1];
				}
			}
			
			if($fdate != $yesterday && $fdate != $today)
			{
				$show_chart_time = 24;
			}
			else
			{
				if($fdate == $today)
				{
					if($hour<=5)
					{
						$show_chart_time = 19 + $hour;
					}
					else
					{
						$show_chart_time = $hour - 5;
					}
				}
				else if($fdate == $yesterday)
				{
					if($hour<=5)
					{
						$show_chart_time = 19 + $hour;
					}
					else
					{
						$show_chart_time = 24;
					}
				}
			}
			
			$arrayHour=array(6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,'0',1,2,3,4,5);
			$arrayHour1 = array();
			$arrData2 = array();
			for($i=0;$i<$show_chart_time;$i++){
				if(isset($arrayHour[$i])){
					$arrayHour1[$i] = $arrayHour[$i];
				}
			}
			if($fdate == $today && $hour < 6 )
			{
				$arrData2=array_fill(0, count($arrayHour1), 0);
			}
			else
			{
				for($t=0;$t<count($arrayHour1);$t++){
					if(isset($arrData1[$arrayHour1[$t]])){
						$arrData2[$t] = $arrData1[$arrayHour1[$t]];
					}else{
						$arrData2[$t] = 0;
					}
				}
			}
			$arrData=array('hour'=>$arrayHour1,'data'=>$arrData2);
		}
		else{
			$arrData1=array_fill(0, count($arrayHour), 0);
			$arrData2=array_fill(0, count($arrayHour), 0);		
			foreach($data as $cd){
				$key=(int)str_replace('h','',$cd['hour']);
				if($cd[0]==$arrBannerid[0]){
					$arrData1[$key]=(int)$cd[2];	
				}
				else{
					$arrData2[$key]=(int)$cd[2];	
				}
			}
			
		
			if($fdate != $yesterday && $fdate != $today)
			{
				$show_chart_time = 24;
			}
			else
			{
				if($fdate == $today)
				{
					if($hour<=5)
					{
						$show_chart_time = 19 + $hour;
					}
					else
					{
						$show_chart_time = $hour - 5;
					}
				}
				else if($fdate == $yesterday)
				{
					if($hour<=5)
					{
						$show_chart_time = 19 + $hour;
					}
					else
					{
						$show_chart_time = 24;
					}
				}
			}
			
			$arrayHour=array(6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,'0',1,2,3,4,5);
			$arrayHour1 = array();
			$arrData3 = array();
			for($i=0;$i<$show_chart_time;$i++){
				$arrayHour1[$i] = $arrayHour[$i];
			}
			
			if($fdate == $today && $hour < 6 )
			{
				$arrData3=array_fill(0, count($arrayHour1), 0);
				$arrData4=array_fill(0, count($arrayHour1), 0);
			}
			else
			{
				for($t=0;$t<count($arrayHour1);$t++){
					$arrData3[$t] = $arrData1[$arrayHour1[$t]];
					$arrData4[$t] = $arrData2[$arrayHour1[$t]];
				}	
			}
			//
			$arrData=array('hour'=>$arrayHour1,'data1'=>$arrData3,'data2'=>$arrData4);
		}
		return $arrData;
	}
}