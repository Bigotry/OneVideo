<?php
/**
 * BCGisbn.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - ISBN-10 and ISBN-13
 *
 * You can provide an ISBN with 10 digits with or without the checksum.
 * You can provide an ISBN with 13 digits with or without the checksum.
 * Calculate the ISBN based on the EAN-13 encoding.
 *
 * The checksum is always displayed.
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.00	23 apr	2008	Jean-Sébastien Goupil	New Version Update
 * v1.2.5	13 apr	2007	Jean-Sébastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGisbn.barcode.php,v 1.5 2009/03/23 06:48:30 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGean13.barcode.php');

class BCGisbn extends BCGean13 {
	const GS1_AUTO = 0;
	const GS1_PREFIX978 = 1;
	const GS1_PREFIX979 = 2;

	private $gs1;
	private $isbn_created;
	private $isbn_text;
	private $isbn_textfont;

	private $forceOffsetY;

	/**
	 * Constructor
	 *
	 * @param int $gs1
	 * @param string $isbn_text
	 * @param mixed $textfont2 BCGFont or int
	 */
	public function __construct($gs1 = self::GS1_AUTO, $isbn_text = BCGBarcode1D::AUTO_LABEL, $isbn_font = null) {
		parent::__construct();

		$this->forceOffsetY = false;

		if($isbn_font === null) {
			$this->setISBNFont($this->textfont);
		} else {
			$this->setISBNFont($isbn_font);
		}
		$this->setISBNText($isbn_text);
		$this->setGS1($gs1);
	}

	/**
	 * Saves Text
	 *
	 * @param string $text
	 */
	public function parse($text) {
		BCGBarcode1D::parse(str_replace(array('-', ' '), '', $text));

		$this->createISBNText();
		$this->setLabelOffset();
	}

	/**
	 * Sets the first numbers of the barcode.
	 *  - GS1_AUTO: Adds 978 before the code
	 *  - GS1_PREFIX978: Adds 978 before the code
	 *  - GS1_PREFIX979: Adds 979 before the code
	 *
	 * @param int $gs1
	 */
	public function setGS1($gs1) {
		$gs1 = (int)$gs1;
		if($gs1 !== self::GS1_AUTO && $gs1 !== self::GS1_PREFIX978 && $gs1 !== self::GS1_PREFIX979) {
			$gs1 = self::GS1_AUTO;
		}
		$this->gs1 = $gs1;
	}

	/**
	 * Sets the font to write the ISBN text on the top of the barcode.
	 *
	 * @param mixed $font
	 */
	public function setISBNFont($font) {
		if($font instanceof BCGFont) {
			$this->isbn_textfont = clone $font;
		} else {
			$this->isbn_textfont = intval($font);
		}

		$this->setLabelOffset();
	}

	/**
	 * Sets the text for the ISBN value.
	 *
	 * @param string $isbn_text
	 */
	public function setISBNText($text) {
		$this->isbn_text = $text;

		$this->createISBNText();
		$this->setLabelOffset();
	}

	public function setOffsetY($offsetY) {
		parent::setOffsetY($offsetY);

		// We force the offsetY, so we won't position based on the label position
		$this->forceOffsetY = true;
	}

	public function getMaxSize() {
		// We must compute the first digit calculating the width
		$null = null;
		$this->isLengthCorrect($null);

		return parent::getMaxSize();
	}

	protected function setLabelOffset() {
		parent::setLabelOffset();

		if(!empty($this->isbn_created) && !$this->forceOffsetY) {
			if($this->isbn_textfont instanceof BCGFont) {
				$f = clone $this->isbn_textfont;
				$f->setText($this->isbn_created);

				$val = ($f->getHeight() - $f->getUnderBaseline()) / $this->scale + BCGBarcode1D::SIZE_SPACING_FONT;
					$this->offsetY = $val;
			} elseif($this->isbn_textfont !== 0) {
				$val = (imagefontheight($this->isbn_textfont) + 2) / $this->scale;
					$this->offsetY = $val;
			}
		}
	}

	protected function isCharsAllowed(&$im) {
		$c = strlen($this->text);
		// Special case, if we have 10 digits, the last one can be X
		if($c === 10) {
			if(array_search($this->text[9], $this->keys) === false && $this->text[9] !== 'X') {
				$this->drawError($im, 'Char \'' . $this->text[9] . '\' not allowed.');
				return false;
			}
			// Drop the last char
			$this->text = substr($this->text, 0, 9);
		}

		return parent::isCharsAllowed($im);
	}

	protected function isLengthCorrect(&$im) {
		$c = strlen($this->text);

		// If we have 13 chars just flush the last one
		if($c === 13) {
			$this->text = substr($this->text, 0, 12);
			return true;
		} elseif($c === 12) {
			return true;
		} elseif($c === 9 || $c === 10) {
			if($c === 10) {
				// Before dropping it, we check if it's legal
				if(array_search($this->text[9], $this->keys) === false && $this->text[9] !== 'X') {
					return false;
				}
				$this->text = substr($this->text, 0, 9);
			}
			if($this->gs1 === self::GS1_AUTO || $this->gs1 === self::GS1_PREFIX978) {
				$this->text = '978' . $this->text;
			} elseif($this->gs1 === self::GS1_PREFIX979) {
				$this->text = '979' . $this->text;
			}
			// We changed the start, recalculate the offset label
			parent::setLabelOffset();

			return true;
		} else {
			if($im !== null) {
				$this->drawError($im, 'Must provide 9, 10, 12 or 13 digits.');
			}
			return false;
		}
	}

	/**
	 * Overloaded method for drawing special label
	 *
	 * @param resource $im
	 */
	protected function drawText($im) {
		parent::drawText($im);

		if(strlen($this->isbn_created) > 0) {
			$pA = $this->getMaxSize();
			$pB = BCGBarcode1D::getMaxSize();
			$w =  $pA[0] - $pB[0];

			if($this->isbn_textfont instanceof BCGFont) {
				$textfont = clone $this->isbn_textfont;
				$textfont->setText($this->isbn_created);
				$xPosition = ($w / 2) - ($textfont->getWidth() / 2) + $this->offsetX * $this->scale;
				$yPosition = $this->offsetY * $this->scale - BCGBarcode1D::SIZE_SPACING_FONT;
				$textfont->draw($im, $this->colorFg->allocate($im), $xPosition, $yPosition);
			} elseif($this->isbn_textfont !== 0) {
				$xPosition = ($w / 2) - (strlen($this->isbn_created) / 2) * imagefontwidth($this->isbn_textfont) + $this->offsetX * $this->scale;
				$yPosition = $this->offsetY * $this->scale - BCGBarcode1D::SIZE_SPACING_FONT - imagefontheight($this->isbn_textfont);
				imagestring($im, $this->isbn_textfont, $xPosition, $yPosition, $this->isbn_created, $this->colorFg->allocate($im));
			}
		}
	}

	private function createISBNText() {
		if($this->isbn_text === BCGBarcode1D::AUTO_LABEL && !empty($this->text)) { 
			// We try to create the ISBN Text... the hyphen really depends the ISBN agency.
			// We just put one before the checksum and one after the GS1 if present.
			$c = strlen($this->text);
			if($c === 12 || $c === 13) {
				// If we have 13 characters now, just transform it temporarily to find the checksum...
				// Further in the code we take care of that anyway.
				$lastCharacter = '';
				if($c === 13) {
					$lastCharacter = $this->text[12];
					$this->text = substr($this->text, 0, 12);
				}
				
				$checksum = $this->processChecksum();
				$this->isbn_created = 'ISBN ' . substr($this->text, 0, 3) . '-' . substr($this->text, 3, 9) . '-' . $checksum;
				
				// Put the last character back
				if($c === 13) {
					$this->text .= $lastCharacter;
				}
			} elseif($c === 9 || $c === 10) {
				$checksum = 0;
				for($i = 10; $i >= 2; $i--) {
					$checksum += $this->text[10 - $i] * $i;
				}
				$checksum = 11 - $checksum % 11;
				if($checksum === 10) {
					$checksum = 'X';
				}
				$this->isbn_created = 'ISBN ' . substr($this->text, 0, 9) . '-' . $checksum;
			} else {
				$this->isbn_created = '';
			}
		} else {
			$this->isbn_created = $this->isbn_text;
		}
	}
};
?>