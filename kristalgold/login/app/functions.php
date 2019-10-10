<?php



function curl($url = '', $var = '')
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_NOBODY, false);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

function get_BIN($bin, $parameter)
{
	$binx = preg_replace('/\s+/', '', substr($bin, 0, 7));
	$get_bin = curl('https://binfind.com/api/json/'.$binx);
	$json_bin = @json_decode($get_bin, true);
	if ($parameter == 'bankname')
	{
		if (isset($json_bin['bank']))
		{
			return $json_bin['bank'];
		}
		else
		{
			return false;
		}
	}
	else
	{
		return $json_bin;
	}

}