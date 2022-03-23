<?php
  // Copyright 2022 Energize Lawrence.  All rights reserved.
?>

<?php
  define( 'BOOTSTRAP_VERSION', '_4' );
  require_once '../common/libraries' . BOOTSTRAP_VERSION . '.php';
  define ( 'NG', 'National Grid' );
  define ( 'BS', 'Basic Service' );
  define ( 'NGBS', NG . ' ' . BS );
  include "../common/main.php";
?>

<script>
  $( document ).ready( setupNavbars );
  function setupNavbars()
  {
    // Reload brand icon from svg source
  $( '.navbar-brand img' ).css( 'height', '33px' );
    $( '.navbar-brand img' ).attr( 'src', 'brand.svg' );

    // Adjust footer height, border, background color
    $( '#footer' ).css( 'padding-top', '5px' )
    $( '#footer' ).css( 'padding-bottom', '7px' )
    $('#footer').css( 'border', 'none' );
    $('#footer.bg-light').removeClass( 'bg-light' );
  }
</script>
