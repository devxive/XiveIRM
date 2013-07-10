<?php

class cologne {
	var $rules = array(
		array('/[aejiouy\xC4\xD6\xDC\xE4\xF6\xFC]/i', '0'),
		array('/[b]/i', '1'),
		array('/[p][^h]/i', '1'),
		array('/[dt]([csz])/i', '8$1'),
		array('/[dt](?<=[^csz])/i', '2$1'),
		array('/[fvw]/i', '3'),
		array('/p([h]?)/i', '3$1'),
		array('/[gkq]/i', '4'),
		array('/^c([ahkloqrux])/i', '4$1'),
		array('/([^sz])c([ahkoqux])/i', '${1}4$2'),
		array('/([^ckq]??)x/i', '${1}48'),
		array('/[l]/i', '5'),
		array('/[mn]/i', '6'),
		array('/[r]/i', '7'),
		array('/([sz])c/i', '${1}8'),
		array('/[sz\xDF]/i', '8'),
		array('/^c([^ahkloqrux]??)/i', '8$1'),
		array('/c([^ahkoqux]??)/i', '8$1'),
		array('/([ckq])x/i', '${1}8'),
		array('/[h]/i', '')
	);

	function cologne() {}

	function &getInstance() {
		static $instance;
		if(!is_object($instance) ) {
			$instance = new cologne();
		}
		return $instance;
	}

	function stringCompare($string1, $string2) {
		return $this->compare($string1, $string2);
	}

	function stringEncode($string) {
		return $this->encode($string);
	}

	function compare($string1, $string2) {
		if ($string1 == $string2 || strtolower($string1) === strtolower($string2)) {
			return "eq";
		}
		if ($this->encode($string1) === $this->encode($string2)) {
			return "si";
		}
		return false;
	}

	function encode($string) {
		$string = preg_replace('/[^a-z\xC4\xD6\xDC\xDF\xE4\xF6\xFC]/i', '', $string);
		$string = $this->applyRules($string);
		$string = $this->stripDoubles($string);
		$string = $this->stripZeros($string);
		return $string;
	}

	function applyRules($string) {
		foreach ($this->rules as $rule) {
			$string = preg_replace($rule[0], $rule[1], $string);
		}
		return $string;
	}

	function stripDoubles($string) {
		for ($i = 0; $i <= 9; $i++) {
			$string = preg_replace('/['.$i.']{2}/', $i, $string);
		}
		return $string;
	}

	function stripZeros($string) {
		$first = '';
		if ($string[0] === '0') {
			$first = '0';
			$string = substr($string, 1);
		}
		return $first.preg_replace('/[0]/', '', $string);
	}
}
?>