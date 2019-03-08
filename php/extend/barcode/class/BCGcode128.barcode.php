<?php
/**
 * BCGcode128.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - Code 128, A, B, C
 *
 * # Code C Working properly only on PHP4 or PHP5.0.3+ due to bug :
 * http://bugs.php.net/bug.php?id=28862
 *
 * !! Warning !!
 * If you display the checksum on the label, you may obtain
 * some garbage since some characters are not displayable.
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.0.1	8  mar	2009	Jean-Sébastien Goupil	Fix Code 128 C
 * v2.00	23 apr	2008	Jean-Sébastien Goupil	New Version Update
 * v1.2.3pl2	27 sep	2006	Jean-Sébastien Goupil	There were some errors dealing with C table
 * v1.2.3b	30 dec	2005	Jean-Sébastien Goupil	Checksum separated + PHP5.1 compatible
 * v1.2.1	27 jun	2005	Jean-Sébastien Goupil	Font support added + Correct bug if passing C to another code
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGcode128.barcode.php,v 1.16 2009/03/23 06:48:30 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGBarcode1D.php');

class BCGcode128 extends BCGBarcode1D {
	const KEYA_FNC3 = 96;
	const KEYA_FNC2 = 97;
	const KEYA_SHIFT = 98;
	const KEYA_CODEC = 99;
	const KEYA_CODEB = 100;
	const KEYA_FNC4 = 101;
	const KEYA_FNC1 = 102;

	const KEYB_FNC3 = 96;
	const KEYB_FNC2 = 97;
	const KEYB_SHIFT = 98;
	const KEYB_CODEC = 99;
	const KEYB_FNC4 = 100;
	const KEYB_CODEA = 101;
	const KEYB_FNC1 = 102;

	const KEYC_CODEB = 100;
	const KEYC_CODEA = 101;
	const KEYC_FNC1 = 102;

	const KEY_STARTA = 103;
	const KEY_STARTB = 104;
	const KEY_STARTC = 105;

	const KEY_STOP = 106;

	protected $keysA, $keysB, $keysC;
	private $starting_text;
	private $indcheck, $data;
	private $tilde;
	private $errorText;

	private $shift;
	private $latch;
	private $fnc;

	/**
	 * Constructor
	 *
	 * @param char $start
	 */
	public function __construct($start = 'B') {
		parent::__construct();

		/* CODE 128 A */
		$this->keysA = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_';
		for($i = 0; $i < 32; $i++) {
			$this->keysA .= chr($i);
		}

		/* CODE 128 B */
		$this->keysB = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~'.chr(127);

		/* CODE 128 C */
		$this->keysC = '0123456789';

		$this->code = array(
			'101111',	/* 00 */
			'111011',	/* 01 */
			'111110',	/* 02 */
			'010112',	/* 03 */
			'010211',	/* 04 */
			'020111',	/* 05 */
			'011102',	/* 06 */
			'011201',	/* 07 */
			'021101',	/* 08 */
			'110102',	/* 09 */
			'110201',	/* 10 */
			'120101',	/* 11 */
			'001121',	/* 12 */
			'011021',	/* 13 */
			'011120',	/* 14 */
			'002111',	/* 15 */
			'012011',	/* 16 */
			'012110',	/* 17 */
			'112100',	/* 18 */
			'110021',	/* 19 */
			'110120',	/* 20 */
			'102101',	/* 21 */
			'112001',	/* 22 */
			'201020',	/* 23 */
			'200111',	/* 24 */
			'210011',	/* 25 */
			'210110',	/* 26 */
			'201101',	/* 27 */
			'211001',	/* 28 */
			'211100',	/* 29 */
			'101012',	/* 30 */
			'101210',	/* 31 */
			'121010',	/* 32 */
			'000212',	/* 33 */
			'020012',	/* 34 */
			'020210',	/* 35 */
			'001202',	/* 36 */
			'021002',	/* 37 */
			'021200',	/* 38 */
			'100202',	/* 39 */
			'120002',	/* 40 */
			'120200',	/* 41 */
			'001022',	/* 42 */
			'001220',	/* 43 */
			'021020',	/* 44 */
			'002012',	/* 45 */
			'002210',	/* 46 */
			'022010',	/* 47 */
			'202010',	/* 48 */
			'100220',	/* 49 */
			'120020',	/* 50 */
			'102002',	/* 51 */
			'102200',	/* 52 */
			'102020',	/* 53 */
			'200012',	/* 54 */
			'200210',	/* 55 */
			'220010',	/* 56 */
			'201002',	/* 57 */
			'201200',	/* 58 */
			'221000',	/* 59 */
			'203000',	/* 60 */
			'110300',	/* 61 */
			'320000',	/* 62 */
			'000113',	/* 63 */
			'000311',	/* 64 */
			'010013',	/* 65 */
			'010310',	/* 66 */
			'030011',	/* 67 */
			'030110',	/* 68 */
			'001103',	/* 69 */
			'001301',	/* 70 */
			'011003',	/* 71 */
			'011300',	/* 72 */
			'031001',	/* 73 */
			'031100',	/* 74 */
			'130100',	/* 75 */
			'110003',	/* 76 */
			'302000',	/* 77 */
			'130001',	/* 78 */
			'023000',	/* 79 */
			'000131',	/* 80 */
			'010031',	/* 81 */
			'010130',	/* 82 */
			'003101',	/* 83 */
			'013001',	/* 84 */
			'013100',	/* 85 */
			'300101',	/* 86 */
			'310001',	/* 87 */
			'310100',	/* 88 */
			'101030',	/* 89 */
			'103010',	/* 90 */
			'301010',	/* 91 */
			'000032',	/* 92 */
			'000230',	/* 93 */
			'020030',	/* 94 */
			'003002',	/* 95 */
			'003200',	/* 96 */
			'300002',	/* 97 */
			'300200',	/* 98 */
			'002030',	/* 99 */
			'003020',	/* 100*/
			'200030',	/* 101*/
			'300020',	/* 102*/
			'100301',	/* 103*/
			'100103',	/* 104*/
			'100121',	/* 105*/
			'122000'	/*STOP*/
		);
		$this->errorText = '';
		$this->setStart($start);
		$this->setTilde(true);

		// Latches and Shifts
		$this->latch = array(
			array(null,			self::KEYA_CODEB,	self::KEYA_CODEC),
			array(self::KEYB_CODEA,		null,			self::KEYB_CODEC),
			array(self::KEYC_CODEA,		self::KEYC_CODEB,	null)
		);
		$this->shift = array(
			array(null,			self::KEYA_SHIFT),
			array(self::KEYB_SHIFT,		null)
		);
		$this->fnc = array(
			array(self::KEYA_FNC1,		self::KEYA_FNC2,	self::KEYA_FNC3,	self::KEYA_FNC4),
			array(self::KEYB_FNC1,		self::KEYB_FNC2,	self::KEYB_FNC3,	self::KEYB_FNC4),
			array(self::KEYC_FNC1,		null,			null,			null)
		);
	}

	/**
	 * Specifies the start code. Can be 'A', 'B' or 'C'.
	 *  - Table A: Capitals + ASCII 0-31 + punct
	 *  - Table B: Capitals + LowerCase + punct
	 *  - Table C: Numbers
	 *
	 * The default table selected is B.
	 *
	 * @param string $table
	 */
	public function setStart($table) {
		// B is the default
		if($table !== 'A' && $table !== 'C') $table = 'B';
		$this->starting_text = $table;
	}

	/**
	 * Accepts tilde to be process as a special character.
	 * If true, you can do this:
	 *  - ~~	: to make ONE tilde
	 *  - ~Fx	: to insert FCNx. x is equal from 1 to 4.
	 *
	 * @param boolean $accept
	 */
	public function setTilde($accept) {
		$this->tilde = (bool)$accept;
	}

	/**
	 * Saves Text
	 *
	 * @param string $text
	 */
	public function parse($text) {
		$this->text = $text;
		$this->errorText = ''; // Reset Error
		$seq = $this->getSequence($text);
		if($seq !== '') {
			$bitstream = $this->createBinaryStream($text, $seq);
			$this->setData($bitstream);
		}
	}

	/**
	 * Draws the barcode
	 *
	 * @param resource $im
	 */
	public function draw(&$im) {
		if(!empty($this->errorText)) {
			$this->drawError($im, $this->errorText);
		} else {
			$c = count($this->data);
			if($c === 0) {
				$this->drawError($im, 'No text has been entered.');
			} else {
				for($i = 0; $i < $c; $i++) {
					$this->drawChar($im, $this->data[$i], true);
				}
				$this->drawChar($im, '1', true);
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

		// Contains start + text + checksum + stop
		$textlength = count($this->data) * 11 * $this->scale;
		$endlength = 2 * $this->scale; // + final bar

		return array($p[0] + $textlength + $endlength, $p[1]);
	}

	/**
	 * Overloaded method to calculate checksum
	 */
	protected function calculateChecksum() {
		// Checksum
		// First Char (START)
		// + Starting with the first data character following the start character,
		// take the value of the character (between 0 and 102, inclusive) multiply
		// it by its character position (1) and add that to the running checksum.
		// Modulated 103
		$this->checksumValue = $this->indcheck[0];
		$c = count($this->indcheck);
		for($i = 1; $i < $c; $i++) {
			$this->checksumValue += $this->indcheck[$i] * $i;
		}

		$this->checksumValue = $this->checksumValue % 103;
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

	/**
	 * Depending on the $text, it will return the correct
	 * sequence to encode the text.
	 *
	 * @param string $text
	 * @return string
	 */
	private function getSequence(&$text) {
		$e = 10000;
		$latLen = array(
			array(0, 1, 1),
			array(1, 0, 1),
			array(1, 1, 0)
		);
		$shftLen = array(
			array($e, 1, $e),
			array(1, $e, $e),
			array($e, $e, $e)
		);
		$charSiz = array(2, 2, 1);

		$startA = $e;
		$startB = $e;
		$startC = $e;
		if($this->starting_text === 'A') $startA = 0;
		if($this->starting_text === 'B') $startB = 0;
		if($this->starting_text === 'C') $startC = 0;

		$curLen = array($startA, $startB, $startC);
		$curSeq = array(null, null, null);

		$nextNumber = false;

		$x = 0;
		$xLen = strlen($text);
		for($x = 0; $x < $xLen; $x++) {
			$input = $text[$x];

			// 1.
			for($i = 0; $i < 3; $i++) {
				for($j = 0; $j < 3; $j++) {
					if(($curLen[$i] + $latLen[$i][$j]) < $curLen[$j]) {
						$curLen[$j] = $curLen[$i] + $latLen[$i][$j];
						$curSeq[$j] = $curSeq[$i] . $j;
					}
				}
			}

			// 2.
			$nxtLen = array($e, $e, $e);
			$nxtSeq = array();

			// 3.
			$flag = false;
			$posArray = array();
			// Special case, we do have a tilde and we process them
			if($this->tilde && $input === '~') {
				// Check next input
				if(isset($text[$x + 1])) {
					// Do we have a tilde?
					if($text[$x + 1] === '~') {
						// We simply skip a tilde
						$posArray[] = 1;
						$x++;
					} elseif($text[$x + 1] === 'F') {
						// Do we have a number after?
						if(isset($text[$x + 2])) {
							$v = intval($text[$x + 2]);
							if($v >= 1 && $v <= 4) {
								$posArray[] = 0;
								$posArray[] = 1;
								if($v === 1) {
									$posArray[] = 2;
								}
								$x += 2;
								$flag = true;
							} else {
								$this->errorText = 'Bad ~F.  You must provide 1 following number.';
								return '';
							}
						} else {
							$this->errorText = 'Bad ~F.  You must provide 1 following number.';
							return '';
						}
					} else {
						// Wrong code
						$this->errorText = 'Wrong code after the ~.';
						return '';
					}
				} else {
					// Wrong code
					$this->errorText = 'Wrong code after the ~.';
					return '';
				}
			} else {
				$pos = strpos($this->keysA, $input);
				if($pos !== false) {
					$posArray[] = 0;
				}
				$pos = strpos($this->keysB, $input);
				if($pos !== false) {
					$posArray[] = 1;
				}
				$pos = strpos($this->keysC, $input);
				// Do we have the next char a number?? OR a ~F1
				if($nextNumber || ($pos !== false && isset($text[$x + 1]) && strpos($this->keysC, $text[$x + 1]) !== false)) {
					$nextNumber = !$nextNumber;
					$posArray[] = 2;
				}
			}

			$c = count($posArray);
			for($i = 0; $i < $c; $i++) {
				if(($curLen[$posArray[$i]] + $charSiz[$posArray[$i]]) < $nxtLen[$posArray[$i]]) {
					$nxtLen[$posArray[$i]] = $curLen[$posArray[$i]] + $charSiz[$posArray[$i]];
					$nxtSeq[$posArray[$i]] = $curSeq[$posArray[$i]] . '.';
				}
				for($j = 0; $j < 2; $j++) {
					if($j === $posArray[$i]) continue;
					if(($curLen[$j] + $shftLen[$j][$posArray[$i]] + $charSiz[$posArray[$i]]) < $nxtLen[$j]) {
						$nxtLen[$j] = $curLen[$j] + $shftLen[$j][$posArray[$i]] + $charSiz[$posArray[$i]];
						$nxtSeq[$j] = $curSeq[$j] . chr($posArray[$i] + 65) . '.';
					}
				}
			}
			if($c === 0) {
				// We found an unsuported character
				$this->errorText = 'Character ' .  $input . ' not supported.';
				return '';
			}

			if($flag) {
				for($i = 0; $i < 5; $i++) {
					if(isset($nxtSeq[$i])) {
						$nxtSeq[$i] .= 'F';
					}
				}
			}

			// 4.
			for($i = 0; $i < 3; $i++) {
				$curLen[$i] = $nxtLen[$i];
				if(isset($nxtSeq[$i])) {
					$curSeq[$i] = $nxtSeq[$i];
				}
			}
		}

		// Every curLen under $e are possible but we take the smallest !
		$m = $e;
		$k = -1;
		for($i = 0; $i < 3; $i++) {
			if($curLen[$i] < $m) {
				$k = $i;
				$m = $curLen[$i];
			}
		}
		if($k === -1) {
			return '';
		}

		return $curSeq[$k];
	}

	/**
	 * Depending on the sequence $seq given (returned from getSequence()),
	 * this method will return the code stream in an array. Each char will be a
	 * string of bit based on the Code 128.
	 *
	 * Each letter from the sequence represents bits.
	 *
	 * 0 to 2 are latches
	 * A to B are Shift + Letter
	 * . is a char in the current encoding
	 *
	 * @param string $text
	 * @param string $seq
	 * @return string[][]
	 */
	private function createBinaryStream($text, $seq) {
		$c = strlen($seq);

		$data = array(); // code stream
		$indcheck = array(); // index for checksum

		$currentEncoding = 0;
		if($this->starting_text === 'A') {
			$currentEncoding = 0;
			$indcheck[] = self::KEY_STARTA;
		} elseif($this->starting_text === 'B') {
			$currentEncoding = 1;
			$indcheck[] = self::KEY_STARTB;
		} elseif($this->starting_text === 'C') {
			$currentEncoding = 2;
			$indcheck[] = self::KEY_STARTC;
		}
		$data[] = $this->code[103 + $currentEncoding];

		$temporaryEncoding = -1;
		for($i = 0, $counter = 0; $i < $c; $i++) {
			$input = $seq[$i];
			$inputI = intval($input);
			if($input === '.') {
				$this->encodeChar($data, $currentEncoding, $seq, $text, $i, $counter, $indcheck);
				if($temporaryEncoding !== -1) {
					$currentEncoding = $temporaryEncoding;
					$temporaryEncoding = -1;
				}
			} elseif($input >= 'A' && $input <= 'B') {
				// We shift
				$encoding = ord($input) - 65;
				$shift = $this->shift[$currentEncoding][$encoding];
				$indcheck[] = $shift;
				$data[] = $this->code[$shift];
				if($temporaryEncoding === -1) {
					$temporaryEncoding = $currentEncoding;
				}
				$currentEncoding = $encoding;
			} elseif($inputI >= 0 && $inputI <= 3) {
				$temporaryEncoding = -1;
				// We latch
				$latch = $this->latch[$currentEncoding][$inputI];
				if($latch !== NULL) {
					$indcheck[] = $latch;
					$data[] = $this->code[$latch];
					$currentEncoding = $inputI;
				}
			}
		}

		return array($indcheck, $data);
	}

	private function encodeChar(&$data, $encoding, $seq, $text, &$i, &$counter, &$indcheck) {
		if(isset($seq[$i + 1]) && $seq[$i + 1] === 'F') {
			// We have a flag !!
			if($text[$counter + 1] === 'F') {
				$number = $text[$counter + 2];
				$fnc = $this->fnc[$encoding][$number - 1];
				$indcheck[] = $fnc;
				$data[] = $this->code[$fnc];
				// Skip F + number
				$counter += 2;
			} else {
				// Not supposed
			}
			$i++;
		} else {
			if($encoding === 2) {
				// We take 2 numbers in the same time
				$code = (int)substr($text, $counter, 2);
				$indcheck[] = $code;
				$data[] = $this->code[$code];
				$counter++;
				$i++;
			} else {
				$keys = ($encoding === 0) ? $this->keysA : $this->keysB;
				$pos = strpos($keys, $text[$counter]);
				$indcheck[] = $pos;
				$data[] = $this->code[$pos];
			}
		}
		$counter++;
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
		$this->data[] = $this->code[$this->checksumValue];
		$this->data[] = $this->code[self::KEY_STOP];

	}
};
?>