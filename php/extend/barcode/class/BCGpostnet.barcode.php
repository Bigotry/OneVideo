<?php
/**
 * BCGpostnet.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - PostNet
 *
 * ################ NOT TESTED ################
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.0.1	8  mar	2009	Jean-Sébastien Goupil	Fix padding for the barcode
 * v2.0.0	23 apr	2008	Jean-Sébastien Goupil	New Version Update
 * v1.2.3b	31 dec	2005	Jean-Sébastien Goupil	PHP5.1 compatible
 * v1.2.1	27 jun	2005	Jean-Sébastien Goupil	Font support added + Redesign output
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGpostnet.barcode.php,v 1.13 2009/03/23 06:48:30 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGBarcode1D.php');

class BCGpostnet extends BCGBarcode1D {
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->keys = array('0','1','2','3','4','5','6','7','8','9');
		$this->code = array(
			'11000',	/* 0 */
			'00011',	/* 1 */
			'00101',	/* 2 */
			'00110',	/* 3 */
			'01001',	/* 4 */
			'01010',	/* 5 */
			'01100',	/* 6 */
			'10001',	/* 7 */
			'10010',	/* 8 */
			'10100'		/* 9 */
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
				$this->drawError($im, 'Char \'' . $this->text[$i] . '\' not allowed.');
				$error_stop = true;
			}
		}
		if($error_stop === false) {
			// Must contain 5, 9 or 11 chars
			if($c !== 5 && $c !== 9 && $c !== 11) {
				$this->drawError($im, 'Must contain 5, 9 or 11 chars.');
				$error_stop = true;
			}
			if($error_stop === false) {
				// Checksum
				$checksum = 0;
				for($i = 0; $i < $c; $i++) {
					$checksum += intval($this->text[$i]);
				}
				$checksum = 10 - ($checksum % 10);

				// Starting Code
				$this->drawChar($im, '1');
				// Code
				for($i = 0; $i < $c; $i++) {
					$this->drawChar($im, $this->findCode($this->text[$i]));
				}
				// Checksum
				$this->drawChar($im, $this->findCode($checksum));
				//Ending Code
				$this->drawChar($im, '1');
				$this->drawText($im);
			}
		}

	}

	/**
	 * Returns the maximal size of a barcode
	 *
	 * @return int
	 */
	public function getMaxSize() {
		$p = parent::getMaxSize();

		$c = strlen($this->text);
		$startlength = 6 * $this->scale;
		$textlength = $c * 5 * 6 * $this->scale;
		$checksumlength = 5 * 6 * $this->scale;
		$endlength = 6 * $this->scale;
		// We remove the white on the right
		$removelength = - 3 * $this->scale;

		return array($p[0] + $startlength + $textlength + $checksumlength + $endlength + $removelength, $p[1]);
	}

	/**
	 * Overloaded method for drawing special barcode
	 *
	 * @param resource $im
	 * @param string $code
	 * @param boolean $last
	 */
	protected function drawChar(&$im, $code, $last = false) {
		$c = strlen($code);
		for($i = 0; $i < $c; $i++) {
			if($code[$i] === '0') {
				$posY = $this->thickness / 2;
			} else {
				$posY = 0;
			}

			$this->drawFilledRectangle($im, $this->positionX, $posY, $this->positionX + 2, $this->thickness, BCGBarcode::COLOR_FG);

			$this->positionX += 2 * 3;
		}
	}
};
?>