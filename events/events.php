<?php
  // Copyright 2020 Energize Andover.  All rights reserved.

  include $_SERVER["DOCUMENT_ROOT"]."/util/security.php";

  require_once $_SERVER["DOCUMENT_ROOT"]."/util/format_event.php";
?>

<style>
  .dark-link
  {
    color: #495057;
  }
</style>

<div class="container">

  <div class="h5 pt-2 pb-3">
    Coming Events
  </div>

  <div class="list-group" >
<?php
  // Iterate through table of events
  $nShown = 0;
  foreach ( $aEvents as $aEvent )
  {
    $nShown += formatEvent( $aEvent );
  }

  if ( $nShown == 0 )
  {
?>
    <div class="alert alert-secondary">
      No events found.
    </div>
<?php
  }
?>
  </div>

</div>
