<?php
//////////////////////////////////////////////////////////////////////
// table.class.php
//--------------------------------------------------------------------
//
// Class Table - Don't modify this file !
//
//--------------------------------------------------------------------
// Revision History
// $Id: LSTable.php,v 1.3 2008/07/10 04:23:27 jsgoupil Exp $
//--------------------------------------------------------------------
// Copyright (C) Jean-Sebastien Goupil
// http://www.barcodephp.com
//--------------------------------------------------------------------
//////////////////////////////////////////////////////////////////////
define( 'TABLE_DEFAULT_ROW_HIDDEN', FALSE );
define( 'TABLE_DEFAULT_COL_HIDDEN', FALSE );
define( 'TABLE_DEFAULT_CELL_VALUE', '&nbsp;' );	// If '&nbsp;', a cell will appear, if NULL, no cell will appear
define( 'TABLE_DEFAULT_TEMPLATE', 'tpl_LS' );

include( $class_dir.'/Table_template.php' );
class LSTable {
	// PRIVATE ARGUMENTS
	private $template;						// string
	private $numRows, $numCols;					// int
	private $title;							// string
	private $text = array();					// string
	private $cellsAttributes = array(), $rowsAttributes = array();	// string
	private $hiddenRows = array(), $hiddenCols = array();		// bool
	private $width;							// string

	private $temp_col;						// int
	private $temp_ascending;					// bool

	// PUBLIC FUNCTIONS
	/**
	* @return
	* @param int $numRows
	* @param int $numCols
	* @param string $width
	* @param object $parent
	* @desc Constructor. You can put a $parent object. This will search the first empty place into the parent.
	**/
	function __construct ( $numRows, $numCols, $width, &$parent ) {
		$this->numRows = intval( $numRows );
		$this->numCols = intval( $numCols );
		for( $i = 0; $i < $numRows; $i++ )
			$this->_setVariable( $i );
		for( $i = 0; $i < $numCols; $i++ )
			$this->hiddenCols[ $i ] = TABLE_DEFAULT_COL_HIDDEN;
		$this->title = '';
		$this->width = strval( $width );

		if( defined( 'TABLE_DEFAULT_TEMPLATE' ) )
			$this->setTemplate( TABLE_DEFAULT_TEMPLATE );

		// We look for an empty place if $parent isn't null
		if( is_object( $parent ) ) {
			$stop = false;
			for( $i = 0; $i < $parent->numRows; $i++ ) {
				for( $j = 0; $j < $parent->numCols; $j++ ) {
					if( $parent->text[ $i ][ $j ] == TABLE_DEFAULT_CELL_VALUE ) {
						$parent->text[ $i ][ $j ] =& $this;
						$stop = true;
					}
					if( $stop == true )
						break;
				}
				if( $stop == true )
					break;
			}
		}
	}

	// No destructor for PHP4
	//function __destruct() {
	//
	//}

	/**
	* @return void
	* @param int $row
	* @param int $col
	* @param mixed $string
	* @desc Writes Text or Object into $row and $col.
	**/
	public function setText ( $row, $col, $string ) {
		if( is_int( $row ) && is_int( $col ) )
			if( $row < $this->numRows && $col < $this->numCols )
				$this->text[ $row ][ $col ] = $string;
	}

	/**
	* @return void
	* @param int $row
	* @param int $col
	* @param string $attrib
	* @param mixed $value
	* @desc Adds a Cell Attribute at $row and $col.
	**/
	public function addCellAttribute ( $row, $col, $attrib, $value ) {
		if( is_int( $row ) && is_int( $col ) && is_string( $attrib ) )
			if( $row < $this->numRows && $col < $this->numCols )
				$this->cellsAttributes[ $row ][ $col ][ strtolower( $attrib ) ] = $value;
	}

	/**
	* @return void
	* @param int $row
	* @param int $col
	* @param string $attrib
	* @desc Deletes a Cell Attribute at $row and $col.
	**/
	public function delCellAttribute ( $row, $col, $attrib ) {
		if( is_int( $row ) && is_int( $col ) && is_string( $attrib ) )
			if( $row < $this->numRows && $col < $this->numCols )
				unset( $this->cellsAttributes[ $row ][ $col ][ strtolower( $attrib ) ] );
	}

	/**
	* @return void
	* @param int $row
	* @param string $attrib
	* @param mixed $value
	* @desc Adds a Row Attribute at $row. Will be located in TR.
	**/
	public function addRowAttribute ( $row, $attrib, $value ) {
		if( is_int( $row ) && is_string( $attrib ) )
			if( $row < $this->numRows )
				$this->rowsAttributes[ $row ][ strtolower( $attrib ) ] = $value;
	}

