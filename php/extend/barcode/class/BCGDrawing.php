<?php
/**
 * BCGDrawing.php
 *--------------------------------------------------------------------
 *
 * Holds the drawing $im
 * You can use get_im() to add other kind of form not held into these classes.
 *
 *--------------------------------------------------------------------
 * Revision History
 * v2.0.1	8  mar	2009	Jean-S�bastien Goupil	Supports GIF and WBMP
 * v2.0.0	23 apr	2008	Jean-S�bastien Goupil	New Version Update
 * v1.2.3b	31 dec	2005	Jean-S�bastien Goupil	Just one barcode per drawing
 * v1.2.1	27 jun	2005	Jean-S�bastien Goupil	Font support added
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: BCGDrawing.php,v 1.11 2009/03/23 06:48:30 jsgoupil Exp $
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGBarcode.php');

class BCGDrawing {
	const IMG_FORMAT_PNG = 1;
	const IMG_FORMAT_JPEG = 2;
	const IMG_FORMAT_GIF = 3;
	const IMG_FORMAT_WBMP = 4;

	private $w, $h;		// int
	private $color;		// BCGColor
	private $filename;	// char *
	private $im;		// {object}
	private $barcode;	// BCGBarcode

	/**
	 * Constructor
	 *
	 * @param int $w
	 * @param int $h
	 * @param string filename
	 * @param BCGColor $color
	 */
	public function __construct($filename, BCGColor $color) {
		$this->im = null;
		$this->setFilename($filename);
		$this->color = $color;
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->destroy();
	}

	/**
	 * Sets the filename
	 *
	 * @param string $filaneme
	 */
	public function setFilename($filename) {
		$this->filename = $filename;
	}

	/**
	 * Init Image and color background
	 */
	private function init() {
		if($this->im === null) {
			$this->im = imagecreatetruecolor($this->w, $this->h)
			or die('Can\'t Initialize the GD Libraty');
			imagefilledrectangle($this->im, 0, 0, $this->w - 1, $this->h - 1, $this->color->allocate($this->im));
		}
	}

	/**
	 * @return resource
	 */
	public function get_im() {
		return $this->im;
	}

	/**
	 * @param resource $im
	 */
	public function set_im(&$im) {
		$this->im = $im;
	}

	/**
	 * Set Barcode for drawing
	 *
	 * @param BCGBarcode $barcode
	 */
	public function setBarcode(BCGBarcode $barcode) {
		$this->barcode = $barcode;
	}

	/**
	 * Draw the barcode on the image $im
	 */
	public function draw() {
		$size = $this->barcode->getMaxSize();
		$this->w = max(1, $size[0]);
		$this->h = max(1, $size[1]);
		$this->init();
		$this->barcode->draw($this->im);
	}

	/**
	 * Save $im into the file (many format available)
	 *
	 * @param int $image_style
	 * @param int $quality
	 */
	public function finish($image_style = self::IMG_FORMAT_PNG, $quality = 100) {
		if ($image_style === self::IMG_FORMAT_PNG) {
			if (empty($this->filename)) {
				imagepng($this->im);
			} else {
				imagepng($this->im, $this->filename);
			}
		} elseif ($image_style === self::IMG_FORMAT_JPEG) {
			imagejpeg($this->im, $this->filename, $quality);
		} elseif ($image_style === self::IMG_FORMAT_GIF) {
			imagegif($this->im, $this->filename);
		} elseif ($image_style === self::IMG_FORMAT_WBMP) {
			imagewbmp($this->im, $this->filename);
		}
	}

	/**
	 * Free the memory of PHP (called also by destructor)
	 */
	public function destroy() {
		@imagedestroy($this->im);
	}
};
?>