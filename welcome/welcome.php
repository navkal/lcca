<?php
  // Copyright 2022 Energize Lawrence.  All rights reserved.

  include $_SERVER["DOCUMENT_ROOT"]."/util/security.php";

  require_once $_SERVER["DOCUMENT_ROOT"]."/util/format_event.php";


  function showFeaturedAnnouncement()
  {
?>
    <div class="pl-4 py-2">


      <div class="h5 pl-1" style="padding-left: 20px; padding-top: 70px;">
        Learn about it <a href="/?page=overview" >here</a>,
      </div>

      <div class="h5 pl-1">
        and stay tuned...
      </div>

      <!-- >>> More lines -- >
      <div class="h6 py-1">
      </div>
      <!-- <<< More lines -->

    </div>
<?php
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
    background-image: url( "welcome/welcome.svg" );
    background-position: center top;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
    background-color: #e6e7e8;  // Matches color of overscroll area to gray outlines in welcome.svg
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

  .cca
  {
    max-height: 70px;
  }

  @media( max-width: 767px )
  {
    .cca
    {
      max-height: 50px;
    }
  }
</style>

<div class="jumbotron jumbotron-fluid">
  <div class="container">

    <!-- Title -->
    <h4>
      Let's bring
    </h4>
    <h3 class="pl-2">
      <span class="font-weight-bold"><a href="/?page=overview" class="dark-link" ><i>Community Choice Aggregation</i></a></span>
    </h3>
    <h4 class="pl-4">
      to Lawrence.
    </h4>

    <!-- Featured announcement -->
    <?php
      showFeaturedAnnouncement();
    ?>

    <!-- Coming events -->
    <?php
      showComingEvents( false );
    ?>

    <!-- Past events -->
    <?php
      // showPastEvents();
    ?>


    <!-- Link to sign-up form -->
    <!-- >>> Commented out until we have a sign-up form -- >
    <h6 class="py-2">
      <a href="https://docs.google.com/forms/d/e/1FAIpQLScP7_LHHiBiykztDq8usdPlBrmZCGDSoFgIJYOxIUuwByxegw/viewform?usp=pp_url&entry.238260412=No&entry.1029755686=No" target="_blank" >Sign up</a> for Lawrence CCA updates.
    </h6>
    <!-- <<< Commented out until we have a sign-up form -->

    <!-- Vertical space so text can be scrolled above background clip art -->
    <div style="height:1000px">

  </div>
</div>