	/**
	* @return void
	* @param int $row
	* @param string $attrib
	* @desc Deletes a Row Attribute at $row.
	**/
	public function delRowAttribute ( $row, $attrib ) {
		if( is_int( $row ) && is_string( $attrib ) )
			if( $row < $this->numRows )
				unset( $this->rowsAttributes[ $row ][ strtolower( $attrib ) ] );
	}

	/**
	* @return void
	* @param int $row
	* @param string $attrib
	* @param mixed $value
	* @desc Adds a Cell Attribute in all cells located on $row row.
	**/
	public function addAllCellsInRowAttribute ( $row, $attrib, $value ) {
		if( is_int( $row ) && is_string( $attrib ) )
			if( $row < $this->numRows )
				for( $i = 0; $i < $this->numCols; $i++ )
					$this->cellsAttributes[ $row ][ $i ][ strtolower( $attrib ) ] = $value;
	}

	/**
	* @return void
	* @param int $col
	* @param string $attrib
	* @param mixed $value
	* @desc Adds a Cell Attribute in all cells located on $col column.
	**/
	public function addAllCellsInColAttribute ( $col, $attrib, $value ) {
		if( is_int( $col ) && is_string( $attrib ) )
			if( $col < $this->numCols )
				for( $i = 0; $i < $this->numRows; $i++ )
					$this->cellsAttributes[ $i ][ $col ][ strtolower( $attrib ) ] = $value;
	}

	/**
	* @return void
	* @param callback $template
	* @desc Sets Template (Class)
	**/
	public function setTemplate ( $template ) {
		if( is_string( $template ) )
			if( class_exists( $template ) )
				$this->template = strval( $template );
	}

	/**
	* @return string
	* @desc Returns current Template
	**/
	public function template () {
		return $this->template;
	}

	/**
	* @return mixed
	* @param int $row
	* @param int $col
	* @desc Returns the text or object contained into $row and $col
	**/
	public function text ( $row, $col ) {
		if( is_int( $row ) && is_int( $col ) )
			if( $row < $this->numRows && $col < $this->numCols )
				return $this->text[ $row ][ $col ];
		return FALSE;
	}

	/**
	* @return void
	* @param int $row
	* @param int $col
	* @desc Clears the cell located into $row and $col. Keeps the Cell.
	*/
	public function clearCell ( $row, $col ) {
		if( is_int( $row ) && is_int( $col ) ) {
			if( $row < $this->numRows && $col < $this->numCols ) {
				$this->text[ $row ][ $col ] = TABLE_DEFAULT_CELL_VALUE;
				unset( $this->cellsAttributes[ $row ][ $col ] );
				$this->cellsAttributes[ $row ][ $col ] = array();
			}
		}
	}

	/**
	* @return int
	* @desc Returns the number of Rows
	**/
	public function numRows () {
		return $this->numRows;
	}

	/**
	* @return int
	* @desc Returns the number of Columns
	**/
	public function numCols () {
		return $this->numCols;
	}

	/**
	* @return void
	* @param int $col
	* @param bool $ascending
	* @desc Sorts table based on $col.
	**/
	public function sortColumn ( $col, $ascending = TRUE ) {
		if( is_int( $col ) && is_bool( $ascending ) ) {
			if( $col < $this->numCols ) {
				$this->temp_col = $col;
				$this->temp_ascending = $ascending;
				$this->_sort_merge( $this->text );
			}
		}
	}

	/**
	* @return void
	* @param int $r
	* @desc Sets the number of rows of the table.
	**/
	public function setNumRows ( $r ) {
		if( is_int( $r ) )
			if( $r >= 0 )
				if( $r > $this->numRows )
					$this->insertRows( $this->numRows, $r - $this->numRows );
				elseif( $r < $this->numRows )
					for( $i = $this->numRows; $i > $r; $i-- )
						$this->removeRow( $i - 1 );
	}

	/**
	* @return void
	* @param int $r
	* @desc Sets the number of columns of the table.
	**/
	public function setNumCols ( $r ) {
		if( is_int( $r ) )
			if( $r >= 0 )
				if( $r > $this->numCols )
					$this->insertColumns( $this->numCols, $r - $this->numCols );
				elseif( $r < $this->numCols )
					for( $i = $this->numCols; $i > $r; $i-- )
						$this->removeColumn( $i - 1 );
	}

	/**
	* @return void
	* @param int $row
	* @desc Hides a $row from table
	**/
	public function hideRow ( $row ) {
		if( is_int( $row ) )
			if( $row < $this->numRows )
				$this->hiddenRows[ $row ] = TRUE;
	}

