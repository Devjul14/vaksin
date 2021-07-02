<?php
Class Tglindo {

function tgl($tgl,$tipe)
{
	$month = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
	$xmonth = array("Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agt","Sep","Okt","Nop","Des");
	$hari = substr($tgl,0,10);
	$jam = substr($tgl,11,5);
	$m = (int)(substr($tgl,5,2));
	$tmp = substr($tgl,8,2)." ".$month[$m]." ".substr($tgl,0,4);
	if ($tipe == 1)
		{
			$tmp = $tmp." - ".$jam;
		}
	elseif ($tipe == 2)
		{
			$tmp = $tmp;
		}
	if (substr($tgl,0,4)=='0000')
	{
		return "";
	}
	else
	{
		return $tmp;
	}
}

}
?>