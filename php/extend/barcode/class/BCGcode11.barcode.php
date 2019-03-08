<?php
/**
 * BCGcode11.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - Code 11
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.00	23 apr	2008	Jean-Sébastien Goupil	New Version Update
 * v1.2.3b	30 dec	2005	Jean-Sébastien Goupil	Checksum separated + PHP5.1 compatible + Error in checksum
 * v1.2.2	23 jul	2005	Jean-Sébastien Goupil	WS Fix
 * v1.2.1	27 jun	2005	Jean-Sébastien Goupil	Font support added
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGcode11.barcode.php,v 1.10 2008/07/10 04:23:26 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGBarcode1D.php');

class BCGcode11 extends BCGBarcode1D {
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->keys = array('0','1','2','3','4','5','6','7','8','9','-');
		$this->code = array(	// 0 added to add an extra space
			'000010',	/* 0 */
			'100010',	/* 1 */
			'010010',	/* 2 */
			'110000',	/* 3 */
			'001010',	/* 4 */
			'101000',	/* 5 */
			'011000',	/* 6 */
			'000110',	/* 7 */
			'100100',	/* 8 */
			'100000',	/* 9 */
			'001000'	/* - */
		);
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
				$this->drawError($im,'Char \'' . $this->text[$i] . '\' not allowed.');
				$error_stop = true;
			}
		}
		if($error_stop === false) {
			// Starting Code
			$this->drawChar($im, '001100', true);
			// Chars
			for($i = 0; $i < $c; $i++) {
				$this->drawChar($im, $this->findCode($this->text[$i]), true);
			}
			// Checksum
			$this->calculateChecksum();
			$c = count($this->checksumValue);
			for($i = 0; $i < $c; $i++) {
				$this->drawChar($im, $this->code[$this->checksumValue[$i]], true);
			}
			// Ending Code
			$this->drawChar($im, '00110', true);
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

		$w = 0;
		$c = strlen($this->text);
		for($i = 0; $i < $c; $i++) {
			$index = $this->findIndex($this->text[$i]);
			if($index !== false) {
				$w += 6;
				$w += substr_count($this->code[$index], '1');
			}
		}
		$startlength = 8 * $this->scale;
		$textlength = $w * $this->scale;
		// We take the max length possible for checksums (it is 7 or 8...)
		$checksumlength = 8 * $this->scale;
		if($c >= 10) {
			$checksumlength += 8 * $this->scale;
		}
		$endlength = 7 * $this->scale;
		return array($p[0] + $startlength + $textlength + $checksumlength + $endlength, $p[1]);
	}

	/**
	 * Overloaded method to calculate checksum
	 */
	protected function calculateChecksum() {
		// Checksum
		// First CheckSUM "C"
		// The "C" checksum character is the modulo 11 remainder of the sum of the weighted
		// value of the data characters. The weighting value starts at "1" for the right-most
		// data character, 2 for the second to last, 3 for the third-to-last, and so on up to 20.
		// After 10, the sequence wraps around back to 1.

		// Second CheckSUM "K"
		// Same as CheckSUM "C" but we count the CheckSum "C" at the end
		// After 9, the sequence wraps around back to 1.
		$sequence_multiplier = array(10, 9);
		$temp_text = $this->text;
		$this->checksumValue = array();
		for($z = 0; $z < 2; $z++) {
			$c = strlen($temp_text);
			// We don't display the K CheckSum if the original text had a length less than 10
			if($c <= 10 && $z === 1) {
				break;
			}
			$checksum = 0;
			for($i = $c, $j = 0; $i > 0; $i--, $j++) {
				$multiplier = $i % $sequence_multiplier[$z];
				if($multiplier === 0) {
					$multiplier = $sequence_multiplier[$z];
				}
				$checksum += $this->findIndex($temp_text[$j]) * $multiplier;
			}
			$this->checksumValue[$z] = $checksum % 11;
			$temp_text .= $this->keys[$this->checksumValue[$z]];
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