	/**
	* @return void
	* @param int $col
	* @desc Hides a $col from table
	**/
	public function hideColumn ( $col ) {
		if( is_int( $col ) )
			if( $col < $this->numCols )
				$this->hiddenCols[ $col ] = TRUE;
	}

	/**
	* @return void
	* @param int $row
	* @desc Shows a $row from table
	**/
	public function showRow ( $row ) {
		if( is_int( $row ) )
			if( $row < $this->numRows )
				$this->hiddenRows[ $row ] = FALSE;
	}

	/**
	* @return void
	* @param int $col
	* @desc Shows a $col from table
	**/
	public function showColumn ( $col ) {
		if( is_int( $col ) )
			if( $col < $this->numCols )
				$this->hiddenCols[ $col ] = FALSE;
	}

	/**
	* @return bool
	* @param int $row
	* @desc Returns TRUE if a row is hidden.
	**/
	public function isRowHidden ( $row ) {
		if( is_int( $row ) )
			if( $row < $this->numRows )
				return $this->hiddenRows[ $row ];
		return FALSE;
	}

	/**
	* @return bool
	* @param int $col
	* @desc Returns TRUE if a col is hidden.
	**/
	public function isColumnHidden ( $col ) {
		if( is_int( $col ) )
			if( $col < $this->numCols )
				return $this->hiddenCols[ $col ];
		return FALSE;
	}

	/**
	* @return void
	* @param int $row1
	* @param int $row2
	* @param bool $swapAttributes
	* @desc Swaps 2 rows. Swaps attributes if $switchAttributes is TRUE.
	**/
	public function swapRows ( $row1, $row2, $swapAttributes = FALSE ) {
		if( is_int( $row1 ) && is_int( $row2 ) && is_bool( $swapAttributes ) ) {
			if( $row1 < $this->numRows && $row2 < $this->numRows ) {
				$temp =& $this->text[ $row1 ];
				$this->text[ $row1 ] =& $this->text[ $row2 ];
				$this->text[ $row2 ] =& $temp;
				if( $swapAttributes == TRUE ) {
					$temp2 = $this->cellsAttributes[ $row1 ];
					$this->cellsAttributes[ $row1 ] = $this->cellsAttributes[ $row2 ];
					$this->cellsAttributes[ $row2 ] = $temp2;

					$temp2 = $this->rowsAttributes[ $row1 ];
					$this->rowsAttributes[ $row1 ] = $this->rowsAttributes[ $row2 ];
					$this->rowsAttributes[ $row2 ] = $temp2;
				}
			}
		}
	}

	/**
	* @return void
	* @param int $col1
	* @param int $col2
	* @param bool $swapAttributes
	* @desc Swaps 2 cols. Swaps attributes if $switchAttributes is TRUE.
	**/
	public function swapColumns ( $col1, $col2, $swapAttributes = FALSE ) {
		if( is_int( $col1 ) && is_int( $col2 ) && is_bool( $swapAttributes ) ) {
			if( $col1 < $this->numCols && $col2 < $this->numCols ) {
				for( $i = 0; $i < $this->numRows; $i++ ){
					$temp =& $this->text[ $i ][ $col1 ];
					$this->text[ $i ][ $col1 ] =& $this->text[ $i ][ $col2 ];
					$this->text[ $i ][ $col2 ] =& $temp;
					if( $swapAttributes == TRUE ) {
						$temp2 = $this->cellsAttributes[ $i ][ $col1 ];
						$this->cellsAttributes[ $i ][ $col1 ] = $this->cellsAttributes[ $i ][ $col2 ];
						$this->cellsAttributes[ $i ][ $col2 ] = $temp2;
					}
				}
			}
		}
	}

	/**
	* @return void
	* @param int $row1
	* @param int $col1
	* @param int $row2
	* @param int $col2
	* @param bool $swapAttributes
	* @desc Swaps 2 cells. Swaps attributes if $switchAttributes is TRUE.
	**/
	public function swapCells ( $row1, $col1, $row2, $col2, $swapAttributes = FALSE ) {
		if( is_int( $row1 ) && is_int( $col1 ) && is_int( $row2 ) && is_int( $col2 ) && is_bool( $swapAttributes ) ) {
			if( $row1 < $this->numRows && $col1 < $this->numCols && $row2 < $this->numRows && $col2 < $this->numCols ) {
				$temp =& $this->text[ $row1 ][ $col1 ];
				$this->text[ $row1 ][ $col1 ] =& $this->text[ $row2 ][ $col2 ];
				$this->text[ $row2 ][ $col2 ] =& $temp;
				if( $swapAttributes == TRUE ) {
					$temp2 = $this->cellsAttributes[ $row1 ][ $col1 ];
					$this->cellsAttributes[ $row1 ][ $col1 ] = $this->cellsAttributes[ $row2 ][ $col2 ];
					$this->cellsAttributes[ $row2 ][ $col2 ] = $temp2;
				}
			}
		}
	}

