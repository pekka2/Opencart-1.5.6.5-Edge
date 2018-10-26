<?php

namespace Bambora;

class PayFormCurl implements PayFormConnector
{
	public function request($url, $post_arr)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, PayForm::API_URL . "/" . $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen(json_encode($post_arr))));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_arr));
		$curl_response = curl_exec($ch);

		if (!$curl_response)
			throw new PayFormException('PayFormCurl::request - curl error: ' . curl_error($ch) . ' (curl error code: ' . curl_errno($ch) . ')', 3);

		curl_close ($ch);

		return $curl_response;
	}
}
