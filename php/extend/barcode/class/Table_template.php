<?php
class tpl_LS {
	function __header ( $width, $title ) {
		echo '<table width="'.$width.'" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#004163" class="tableline"><tr><th><u>'.$title.'</u>'."\n".'</th></tr><tr><td>'."\n".'<table width="100%" border="0" cellpadding="4" cellspacing="1" align="center">';
	}

	function __footer ( $width, $title ) {
		echo '</table>'."\n".'</td></tr></table>'."\n";
	}

	function __row_start ( $attributes ) {
		$code = '';
		$found_class = false;
		reset( $attributes );
		while( list( $key, $value ) = each( $attributes ) ) {
			$code .= ' '.$key.'="'.$value.'"';
			if( $key == 'class' )
				$found_class = true;
		}
		if( $found_class=== false )
			$code .= ' class="'.next_color().'"';
		echo '<tr'.$code.'>';
	}

	function __row_stop ( $attributes ) {
		echo '</tr>';
	}

	function __cell_start ( $text, $attributes ) {
		$code = '';
		reset( $attributes );
		while( list( $key, $value ) = each( $attributes ) )
			$code .= ' '.$key.'="'.$value.'"';
		echo '<td'.$code.'>'.$text;
	}

	function __cell_stop ( $text, $attributes ) {
		echo '</td>';
	}
}

class tpl_BLANK {
	function __header ( $width, $title ) {
		echo '<table width="'.$width.'" border="0" cellpadding="0" cellspacing="0" align="center">';
	}

	function __footer ( $width, $title ) {
		echo '</table>';
	}

	function __row_start ( $attributes ) {
		echo '<tr>';
	}

	function __row_stop ( $attributes ) {
		echo '</tr>';
	}

	function __cell_start ( $text, $attributes ) {
		$code = '';
		reset( $attributes );
		while( list( $key, $value ) = each( $attributes ) )
			$code .= ' '.$key.'="'.$value.'"';
		echo '<td'.$code.'>'.$text;
	}

	function __cell_stop ( $text, $attributes ) {
		echo '</td>';
	}
}
?>