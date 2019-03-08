<?php
/**
 * BCGcodabar.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - Codabar
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.00	23 apr	2008	Jean-Sébastien Goupil	New Version Update + fix B and C
 * v1.2.3b	31 dec	2005	Jean-Sébastien Goupil	PHP5.1 compatible
 * v1.2.1	27 jun	2005	Jean-Sébastien Goupil	Font support added
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGcodabar.barcode.php,v 1.10 2008/07/10 04:23:26 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGBarcode1D.php');

class BCGcodabar extends BCGBarcode1D {
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->keys = array('0','1','2','3','4','5','6','7','8','9','-','$',':','/','.','+','A','B','C','D');
		$this->code = array(	// 0 added to add an extra space
			'00000110',	/* 0 */
			'00001100',	/* 1 */
			'00010010',	/* 2 */
			'11000000',	/* 3 */
			'00100100',	/* 4 */
			'10000100',	/* 5 */
			'01000010',	/* 6 */
			'01001000',	/* 7 */
			'01100000',	/* 8 */
			'10010000',	/* 9 */
			'00011000',	/* - */
			'00110000',	/* $ */
			'10001010',	/* : */
			'10100010',	/* / */
			'10101000',	/* . */
			'00111110',	/* + */
			'00110100',	/* A */
			'01010010',	/* B */
			'00010110',	/* C */
			'00011100'	/* D */
		);
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
		for($i = 0; $i < $c; $i++) {
			if(array_search($this->text[$i], $this->keys) === false) {
				$this->drawError($im, 'Char \'' . $this->text[$i] . '\' not allowed.');
				$error_stop = true;
			}
		}
		if($error_stop === false) {
			// Must start by A, B, C or D
			if($this->text[0] !== 'A' && $this->text[0] !== 'B' && $this->text[0] !== 'C' && $this->text[0] !== 'D') {
				$this->drawError($im, 'Must start by char A, B, C or D.');
				$error_stop = true;
			}
			// Must over by A, B, C or D
			$c2 = $c - 1;
			if($c2 === 0 || ($this->text[$c2] !== 'A' && $this->text[$c2] !== 'B' && $this->text[$c2] !== 'C' && $this->text[$c2] !== 'D')) {
				$this->drawError($im, 'Must end by char A, B, C or D.');
				$error_stop = true;
			}
			if($error_stop === false) {
				for($i = 0; $i < $c; $i++) {
					$this->drawChar($im, $this->findCode($this->text[$i]), true);
				}
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

		$w = 0;
		$c = strlen($this->text);
		for($i = 0; $i < $c; $i++) {
			$index = $this->findIndex($this->text[$i]);
			if($index !== false) {
				$w += 8;
				$w += substr_count($this->code[$index], '1');
			}
		}
		return array($p[0] + $w * $this->scale, $p[1]);
	}
};
?>