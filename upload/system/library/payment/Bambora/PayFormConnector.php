<?php

namespace Bambora;

interface PayFormConnector
{
	public function request($url, $post_arr);
}
