<?php
/**
 * BCGcode93.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - Code 93
 *
 * !! Warning !!
 * If you display the checksum on the barcode, you may obtain
 * some garbage since some characters are not displayable.
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.00	23 apr	2008	Jean-Sébastien Goupil	New Version Update
 * v1.2.3b	30 dec	2005	Jean-Sébastien Goupil	Checksum separated + PHP5.1 compatible
 * v1.2.1	27 jun	2005	Jean-Sébastien Goupil	Font support added
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGcode93.barcode.php,v 1.9 2008/07/10 04:23:26 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGBarcode1D.php');

class BCGcode93 extends BCGBarcode1D {
	const EXTENDED_1 = 43;
	const EXTENDED_2 = 44;
	const EXTENDED_3 = 45;
	const EXTENDED_4 = 46;

	private $starting, $ending;
	private $indcheck, $data;
	private $errorText;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->starting = $this->ending = 47; /* * */
		$this->keys = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','-','.',' ','$','/','+','%','($)','(%)','(/)','(+)','(*)');
		$this->code = array(
			'020001',	/* 0 */
			'000102',	/* 1 */
			'000201',	/* 2 */
			'000300',	/* 3 */
			'010002',	/* 4 */
			'010101',	/* 5 */
			'010200',	/* 6 */
			'000003',	/* 7 */
			'020100',	/* 8 */
			'030000',	/* 9 */
			'100002',	/* A */
			'100101',	/* B */
			'100200',	/* C */
			'110001',	/* D */
			'110100',	/* E */
			'120000',	/* F */
			'001002',	/* G */
			'001101',	/* H */
			'001200',	/* I */
			'011001',	/* J */
			'021000',	/* K */
			'000012',	/* L */
			'000111',	/* M */
			'000210',	/* N */
			'010011',	/* O */
			'020010',	/* P */
			'101001',	/* Q */
			'101100',	/* R */
			'100011',	/* S */
			'100110',	/* T */
			'110010',	/* U */
			'111000',	/* V */
			'001011',	/* W */
			'001110',	/* X */
			'011010',	/* Y */
			'012000',	/* Z */
			'010020',	/* - */
			'200001',	/* . */
			'200100',	/*   */
			'210000',	/* $ */
			'001020',	/* / */
			'002010',	/* + */
			'100020',	/* % */
			'010110',	/*($)*/
			'201000',	/*(%)*/
			'200010',	/*(/)*/
			'011100',	/*(+)*/
			'000030'	/*(*)*/
		);
		$this->errorText = '';
	}

	/**
	 * Saves Text
	 *
	 * @param string $text
	 */
	public function parse($text) {
		$this->text = $text;

		$data = array();
		$indcheck = array();

		$this->errorText = ''; // Reset Error
		$c = strlen($this->text);
		for($i = 0; $i < $c; $i++) {
			$pos = array_search($this->text[$i], $this->keys);
			if($pos === false) {
				// Search in extended?
				$extended = $this->getExtendedVersion($this->text[$i]);
				if($extended === false) {
					$this->errorText .= 'Char \'' . $this->text[$i] . '\' not allowed.'."\n";
				} else {
					$extc = strlen($extended);
					for($j = 0; $j < $extc; $j++) {
						$v = $extended[$j];
						if($v === '$') {
							$indcheck[] = self::EXTENDED_1;
							$data[] = $this->code[self::EXTENDED_1];
						} elseif($v === '%') {
							$indcheck[] = self::EXTENDED_2;
							$data[] = $this->code[self::EXTENDED_2];
						} elseif($v === '/') {
							$indcheck[] = self::EXTENDED_3;
							$data[] = $this->code[self::EXTENDED_3];
						} elseif($v === '+') {
							$indcheck[] = self::EXTENDED_4;
							$data[] = $this->code[self::EXTENDED_4];
						} else {
							$pos2 = array_search($v, $this->keys);
							$indcheck[] = $pos2;
							$data[] = $this->code[$pos2];
						}
					}
				}
			} else {
				$indcheck[] = $pos;
				$data[] = $this->code[$pos];
			}
		}

		$this->setData(array($indcheck, $data));
	}

	/**
	 * Draws the barcode
	 *
	 * @param resource $im
	 */
	public function draw(&$im) {
		if(!empty($this->errorText)) {
			$error = explode("\n", trim($this->errorText));
			$c = count($error);
			for($i = 0; $i < $c; $i++) {
				$this->drawError($im, $error[$i]);
			}
		} else {
			$c = count($this->data);
			if($c === 0) {
				$this->drawError($im, 'No text has been entered.');
			} else {
				// Starting *
				$this->drawChar($im, $this->code[$this->starting], true);
				for($i = 0; $i < $c; $i++) {
					$this->drawChar($im, $this->data[$i], true);
				}
				// Checksum
				$c = count($this->checksumValue);
				for($i = 0; $i < $c; $i++) {
					$this->drawChar($im, $this->code[$this->checksumValue[$i]], true);
				}
				// Ending *
				$this->drawChar($im, $this->code[$this->ending], true);
				// Draw a Final Bar
				$this->drawChar($im, '0', true);
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

		$startlength = 9 * $this->scale;
		$textlength = 9 * count($this->data) * $this->scale;
		$checksumlength = 2 * 9 * $this->scale;
		$endlength = 9 * $this->scale + $this->scale; // + final bar
		return array($p[0] + $startlength + $textlength + $checksumlength + $endlength, $p[1]);
	}

	/**
	 * Overloaded method to calculate checksum
	 */
	protected function calculateChecksum() {
		// Checksum
		// First CheckSUM "C"
		// The "C" checksum character is the modulo 47 remainder of the sum of the weighted
		// value of the data characters. The weighting value starts at "1" for the right-most
		// data character, 2 for the second to last, 3 for the third-to-last, and so on up to 20.
		// After 20, the sequence wraps around back to 1.

		// Second CheckSUM "K"
		// Same as CheckSUM "C" but we count the CheckSum "C" at the end
		// After 15, the sequence wraps around back to 1.
		$sequence_multiplier = array(20, 15);
		$this->checksumValue = array();
		$indcheck = $this->indcheck;
		for($z = 0; $z < 2; $z++) {
			$checksum = 0;
			for($i = count($indcheck), $j = 0; $i > 0; $i--, $j++) {
				$multiplier = $i % $sequence_multiplier[$z];
				if($multiplier === 0) {
					$multiplier = $sequence_multiplier[$z];
				}
				$checksum += $indcheck[$j] * $multiplier;
			}
			$this->checksumValue[$z] = $checksum % 47;
			$indcheck[] = $this->checksumValue[$z];
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

	/**
	 * Saves data into the classes.
	 *
	 * This method will save data, calculate real column number
	 * (if -1 was selected), the real error level (if -1 was
	 * selected)... It will add Padding to the end and generate
	 * the error codes.
	 *
	 * @param array $data
	 */
	private function setData($data) {
		$this->indcheck = $data[0];
		$this->data = $data[1];
		$this->calculateChecksum();

	}

	private function getExtendedVersion($char) {
		$o = ord($char);
		if($o === 0)
			return '%U';
		elseif($o >= 1 && $o <= 26)
			return '$' . chr($o + 64);
		elseif(($o >= 33 && $o <= 44) || $o === 47 || $o === 48)
			return '/' . chr($o + 32);
		elseif($o >= 97 && $o <= 122)
			return '+' . chr($o - 32);
		elseif($o >= 27 && $o <= 31)
			return '%' . chr($o + 38);
		elseif($o >= 59 && $o <= 63)
			return '%' . chr($o + 11);
		elseif($o >= 91 && $o <= 95)
			return '%' . chr($o - 16);
		elseif($o >= 123 && $o <= 127)
			return '%' . chr($o - 43);
		elseif($o === 64)
			return '%V';
		elseif($o === 96)
			return '%W';
		elseif($o > 127)
			return false;
		else
			return $char;
	}
};
?>