<?php
/**
 * BCGs25.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - Standard 2 of 5
 *
 * NOTE: It is really tough to read this barcode !
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.00	23 apr	2008	Jean-S�bastien Goupil	New Version Update
 * v1.2.3b	31 dec	2005	Jean-S�bastien Goupil	Checksum separated + PHP5.1 compatible
 * v1.2.2	23 jul	2005	Jean-S�bastien Goupil	Correct Checksum
 * v1.2.1	27 jun	2005	Jean-S�bastien Goupil	Font support added
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGs25.barcode.php,v 1.9 2008/07/10 04:23:26 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGBarcode1D.php');

class BCGs25 extends BCGBarcode1D {
	private $checksum;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->keys = array('0','1','2','3','4','5','6','7','8','9');
		$this->code = array(
			'0000202000',	/* 0 */
			'2000000020',	/* 1 */
			'0020000020',	/* 2 */
			'2020000000',	/* 3 */
			'0000200020',	/* 4 */
			'2000200000',	/* 5 */
			'0020200000',	/* 6 */
			'0000002020',	/* 7 */
			'2000002000',	/* 8 */
			'0020002000'	/* 9 */
		);

		$this->setChecksum(false);
	}

	public function setChecksum($checksum) {
		$this->checksum = (bool)$checksum;
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
			// Must be even
			if($c % 2 !== 0 && $this->checksum === false) {
				$this->drawError($im, 's25 must be even if checksum is false.');
				$error_stop = true;
			} elseif($c % 2 === 0 && $this->checksum === true) {
				$this->drawError($im, 's25 must be odd if checksum is true.');
				$error_stop = true;
			}
			if($error_stop === false) {
				$temp_text = $this->text;
				// Checksum
				if($this->checksum === true) {
					$this->calculateChecksum();
					$temp_text .= $this->keys[$this->checksumValue];
				}
				// Starting Code
				$this->drawChar($im, '101000', true);
				// Chars
				$c = strlen($temp_text);
				for($i = 0; $i < $c; $i++) {
					$this->drawChar($im, $this->findCode($temp_text[$i]), true);
				}
				// Ending Code
				$this->drawChar($im, '10001', true);
				$this->drawText($im);
			}
		}
	}

	/**
	 * Returns the maximal size of a barcode
	 *
	 * @return int[]
	 */
	public function getMaxSize() {
		$p = parent::getMaxSize();

		$c = strlen($this->text);
		$startlength = 8 * $this->scale;
		$textlength = $c * 14 * $this->scale;
		$checksumlength = 0;
		if($c % 2 !== 0) {
			$checksumlength = 14 * $this->scale;
		}
		$endlength = 7 * $this->scale;

		return array($p[0] + $startlength + $textlength + $checksumlength + $endlength, $p[1]);
	}

	/**
	 * Overloaded method to calculate checksum
	 */
	protected function calculateChecksum() {
		// Calculating Checksum
		// Consider the right-most digit of the message to be in an "even" position,
		// and assign odd/even to each character moving from right to left
		// Even Position = 3, Odd Position = 1
		// Multiply it by the number
		// Add all of that and do 10-(?mod10)
		$even = true;
		$this->checksumValue = 0;
		$c = strlen($this->text);
		for($i = $c; $i > 0; $i--) {
			if($even === true) {
				$multiplier = 3;
				$even = false;
			} else {
				$multiplier = 1;
				$even = true;
			}
			$this->checksumValue += $this->keys[$this->text[$i - 1]] * $multiplier;
		}
		$this->checksumValue = (10 - $this->checksumValue % 10) % 10;
	}

	/**
	 * Overloaded method to display the checksum
	 */
	protected function processChecksum() {
		if($this->checksumValue === false) { // Calculate the checksum only once
			$this->calculateChecksum();
		}
		if($this->checksumValue !== false) {
			return $this->keys[$this->checksumValue];
		}
		return false;
	}
};
?>