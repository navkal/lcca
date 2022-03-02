<?php
  // Copyright 2020 Energize Andover.  All rights reserved.

  include $_SERVER["DOCUMENT_ROOT"]."/util/security.php";

  require_once $_SERVER["DOCUMENT_ROOT"]."/util/format_event.php";


  function showFeaturedAnnouncement()
  {
    // --> --> Hard-coded.  Remove later.  --> -->
?>
    <hr/>
    <div class="pl-4 py-2">
      <div class="h5 py-1">
        Congratulations, Andover!
      </div>

      <div class="h6 py-1">
        Town Meeting passed Article 28 with overwhelming support.
      </div>

      <div class="h6 py-1">
        Thanks to all who participated!
      </div>

    </div>
    <hr/>
<?php
    // <-- <-- Hard-coded.  Remove later.  <-- <--
  }


  function showPastEvents()
  {
    // --> --> Hard-coded.  Remove later.  --> -->
?>
    <hr/>
    <div class="pl-4">
      <h5>
        Past Events
      </h5>
      <div class="list-group">
        <a href="https://andoverma.gov/DocumentCenter/View/8102" target="_blank">
          <div style="color:#397947">
            <b>
              Presentation - June 15, 2020
            </b>
          </div>
        </a>
        <a href="https://andover.vod.castus.tv/vod/?video=c3b22398-aabf-4f0c-ac22-fd4df6cb3834" target="_blank">
          <div style="color:#397947">
            <b>
              Public forum - May 27, 2020
            </b>
          </div>
        </a>
      </div>
    </div>
    <hr/>
<?php
    // <-- <-- Hard-coded.  Remove later.  <-- <--
  }


  function showComingEvents( $bLinkToAll )
  {
    global $aEvents;

    // Find next event to be shown
    $aNextEvent = null;
    for ( $iEvent = 0; ( $iEvent < count( $aEvents ) ) && ( $aNextEvent == null ); $iEvent ++ )
    {
      if ( $aEvents[$iEvent]['show'] )
      {
        $aNextEvent = $aEvents[$iEvent];
      }
    }

    // Show next event, if any
    if ( $aNextEvent )
    {
?>
      <!-- Coming event -->
      <hr/>
      <div class="pl-4">
        <h6>
          <u>Coming Event</u>
        </h6>
        <?php
          formatEvent( $aNextEvent, false );
        ?>
      </div>
      <hr/>

      <?php
      if ( $bLinkToAll )
      {
      ?>
        <!-- Link to Events page -->
        <h6>
          <a href="/?page=events">All Events</a>
        </h6>
<?php
      }
    }
  }
?>

<style>
  body
  {
    background-image: url( "welcome/banner.jpg" );
    background-position: center top;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
  }

  .jumbotron
  {
    padding-top: .75rem;
    background-color: transparent;
    color: #384897;
  }

  .dark-link
  {
    color: #384897;
  }

  hr
  {
    border-top: 1px dotted #384897;
  }

  .event-text-alert
  {
    font-weight: bold;
    color: red;
  }

  .event-text-general
  {
    font-weight: 500;
  }

  .event-text-topic
  {
    font-weight: 500;
    color: #006600;
    font-size: 1.25rem;
  }

</style>

<div class="jumbotron jumbotron-fluid">
  <div class="container">

    <!-- Title -->
    <h4>
      Let's bring
    </h4>
    <h3>
      <span class="font-weight-bold"><a href="/?page=overview" class="dark-link" ><i>Community Choice Aggregation</i></a></span>
    </h3>
    <h4>
      to Andover.
    </h4>

    <!-- Past events -->
    <?php
      //showPastEvents();
    ?>

    <!-- Featured announcement -->
    <?php
      showFeaturedAnnouncement();
    ?>

    <!-- Link to sign-up form -->
    <h6 class="py-2">
      <a href="https://docs.google.com/forms/d/e/1FAIpQLScP7_LHHiBiykztDq8usdPlBrmZCGDSoFgIJYOxIUuwByxegw/viewform?usp=pp_url&entry.238260412=No&entry.1029755686=No" target="_blank" >Sign up</a> for Andover CCA updates.
    </h6>

    <!-- Coming events -->
    <?php
      showComingEvents( false );
    ?>

    <!-- Vertical space so text can be scrolled above background clip art -->
    </br>
    </br>
    </br>
    </br>
    </br>
    </br>

  </div>
</div>
