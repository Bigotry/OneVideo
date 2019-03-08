<?php
/**
 * BCGupca.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - UPC-A
 *
 * UPC-A contains
 *	- 2 system digits (1 not provided, a 0 is added automatically)
 *	- 5 manufacturer code digits
 *	- 5 product digits
 *	- 1 checksum digit
 *
 * The checksum is always displayed.
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.0.1	8  mar	2009	Jean-Sébastien Goupil	Fix padding for the barcode
 * v2.0.0	23 apr	2008	Jean-Sébastien Goupil	New Version Update
 * v1.2.3	6  feb	2006	Jean-Sébastien Goupil	Fix label position + Using correctly static method
 * v1.2.3b	31 dec	2005	Jean-Sébastien Goupil	Checksum separated + PHP5.1 compatible
 * v1.2.2	23 jul	2005	Jean-Sébastien Goupil	Enhance rapidity
 * v1.2.1	27 jun	2005	Jean-Sébastien Goupil	Font support added + correcting output when text was present
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGupca.barcode.php,v 1.15 2009/03/23 06:48:30 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGBarcode1D.php');

class BCGupca extends BCGBarcode1D {
	protected $codeParity = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->keys = array('0','1','2','3','4','5','6','7','8','9');
		// Left-Hand Odd Parity starting with a space
		// Left-Hand Even Parity is the inverse (0=0012) starting with a space
		// Right-Hand is the same of Left-Hand starting with a bar
		$this->code = array(
			'2100',	/* 0 */
			'1110',	/* 1 */
			'1011',	/* 2 */
			'0300',	/* 3 */
			'0021',	/* 4 */
			'0120',	/* 5 */
			'0003',	/* 6 */
			'0201',	/* 7 */
			'0102',	/* 8 */
			'2001'	/* 9 */
		);
		// Parity, 0=Odd, 1=Even for manufacturer code. Depending on 1st System Digit
		$this->codeParity = array(
			array(0,0,0,0,0),	/* 0 */
			array(0,1,0,1,1),	/* 1 */
			array(0,1,1,0,1),	/* 2 */
			array(0,1,1,1,0),	/* 3 */
			array(1,0,0,1,1),	/* 4 */
			array(1,1,0,0,1),	/* 5 */
			array(1,1,1,0,0),	/* 6 */
			array(1,0,1,0,1),	/* 7 */
			array(1,0,1,1,0),	/* 8 */
			array(1,1,0,1,0)	/* 9 */
		);
	}

	public function parse($text) {
		parent::parse($text);
	
		$this->setLabelOffset();
	}

	public function setFont($font) {
		parent::setFont($font);

		$this->setLabelOffset();
	}

	public function setLabel($label) {
		parent::setLabel($label);

		$this->setLabelOffset();
	}

	public function setOffsetX($offsetX) {
		parent::setOffsetX($offsetX);

		$this->setLabelOffset();
	}

	public function setScale($scale) {
		parent::setScale($scale);

		$this->setLabelOffset();
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
			// Must contain 11 chars
			if($c !== 11) {
				$this->drawError($im, 'Must contain 11 chars, the 12th digit is automatically added.');
				$error_stop = true;
			}
			if($error_stop === false) {
				// The following code is exactly the same as EAN13. We just add a 0 in front of the code !
				$this->text = '0'.$this->text; // We will remove it at the end... don't worry
				// Checksum
				$this->calculateChecksum();
				$temp_text = $this->text . $this->keys[$this->checksumValue];
				// Starting Code
				$this->drawChar($im, '000', true);
				// Draw Second Code
				$this->drawChar($im, $this->findCode($temp_text[1]), false);
				// Draw Manufacturer Code
				for($i = 0; $i < 5; $i++) {
					$this->drawChar($im, self::inverse($this->findCode($temp_text[$i + 2]), $this->codeParity[$temp_text[0]][$i]), false);
				}
				// Draw Center Guard Bar
				$this->drawChar($im, '00000', false);
				// Draw Product Code
				for($i = 7; $i < 13; $i++) {
					$this->drawChar($im, $this->findCode($temp_text[$i]), true);
				}
				// Draw Right Guard Bar
				$this->drawChar($im, '000', true);
				$this->drawText($im);
				// We remove the 0 in front, as we said :)
				$this->text = substr($this->text, 1);
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

		$startlength = 3 * $this->scale;
		$centerlength = 5 * $this->scale;
		$textlength = 12 * 7 * $this->scale;
		$endlength = 3 * $this->scale;
		$lastcharlength = $this->getEndPosition() + 2;

		return array($p[0] + $startlength + $centerlength + $textlength + $endlength + $lastcharlength, $p[1]);
	}

	/**
	 * Overloaded method to calculate checksum
	 */
	protected function calculateChecksum() {
		// Calculating Checksum
		// Consider the right-most digit of the message to be in an "odd" position,
		// and assign odd/even to each character moving from right to left
		// Odd Position = 3, Even Position = 1
		// Multiply it by the number
		// Add all of that and do 10-(?mod10)
		$odd = true;
		$this->checksumValue = 0;
		$c = strlen($this->text);
		for($i = $c; $i > 0; $i--) {
			if($odd === true) {
				$multiplier = 3;
				$odd = false;
			} else {
				$multiplier = 1;
				$odd = true;
			}
			if(!isset($this->keys[$this->text[$i - 1]])) {
				return;
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

	/**
	 * Overloaded method for drawing special label
	 *
	 * @param resource $im
	 */
	protected function drawText($im) {
		if($this->label !== BCGBarcode1D::AUTO_LABEL) {
			parent::drawText($im);
		} elseif($this->label !== '') {
			$temp_text = $this->text . $this->keys[$this->checksumValue];
			if($this->textfont instanceof BCGFont) {
				$code1 = 0;
				$code2 = 0;

				$this->textfont->setText($temp_text);
				$this->drawExtendedBars($im, $this->textfont->getHeight(), $code1, $code2);

				// We need to separate the text, one on the left and one on the right, one starting and one ending
				$text0 = substr($temp_text, 1, 1);
				$text1 = substr($temp_text, 2, 5);
				$text2 = substr($temp_text, 7, 5);
				$text3 = substr($temp_text, 12, 1);
				$font0 = clone $this->textfont;
				$font1 = clone $this->textfont;
				$font2 = clone $this->textfont;
				$font3 = clone $this->textfont;
				$font0->setText($text0);
				$font1->setText($text1);
				$font2->setText($text2);
				$font3->setText($text3);

				$xPosition0 = $this->offsetX * $this->scale - $font0->getWidth() - 4; // -4 is just for beauty;
				$xPosition3 = $this->offsetX * $this->scale + $this->positionX * $this->scale + 2;
				$yPosition0 = $this->thickness * $this->scale + $font0->getHeight() / 2 + $this->offsetY * $this->scale;

				$xPosition1 = ($this->scale * 36 - $font1->getWidth()) / 2 + $code1 * $this->scale + $this->offsetX * $this->scale;
				$xPosition2 = ($this->scale * 37 - $font2->getWidth()) / 2 + $code2 * $this->scale + $this->offsetX * $this->scale;
				$yPosition = $this->thickness * $this->scale + $this->textfont->getHeight() + BCGBarcode1D::SIZE_SPACING_FONT + $this->offsetY * $this->scale;

				$text_color = $this->colorFg->allocate($im);
				$font0->draw($im, $text_color, $xPosition0, $yPosition0);
				$font1->draw($im, $text_color, $xPosition1, $yPosition);
				$font2->draw($im, $text_color, $xPosition2, $yPosition);
				$font3->draw($im, $text_color, $xPosition3, $yPosition0);
			} elseif($this->textfont !== 0) {
				$code1 = 0;
				$code2 = 0;

				$this->drawExtendedBars($im, 9, $code1, $code2);

				$xPosition0 = $this->offsetX * $this->scale - imagefontwidth($this->textfont);
				$xPosition3 = $this->offsetX * $this->scale + $this->positionX * $this->scale + 2;
				$yPosition0 = $this->thickness * $this->scale - imagefontheight($this->textfont) / 2 + $this->offsetY * $this->scale;

				$xPosition1 = ($this->scale * 36 - imagefontwidth($this->textfont) * 5) / 2 + $code1 * $this->scale + $this->offsetX * $this->scale;
				$xPosition2 = ($this->scale * 37 - imagefontwidth($this->textfont) * 5) / 2 + $code2 * $this->scale + $this->offsetX * $this->scale;
				$yPosition = $this->thickness * $this->scale + $this->offsetY * $this->scale;

				$text_color = $this->colorFg->allocate($im);
				imagechar($im, $this->textfont, $xPosition0, $yPosition0, $temp_text[1], $text_color);
				imagestring($im, $this->textfont, $xPosition1, $yPosition, substr($temp_text, 2, 5), $text_color);
				imagestring($im, $this->textfont, $xPosition2, $yPosition, substr($temp_text, 7, 5), $text_color);
				imagechar($im, $this->textfont, $xPosition3, $yPosition0, $temp_text[12], $text_color);
			}
		}
	}

	private function setLabelOffset() {
		$label = $this->getLabel();
		if(!empty($label)) {
			if($this->textfont instanceof BCGFont) {
				$f = clone $this->textfont;
				$f->setText(substr($label, 0, 1));
				$val = ($f->getWidth() + 5) / $this->scale;
				if($val > $this->offsetX) {
					$this->offsetX = $val;
				}
			} elseif($this->textfont !== 0) {
				$val = (imagefontwidth($this->textfont) + 2) / $this->scale;
				if($val > $this->offsetX) {
					$this->offsetX = $val;
				}
			}
		}
	}

	private function drawExtendedBars(&$im, $plus, &$code1, &$code2) {
		$temp_text = $this->text . $this->keys[$this->checksumValue];
		$rememberX = $this->positionX;
		$rememberH = $this->thickness;
		// We increase the bars

		// First 2 Bars
		$this->thickness = $this->thickness + ceil($plus / $this->scale);
		$this->positionX = 0;
		$this->drawSingleBar($im, BCGBarcode::COLOR_FG);
		$this->positionX += 2;
		$this->drawSingleBar($im, BCGBarcode::COLOR_FG);

		// Attemping to increase the 2 following bars
		$this->positionX += 1;
		$code1 = $this->positionX;
		$temp_value = $this->findCode($temp_text[1]);
		$this->drawChar($im, $temp_value, false);
		$code1 = $this->positionX;

		// Center Guard Bar
		$this->positionX += 36;
		$this->drawSingleBar($im, BCGBarcode::COLOR_FG);
		$this->positionX += 2;
		$this->drawSingleBar($im, BCGBarcode::COLOR_FG);
		$code2 = $this->positionX;

		// Attemping to increase the 2 last bars
		$this->positionX += 37;
		$temp_value = $this->findCode($temp_text[12]);
		$this->drawChar($im, $temp_value, true);

		// Completly last bars
		$this->drawSingleBar($im, BCGBarcode::COLOR_FG);
		$this->positionX += 2;
		$this->drawSingleBar($im, BCGBarcode::COLOR_FG);

		$this->positionX = $rememberX;
		$this->thickness = $rememberH;
	}

	private function getEndPosition() {
		if($this->label === BCGBarcode1D::AUTO_LABEL) {
			$this->calculateChecksum();
			if($this->textfont instanceof BCGFont) {
				$f = clone $this->textfont;
				$f->setText($this->checksumValue);
				return $f->getWidth();
			} elseif($this->textfont !== 0) {
				return imagefontwidth($this->textfont);
			}
		}
		return 0;
	}

	private static function inverse($text, $inverse = 1) {
		if($inverse === 1) {
			$text = strrev($text);
		}
		return $text;
	}
};
?>