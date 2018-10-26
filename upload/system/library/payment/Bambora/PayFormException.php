<?php

/*
	Exception code 2: Cannot parse JSON
	Exception code 3: Connection error / CURL error
	Exception code 4: MAC authentication failed
	Exception code 5: Not enough return values given - invalid return
*/

namespace Bambora;

class PayFormException extends \Exception {}
