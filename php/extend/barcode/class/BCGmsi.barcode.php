<?php
/**
 * BCGmsi.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - MSI Plessey
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.0.1	8  mar	2009	Jean-Sébastien Goupil	Fix checksum 1 or 2
 * v2.0.0	23 apr	2008	Jean-Sébastien Goupil	New Version Update
 * v1.2.3b	31 dec	2005	Jean-Sébastien Goupil	Checksum separated + PHP5.1 compatible
 * v1.2.2	23 jul	2005	Jean-Sébastien Goupil	Enhance rapidity
 * v1.2.1	27 jun	2005	Jean-Sébastien Goupil	Font support added
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGmsi.barcode.php,v 1.10 2009/03/23 06:48:30 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGBarcode1D.php');

class BCGmsi extends BCGBarcode1D {
	private $checksum;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->keys = array('0','1','2','3','4','5','6','7','8','9');
		$this->code = array(
			'01010101',	/* 0 */
			'01010110',	/* 1 */
			'01011001',	/* 2 */
			'01011010',	/* 3 */
			'01100101',	/* 4 */
			'01100110',	/* 5 */
			'01101001',	/* 6 */
			'01101010',	/* 7 */
			'10010101',	/* 8 */
			'10010110'	/* 9 */
		);

		$this->setChecksum(0);
	}

	public function setChecksum($checksum) {
		$checksum = intval($checksum);
		if($checksum < 0 && $checksum > 2) {
			$checksum = 0;
		}
		$this->checksum = $checksum;
	}

	/**
	 * Draws the barcode
	 *
	 * @param resource $im
	 */
	public function draw(&$im) {
		$error_stop = false;

		// Checking if all chars are allowed
		$c = strlen($this->text);
		for($i = 0; $i < $c; $i++) {
			if(array_search($this->text[$i], $this->keys) === false) {
				$this->drawError($im, 'Char \'' . $this->text[$i] . '\' not allowed.');
				$error_stop = true;
			}
		}
		if($error_stop === false) {
			// Checksum
			$this->calculateChecksum();
			// Starting Code
			$this->drawChar($im, '10', true);
			// Chars
			$c = strlen($this->text);
			for($i = 0; $i < $c; $i++) {
				$this->drawChar($im, $this->findCode($this->text[$i]), true);
			}
			$c = count($this->checksumValue);
			for($i = 0; $i < $c; $i++) {
				$this->drawChar($im, $this->findCode($this->checksumValue[$i]), true);
			}
			// Ending Code
			$this->drawChar($im, '010', true);
			$this->drawText($im);
		}
	}

	/**
	 * Returns the maximal size of a barcode
	 *
	 * @return int[]
	 */
	public function getMaxSize() {
		$p = parent::getMaxSize();

		$textlength = 12 * strlen($this->text) * $this->scale;
		$startlength = 3 * $this->scale;
		$checksumlength = $this->checksum * 12 * $this->scale;
		$endlength = 4 * $this->scale;

		return array($p[0] + $startlength + $textlength + $checksumlength + $endlength, $p[1]);
	}

	/**
	 * Overloaded method to calculate checksum
	 */
	protected function calculateChecksum() {
		// Forming a new number
		// If the original number is even, we take all even position
		// If the original number is odd, we take all odd position
		// 123456 = 246
		// 12345 = 135
		// Multiply by 2
		// Add up all the digit in the result (270 : 2+7+0)
		// Add up other digit not used.
		// 10 - (? Modulo 10). If result = 10, change to 0
		$last_text = $this->text;
		$this->checksumValue = array();
		for($i = 0; $i < $this->checksum; $i++) {
			$new_text = '';
			$new_number = 0;
			$c = strlen($last_text);
			if($c % 2 === 0) { // Even
				$starting = 1;
			} else {
				$starting = 0;
			}
			for($j = $starting; $j < $c; $j += 2) {
				$new_text .= $last_text[$j];
			}
			$new_text = strval(intval($new_text) * 2);
			$c2 = strlen($new_text);
			for($j = 0; $j < $c2; $j++) {
				$new_number += intval($new_text[$j]);
			}
			for($j = ($starting === 0) ? 1 : 0; $j < $c; $j += 2) {
				$new_number += intval($last_text[$j]);
			}
			$new_number = (10 - $new_number % 10) % 10;
			$this->checksumValue[] = $new_number;
			$last_text .= $new_number;
		}
	}

	/**
	 * Overloaded method to display the checksum
	 */
	protected function processChecksum() {
		if($this->checksumValue === false) { // Calculate the checksum only once
			$this->calculateChecksum();
		}
		if($this->checksumValue !== false) {
			$ret = '';
			$c = count($this->checksumValue);
			for($i = 0; $i < $c; $i++) {
				$ret .= $this->keys[$this->checksumValue[$i]];
			}
			return $ret;
		}
		return false;
	}
};
?>