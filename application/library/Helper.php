<?php
/**
 * @author JosT
 * @date   Jun 2015
 */
class Helper 
{ 	
	function check($id, $acitve)
	{
		if ($acitve != null && $acitve == 1) {
			echo '<td style="text-align: center"><a href="" class="turn-active" title="'.$id.'-'.$acitve.'"><i class="fa fa-fw fa-check" title="Hiển thị"></i></a></td>';
		} else if ($acitve != 1) {			
			echo '<td style="text-align: center"><a href="" class="turn-active" title="'.$id.'-'.$acitve.'"><i class="fa fa-fw fa-power-off" title="Không hiển thị"></i></a></td>';
		}
	}
	function menu($id, $menu) 
	{
		if ($menu != null && $menu == 1) {
			echo '<td style="text-align: center"><a href="" class="turn-menu" title="'.$id.'-'.$menu.'"><i class="fa fa-fw fa-check" title="Hiển thị"></i></a></td>';
		} else if ($menu != 1) {			
			echo '<td style="text-align: center"><a href="" class="turn-menu" title="'.$id.'-'.$menu.'"><i class="fa fa-fw fa-power-off" title="Không hiển thị"></i></a></td>';
		}
	}
	/**
	 * Function Recursive
	 * @author JosT
	 * @date   Apr 2015
	 */
	function recursive($data,$columnName = "",$parentValue = 0, $lever = 1,&$resultArr)
	{
		if(count($data) > 0){
			foreach ($data as $key => $value) {
				if($value['parent'] == $parentValue){
					$value['lever'] = $lever;
					$resultArr[] = $value;
					$newParent = $value['id'];
					unset($data[$key]);
					$this->recursive($data,$columnName,$newParent,$lever+1,$resultArr);
				}
			}
		}
	}
	function buildArr($data, $columnName, $parentValue = 0)
	{
		$this->recursive($data, $columnName, $parentValue, 1, $resultArr);
		return $resultArr;
	}
	/* 
     * filtering an array 
     */ 
    function filter_by_value ($array, $index, $value)
    { 
        if(is_array($array) && count($array) > 0) { 
            foreach(array_keys($array) as $key){ 
                $temp[$key] = $array[$key][$index]; 
                 
                if ($temp[$key] == $value){ 
                    $newarray[$key] = $array[$key]; 
                } 
            } 
      	} 
  		return $newarray; 
    }
    function get_client_ip() {
	    $ipaddress = '';
	    if (@$_SERVER['HTTP_CLIENT_IP'])
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(@$_SERVER['HTTP_X_FORWARDED_FOR'])
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(@$_SERVER['HTTP_X_FORWARDED'])
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(@$_SERVER['HTTP_FORWARDED_FOR'])
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(@$_SERVER['HTTP_FORWARDED'])
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(@$_SERVER['REMOTE_ADDR'])
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = '';
	    return $ipaddress;
	}
} 