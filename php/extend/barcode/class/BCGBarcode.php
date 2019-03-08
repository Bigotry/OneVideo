<?php
/**
 * BCGBarcode.php
 *--------------------------------------------------------------------
 *
 * Base class for Barcode 1D and 2D
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.00	23 apr	2008	Jean-Sébastien Goupil	New Version Update
 * v0.8		19 feb	2008	Jean-Sébastien Goupil	First Beta
 *--------------------------------------------------------------------
 * $Id: BCGBarcode.php,v 1.12 2009/05/03 20:49:40 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGColor.php');

abstract class BCGBarcode {
	const COLOR_BG = 0;
	const COLOR_FG = 1;

	protected $error;

	protected $colorFg, $colorBg;			// Color Foreground, Barckground
	protected $scale;				// Scale of the graphic, default: 1
	protected $offsetX, $offsetY;			// Position where to start the drawing

	protected function __construct() {
		$this->setForegroundColor(0x000000);
		$this->setBackgroundColor(0xffffff);
		$this->setScale(1);
		$this->setOffsetX(0);
		$this->setOffsetY(0);

		$this->error = 0;
	}

	/**
	 * Parses the text before displaying it.
	 *
	 * @param mixed $text
	 */
	public abstract function parse($text);

	/**
	 * Sets the foreground color of the barcode. It could be a BCGColor
	 * value or simply a language code (white, black, yellow...) or hex value.
	 *
	 * @param mixed $code
	 */
	public function setForegroundColor($code) {
		if($code instanceof BCGColor) {
			$this->colorFg = $code;
		} else {
			$this->colorFg = new BCGColor($code);
		}
	}

	/**
	 * Sets the background color of the barcode. It could be a BCGColor
	 * value or simply a language code (white, black, yellow...) or hex value.
	 *
	 * @param mixed $code
	 */
	public function setBackgroundColor($code) {
		if($code instanceof BCGColor) {
			$this->colorBg = $code;
		} else {
			$this->colorBg = new BCGColor($code);
		}
	}

	/**
	 * Sets the color
	 *
	 * @param mixed $fg
	 * @param mixed $bg
	 */
	public function setColor($fg, $bg) {
		$this->setForegroundColor($fg);
		$this->setBackgroundColor($bg);
	}

	public function getScale() {
		return $this->scale;
	}

	public function setScale($scale) {
		$scale = intval($scale);
		if($scale <= 0) {
			$scale = 1;
		}
		$this->scale = $scale;
	}

	public abstract function draw(&$im);

	/**
	 * Returns the maximal size of a barcode.
	 * [0]->width
	 * [1]->height
	 *
	 * @return int[]
	 */
	public function getMaxSize() {
		return array($this->offsetX * $this->scale, $this->offsetY * $this->scale);
	}

	/**
	 * Sets the X offset
	 *
	 * @param int $offsetX
	 */
	public function setOffsetX($offsetX) {
		$offsetX = intval($offsetX);
		if($offsetX < 0) {
			$offsetX = 0;
		}
		$this->offsetX = $offsetX;
	}

	/**
	 * Sets the Y offset
	 *
	 * @param int $offsetY
	 */
	public function setOffsetY($offsetY) {
		$offsetY = intval($offsetY);
		if($offsetY < 0) {
			$offsetY = 0;
		}
		$this->offsetY = $offsetY;
	}

	protected function drawPixel($im, $x, $y, $color = self::COLOR_FG) {
		$xR = ($x + $this->offsetX) * $this->scale;
		$yR = ($y + $this->offsetY) * $this->scale;
		// we always draw a rectangle
		imagefilledrectangle($im,
			$xR,
			$yR,
			$xR + $this->scale - 1,
			$yR + $this->scale - 1,
			$this->getColor($im, $color));
	}

	protected function drawRectangle($im, $x1, $y1, $x2, $y2, $color = self::COLOR_FG) {
		if($this->scale === 1) {
			imagerectangle($im,
				($x1 + $this->offsetX) * $this->scale,
				($y1 + $this->offsetY) * $this->scale,
				($x2 + $this->offsetX) * $this->scale,
				($y2 + $this->offsetY) * $this->scale,
				$this->getColor($im, $color));
		} else {
			imagefilledrectangle($im, ($x1 + $this->offsetX) * $this->scale, ($y1 + $this->offsetY) * $this->scale, ($x2 + $this->offsetX) * $this->scale + $this->scale - 1, ($y1 + $this->offsetY) * $this->scale + $this->scale - 1, $this->getColor($im, $color));
			imagefilledrectangle($im, ($x1 + $this->offsetX) * $this->scale, ($y1 + $this->offsetY) * $this->scale, ($x1 + $this->offsetX) * $this->scale + $this->scale - 1, ($y2 + $this->offsetY) * $this->scale + $this->scale - 1, $this->getColor($im, $color));
			imagefilledrectangle($im, ($x2 + $this->offsetX) * $this->scale, ($y1 + $this->offsetY) * $this->scale, ($x2 + $this->offsetX) * $this->scale + $this->scale - 1, ($y2 + $this->offsetY) * $this->scale + $this->scale - 1, $this->getColor($im, $color));
			imagefilledrectangle($im, ($x1 + $this->offsetX) * $this->scale, ($y2 + $this->offsetY) * $this->scale, ($x2 + $this->offsetX) * $this->scale + $this->scale - 1, ($y2 + $this->offsetY) * $this->scale + $this->scale - 1, $this->getColor($im, $color));
		}
	}

	protected function drawFilledRectangle($im, $x1, $y1, $x2, $y2, $color = self::COLOR_FG) {
		if($x1 > $x2) { // Swap
			$x1 ^= $x2 ^= $x1 ^= $x2;
		}
		if($y1 > $y2) { // Swap
			$y1 ^= $y2 ^= $y1 ^= $y2;
		}

		imagefilledrectangle($im,
			($x1 + $this->offsetX) * $this->scale,
			($y1 + $this->offsetY) * $this->scale,
			($x2 + $this->offsetX) * $this->scale + $this->scale - 1,
			($y2 + $this->offsetY) * $this->scale + $this->scale - 1,
			$this->getColor($im, $color));
	}

	protected function getColor($im, $color) {
		if($color === self::COLOR_BG) {
			return $this->colorBg->allocate($im);
		} else {
			return $this->colorFg->allocate($im);
		}
	}

	/**
	 * Writes the Error on the picture
	 *
	 * @param ressource $img
	 * @param string $text
	 */
	protected function drawError(&$im, $text) {
		// Is the image big enough?
		$w = imagesx($im);
		$h = imagesy($im);

		$text = 'Error: ' . $text;

		$width = imagefontwidth(2) * strlen($text);
		$height = imagefontheight(2) + $this->error * 15;
		if($width > $w || $height > $h) {
			$width = max($w, $width);
			$height = max($h, $height);
			// We change the size of the image
			$newimg = imagecreatetruecolor($width, $height);
			imagefill($newimg, 0, 0, imagecolorat($im, 0, 0));
			imagecopy($newimg, $im, 0, 0, 0, 0, $w, $h);
			$im = $newimg;
		}

		imagestring($im, 2, 0, $this->error * 15, $text, $this->colorFg->allocate($im));
		$this->error++;
	}
}
?>