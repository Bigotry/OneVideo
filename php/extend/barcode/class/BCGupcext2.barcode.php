<?php
/**
 * BCGupcext2.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - UPC Supplemental Barcode 2 digits
 *
 * Working with UPC-A, UPC-E, EAN-13, EAN-8
 * This includes 2 digits (normaly for publications)
 * Must be placed next to UPC or EAN Code
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.00	23 apr	2008	Jean-S�bastien Goupil	New Version Update
 * v1.2.3	6  feb	2006	Jean-S�bastien Goupil	Using correctly static method
 * v1.2.3b	31 dec	2005	Jean-S�bastien Goupil	PHP5.1 compatible
 * v1.2.1	27 jun	2005	Jean-S�bastien Goupil	Font support added + correcting output error
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGupcext2.barcode.php,v 1.12 2008/07/10 04:23:27 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGBarcode1D.php');

class BCGupcext2 extends BCGBarcode1D {
	protected $codeParity = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->keys = array('0','1','2','3','4','5','6','7','8','9');
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
		// Parity, 0=Odd, 1=Even. Depending on ?%4
		$this->codeParity = array(
			array(0,0),	/* 0 */
			array(0,1),	/* 1 */
			array(1,0),	/* 2 */
			array(1,1)	/* 3 */
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

	public function setOffsetY($offsetY) {
		parent::setOffsetY($offsetY);

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
			// Must contain 2 chars
			if($c !== 2) {
				$this->drawError($im, 'Must contain 2 chars.');
				$error_stop = true;
			}
			if($error_stop === false) {
				// Starting Code
				$this->drawChar($im, '001', true);
				// Code
				for($i = 0; $i < 2; $i++) {
					$this->drawChar($im, self::inverse($this->findCode($this->text[$i]), $this->codeParity[intval($this->text) % 4][$i]), false);
					if($i === 0) {
						$this->DrawChar($im, '00', false);	// Inter-char
					}
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

		$startlength = 4 * $this->scale;
		$textlength = 2 * 7 * $this->scale;
		$intercharlength = 2 * $this->scale;

		$label = $this->getLabel();
		$textHeight = 0;
		if(!empty($label)) {
			if($this->textfont instanceof BCGFont) {
				$textfont = clone $this->textfont;
				$textfont->setText($label);
				$textHeight = $textfont->getHeight() + self::SIZE_SPACING_FONT;
			} elseif($this->textfont !== 0) {
				$textHeight = imagefontheight($this->textfont) + self::SIZE_SPACING_FONT;
			}
		}

		return array($p[0] + $startlength + $textlength + $intercharlength, $p[1] - $textHeight);
	}

	/**
	 * Overloaded method for drawing special label
	 *
	 * @param resource $im
	 */
	protected function drawText($im) {
		$label = $this->getLabel();

		if(!empty($label)) {
			$pA = $this->getMaxSize();
			$pB = BCGBarcode1D::getMaxSize();
			$w =  $pA[0] - $pB[0];

			if($this->textfont instanceof BCGFont) {
				$textfont = clone $this->textfont;
				$textfont->setText($label);
				$xPosition = ($w / 2) - ($textfont->getWidth() / 2) + $this->offsetX * $this->scale;
				$yPosition = $this->offsetY * $this->scale - BCGBarcode1D::SIZE_SPACING_FONT;
				$textfont->draw($im, $this->colorFg->allocate($im), $xPosition, $yPosition);
			} elseif($this->textfont !== 0) {
				$xPosition = ($w / 2) - (strlen($label) / 2) * imagefontwidth($this->textfont) + $this->offsetX * $this->scale;
				$yPosition = $this->offsetY * $this->scale - BCGBarcode1D::SIZE_SPACING_FONT - imagefontheight($this->textfont);
				imagestring($im, $this->textfont, $xPosition, $yPosition, $label, $this->colorFg->allocate($im));
			}
		}
	}

	private function setLabelOffset() {
		$label = $this->getLabel();
		if(!empty($label)) {
			if($this->textfont instanceof BCGFont) {
				$f = clone $this->textfont;
				$f->setText($label);

				$val = ($f->getHeight() - $f->getUnderBaseline()) / $this->scale + BCGBarcode1D::SIZE_SPACING_FONT;
				if($val > $this->offsetY) {
					$this->offsetY = $val;
				}
			} elseif($this->textfont !== 0) {
				$val = (imagefontheight($this->textfont) + 2) / $this->scale;
				if($val > $this->offsetY) {
					$this->offsetY = $val;
				}
			}
		}
	}

	private static function inverse($text, $inverse = 1) {
		if($inverse === 1) {
			$text = strrev($text);
		}
		return $text;
	}
};
?>