--TEST--
Bug #75357 (segfault loading WordPress wp-admin)
--INI--
bcgen.enable=1
bcgen.optimization_level=-1
--SKIPIF--
<?php require_once('skipif.inc'); ?>
--FILE--
<?php

function wp_slash( $value ) {
	if ( is_array( $value ) ) {
		foreach ( $value as $k => $v ) {
			if ( is_array( $v ) ) {
				$value[$k] = wp_slash( $v );
			} else {
				$value[$k] = addslashes( $v );
			}
		}
	} else {
		$value = addslashes( $value );
	}

	return $value;
}

function addslashes_gpc($gpc) {
	if ( get_magic_quotes_gpc() )
		$gpc = stripslashes($gpc);

	return wp_slash($gpc);
}

var_dump(addslashes_gpc(array(array("test"))));
?>
--EXPECT--
array(1) {
  [0]=>
  array(1) {
    [0]=>
    string(4) "test"
  }
}