	/**
	* @return void
	* @param int $row
	* @param int $count
	* @desc Inserts $count row at $row position.
	**/
	public function insertRows ( $row, $count = 1 ) {
		if( is_int( $row ) && is_int( $count ) ) {
			if( $row >= 0 && $row <= $this->numRows ) {
				for( $i = 0; $i < $count; $i++) {
					array_splice( $this->text, $row, 0, '' );
					array_splice( $this->hiddenRows, $row, 0, FALSE );
					array_splice( $this->cellsAttributes, $row, 0, FALSE );
					array_splice( $this->rowsAttributes, $row, 0, FALSE );
					$this->_setVariable( $row );
					$this->numRows += 1;
				}
			}
		}
	}

	/**
	* @return void
	* @param int $col
	* @param int $count
	* @desc Inserts $count columns at $col position.
	**/
	public function insertColumns ( $col, $count = 1 ) {
		if( is_int( $col ) && is_int( $count ) ) {
			if( $col >= 0 && $col <= $this->numCols ) {
				for( $i = 0; $i < $this->numRows; $i++ ) {
					$temp_text = array();
					$temp_attributes = array();
					reset($this->text[ $i ]);
					for( $j = 0; $j <= $this->numCols; $j++ ) {
						if( $col == $j ) {
							for( $k = 0; $k < $count; $k++ ){
								$temp_text[] = '';
								$temp_attributes[] = array();
							}
						}
						if( $j == $this->numCols )
							break;
						$temp_text[] = $this->text[ $i ][ $j ];
						$temp_attributes[] = $this->cellsAttributes[ $i ][ $j ];
					}
					$this->text[ $i ] = $temp_text;
					$this->cellsAttributes[ $i ] = $temp_attributes;
				}
				for( $i = 0; $i < $count; $i++ )
					array_splice( $this->hiddenCols, $col, 0, FALSE );
				$this->numCols += $count;
			}
		}
	}

	/**
	* @return void
	* @param int $row
	* @desc Removes $row.
	**/
	public function removeRow ( $row ) {
		if( is_int( $row ) ) {
			if( $row < $this->numRows ) {
				array_splice( $this->text, $row, 1 );
				array_splice( $this->hiddenRows, $row, 1 );
				array_splice( $this->cellsAttributes, $row, 1 );
				array_splice( $this->rowsAttributes, $row, 1 );
				$this->numRows -= 1;
			}
		}
	}

	/**
	* @return void
	* @param int $col
	* @desc Removes $col.
	**/
	public function removeColumn ( $col ) {
		if( is_int( $col ) ) {
			if( $col < $this->numCols ) {
				for( $i = 0; $i < $this->numRows; $i++ ) {
					$temp_text = array();
					$temp_attributes = array();
					reset($this->text[ $i ]);
					while ( list( $key, ) = each( $this->text[ $i ] ) ) {
						if( $key != $col ) {
							$temp_text[] = $this->text[ $i ][ $key ];
							$temp_attributes[] = $this->cellsAttributes[ $i ][ $key ];
						}
					}
					$this->text[ $i ] = $temp_text;
					$this->cellsAttributes[ $i ] = $temp_attributes;
				}
				array_splice( $this->hiddenCols, $col, 1 );
				$this->numCols -= 1;
			}
		}
	}

	/**
	* @return void
	* @param int $width
	* @desc Sets Table Width.
	**/
	public function setWidth ( $width ) {
		if( is_string( $width ) )
			$this->width = strval( $width );
	}

	/**
	* @return string
	* @desc Returns Table Width.
	**/
	public function width () {
		return $this->width;
	}

	/**
	* @return void
	* @param int $title
	* @desc Sets Table Title.
	**/
	public function setTitle ( $title ) {
		if( is_string( $title ) )
			$this->title = strval( $title );
	}

	/**
	* @return string
	* @desc Returns Table Title.
	**/
	public function title () {
		return $this->title;
	}

