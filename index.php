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
  $( document ).ready( modifyFooter );
  function modifyFooter()
  {
    // Adjust height
    $( '#footer' ).css( 'padding-top', '5px' )
    $( '#footer' ).css( 'padding-bottom', '6px' )

    // Remove border
    $('#footer').css( 'border', 'none' );

    // Remove background color
    $('#footer.bg-light').removeClass( 'bg-light' );
  }
</script>
