<?php
/*
 * Class string
 * @author JosT
 * @return string
 */
class Date
{
	function dmY($val)
	{
		if ($val != null) {
			$date = strtotime($val);
			echo date('d-m-Y',$date);
		}
	}
	function mdY($val)
	{
		if ($val != null) {
			$date = strtotime($val);
			return date('m-d-Y',$date);
		}
	}
}