	/**
	* @return void
	* @desc Displays the table with its cells.
	**/
	public function draw () {
		if( $this->template != '' ) {
			$tpl = new $this->template;
			$tpl->__header( $this->width, $this->title );
			$this->_check_rowcol_span();
			for( $i = 0; $i < $this->numRows; $i++ ) {
				if( $this->hiddenRows[ $i ] == FALSE ) {
					$tpl->__row_start( $this->rowsAttributes[ $i ] );
					for( $j = 0; $j < $this->numCols; $j++ ) {
						if( $this->hiddenCols[ $j ] == FALSE ) {
							if( $this->text[ $i ][ $j ] !== NULL ) {
								if( is_object( $this->text[ $i ][ $j ] ) ) {
									$tpl->__cell_start( '', $this->cellsAttributes[ $i ][ $j ] );
									$this->text[ $i ][ $j ]->draw();
								}
								else 
									$tpl->__cell_start( $this->text[ $i ][ $j ], $this->cellsAttributes[ $i ][ $j ] );
								$tpl->__cell_stop( $this->text[ $i ][ $j ], $this->cellsAttributes[ $i ][ $j ] );
							}
						}
					}
					$tpl->__row_stop( $this->rowsAttributes[ $i ] );
				}
			}
			$tpl->__footer( $this->width, $this->title );
		}
	}

	// PRIVATE FUNCTIONS
	/**
	* @return void
	* @desc Sets NULL to cells if colspan or rowspan go on the cells
	**/
	private function _check_rowcol_span() {
		for( $i = 0; $i < $this->numRows; $i++ ) {
			for( $j = 0; $j < $this->numCols; $j++ ) {
				if( isset( $this->cellsAttributes[ $i ][ $j ][ 'colspan' ] ) )
					if( $this->cellsAttributes[ $i ][ $j ][ 'colspan' ] > 0 )
						for( $z = 1; $z < intval( $this->cellsAttributes[ $i ][ $j ][ 'colspan' ] ); $z++ )
							$this->text[ $i ][ $j + $z ] = NULL;
				if( isset( $this->cellsAttributes[ $i ][ $j ][ 'rowspan' ] ) )
					if( $this->cellsAttributes[ $i ][ $j ][ 'rowspan' ] > 0 )
						for( $z = 1; $z < intval( $this->cellsAttributes[ $i ][ $j ][ 'rowspan' ] ); $z++ )
							$this->text[ $i + $z ][ $j ] = NULL;
			}
		}
	}


	/**
	* @return void
	* @param int $r
	* @desc Sets Default Variables for row $r.
	**/
	private function _setVariable ( $r ) {
		$this->text[ $r ] = array_fill( 0, $this->numCols, TABLE_DEFAULT_CELL_VALUE );
		$this->cellsAttributes[ $r ] = array_fill( 0, $this->numCols, array() );
		$this->rowsAttributes[ $r ] = array();
		$this->hiddenRows[ $r ] = TABLE_DEFAULT_ROW_HIDDEN;
	}

	/**
	* @return void
	* @param int $r
	* @desc Unsets Default Variables for row $r.
	**/
	private function _unsetVariable ( $r ) {
		unset( $this->text[ $r ] );
		unset( $this->cellsAttributes[ $r ] );
		unset( $this->rowsAttributes[ $r ] );
		unset( $this->hiddenRows[ $r ] );
	}

	/**
	* @return void
	* @param string $tab
	* @desc Merge Function
	**/
	private function _sort_merge ( &$tab ) {
		if( count( $tab ) <= 1 ) return;
		else {
			$tab1 = array();
			$tab2 = array();

			for( $i = 0; $i < count( $tab ); $i++) {
				if( $i < ( count( $tab ) ) / 2 )
					$tab1[] = $tab[ $i ];
				else
					$tab2[] = $tab[ $i ];
			}

			$this->_sort_merge( $tab1 );
			$this->_sort_merge( $tab2 );

			$this->_merge_all( $tab1, $tab2, $tab );
		}
	}

	/**
	* @return void
	* @param string $tab1
	* @param string $tab2
	* @param string $tab
	* @desc Merge Function
	**/
	private function _merge_all ( $tab1, $tab2, &$tab ) {
		$i = 0;
		$i1 = $i2 = 0;
		while( $i1 < count( $tab1 ) && $i2 < count( $tab2 ) ) {
			if( strcmp( $tab1[ $i1 ][ $this->temp_col ], $tab2[ $i2 ][ $this->temp_col ] ) == (($this->temp_ascending==TRUE)?-1:1) )
				$tab[ $i ] = $tab1[ $i1++ ];
			else
				$tab[ $i ] = $tab2[ $i2++ ];
			$i++;
		}

		while( $i1 < count( $tab1 ) ) {
			$tab[ $i ] = $tab1[ $i1++ ];
			$i++;
		}
		while( $i2 < count( $tab2 ) ) {
			$tab[ $i ] = $tab2[ $i2++ ];
			$i++;
		}
	}
}
?>