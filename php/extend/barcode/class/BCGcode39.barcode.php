<?php
/**
 * BCGcode39.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - Code 39
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.00	23 apr	2008	Jean-Sébastien Goupil	New Version Update
 * v1.2.3b	30 dec	2005	Jean-Sébastien Goupil	Checksum separated + PHP5.1 compatible
 * v1.2.1	27 jun	2005	Jean-Sébastien Goupil	Font support added
 * v1.01	7  jul  2004	Jean-Sebastien Goupil	Correction + Sign
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGcode39.barcode.php,v 1.9 2008/07/10 04:23:26 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGBarcode1D.php');

class BCGcode39 extends BCGBarcode1D {
	protected $starting, $ending;
	protected $checksum;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->starting = $this->ending = 43;
		$this->keys = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','-','.',' ','$','/','+','%','*');
		$this->code = array(	// 0 added to add an extra space
			'0001101000',	/* 0 */
			'1001000010',	/* 1 */
			'0011000010',	/* 2 */
			'1011000000',	/* 3 */
			'0001100010',	/* 4 */
			'1001100000',	/* 5 */
			'0011100000',	/* 6 */
			'0001001010',	/* 7 */
			'1001001000',	/* 8 */
			'0011001000',	/* 9 */
			'1000010010',	/* A */
			'0010010010',	/* B */
			'1010010000',	/* C */
			'0000110010',	/* D */
			'1000110000',	/* E */
			'0010110000',	/* F */
			'0000011010',	/* G */
			'1000011000',	/* H */
			'0010011000',	/* I */
			'0000111000',	/* J */
			'1000000110',	/* K */
			'0010000110',	/* L */
			'1010000100',	/* M */
			'0000100110',	/* N */
			'1000100100',	/* O */
			'0010100100',	/* P */
			'0000001110',	/* Q */
			'1000001100',	/* R */
			'0010001100',	/* S */
			'0000101100',	/* T */
			'1100000010',	/* U */
			'0110000010',	/* V */
			'1110000000',	/* W */
			'0100100010',	/* X */
			'1100100000',	/* Y */
			'0110100000',	/* Z */
			'0100001010',	/* - */
			'1100001000',	/* . */
			'0110001000',	/*   */
			'0101010000',	/* $ */
			'0101000100',	/* / */
			'0100010100',	/* + */
			'0001010100',	/* % */
			'0100101000'	/* * */
		);

		$this->setChecksum(false);
	}

	public function setChecksum($checksum) {
		$this->checksum = (bool)$checksum;
	}

	/**
	 * Saves Text
	 *
	 * @param string $text
	 */
	public function parse($text) {
		parent::parse(strtoupper($text));	// Only Capital Letters are Allowed
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
		for ($i = 0; $i < $c; $i++) {
			if (array_search($this->text[$i], $this->keys) === false) {
				$this->drawError($im, 'Char \'' . $this->text[$i] . '\' not allowed.');
				$error_stop = true;
			}
		}
		if ($error_stop === false) {
			// The * is not allowed
			if (strpos($this->text, '*') !== false) {
				$this->drawError($im, 'Char \'*\' not allowed.');
				$error_stop = true;
			}
			if ($error_stop === false) {
				// Starting *
				$this->drawChar($im, $this->code[$this->starting], true);
				// Chars
				for ($i = 0; $i < $c; $i++) {
					$this->drawChar($im, $this->findCode($this->text[$i]), true);
				}
				// Checksum (rarely used)
				if ($this->checksum === true) {
					$this->calculateChecksum();
					$this->drawChar($im, $this->code[$this->checksumValue % 43], true);
				}
				// Ending *
				$this->drawChar($im, $this->code[$this->ending], true);
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

		$textlength = 13 * strlen($this->text) * $this->scale;
		$startlength = 13 * $this->scale;
		$checksumlength = 0;
		if ($this->checksum === true) {
			$checksumlength = 13 * $this->scale;
		}
		$endlength = 13 * $this->scale;
		return array($p[0] + $startlength + $textlength + $checksumlength + $endlength, $p[1]);
	}

	/**
	 * Overloaded method to calculate checksum
	 */
	protected function calculateChecksum() {
		$this->checksumValue = 0;
		$c = strlen($this->text);
		for ($i = 0; $i < $c; $i++) {
			$this->checksumValue += $this->findIndex($this->text[$i]);
		}
		$this->checksumValue = $this->checksumValue % 43;
	}

	/**
	 * Overloaded method to display the checksum
	 */
	protected function processChecksum() {
		if ($this->checksumValue === false) { // Calculate the checksum only once
			$this->calculateChecksum();
		}
		if ($this->checksumValue !== false) {
			return $this->keys[$this->checksumValue];
		}
		return false;
	}
};
?>