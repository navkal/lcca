<?php
  // Copyright 2020 Energize Andover.  All rights reserved.

  $aEvents =
  [
   [
      'show' => false,
      'when' =>
      [
        'text' => 'Wednesday, September 9, 7 - 8 pm',
        'class' => 'event-text-general',
        'link' => '',
      ],
      'where' =>
      [
        'text' => 'WebEx Webinar, registration required',
        'class' => 'event-text-general',
        'link' => 'https://andover.webex.com/andover/j.php?RGID=r65476dbac2c75e83f6197bd4459435fc',
      ],
      'topic' =>
      [
        'text' => 'Virtual Public Forum on CCA',
        'class' => 'event-text-topic',
        'link' => 'https://andoverma.gov/Calendar.aspx?EID=4485&month=9&year=2020&day=9&calType=0',
      ],
      'topic_details' =>
      [
        'Followed by Q&A',
      ],
      'presenters' =>
      [
        [
          'text' => 'Michael Lindstrom, Deputy Town Manager',
          'class' => 'event-text-general',
          'link' => 'https://andoverma.gov/directory.aspx?EID=128',
        ],
        [
          'text' => 'Joyce Losick-Yang, PhD, Sustainability Coordinator',
          'class' => 'event-text-general',
          'link' => 'https://andoverma.gov/816/Sustainability',
        ],
      ],
      'sponsors' =>
      [
      ],
    ],
    [
      'show' => false,
      'when' =>
      [
        'text' => 'Saturday, September 12, 9:30 am',
        'class' => 'event-text-general',
        'link' => '',
      ],
      'where' =>
      [
        'text' => 'West Middle School Field',
        'class' => 'event-text-general',
        'link' => 'https://goo.gl/maps/zvzbhHNe1zzHriwo6',
      ],
      'topic' =>
      [
        'text' => 'Annual Town Meeting',
        'class' => 'event-text-topic',
        'link' => 'https://andoverma.gov/DocumentCenter/View/8309/2020-ATM-Fincom-Report_August-20_Final-Submission-to-Printer?bidId=',
      ],
      'topic_details' =>
      [
        'Vote on Article 28 - Community Choice Aggregation',
        'Details on page 70'
      ],
      'presenters' =>
      [
      ],
      'sponsors' =>
      [
      ],
    ],
  ];


  function formatOptionalLink( $aItem )
  {
    if ( $aItem['link'] )
    {
?>
      <a href="<?=$aItem['link']?>" target="_blank" class="dark-link">
<?php
    }
?>
    <span class="<?=$aItem['class']?>" >
      <?=$aItem['text']?>
    </span>
<?php
    if ( $aItem['link'] )
    {
?>
      </a>
<?php
    }
  }


  function formatEvent( $aEvent, $bList=true )
  {
    if ( $aEvent['show'] )
    {
      $sDtClass = 'class="col-sm-2"';
      $sDdClass = 'class="col-sm-10"';
      $sBulletCode = '&#9702';
      $sEventClass = $bList ? 'class="list-group-item list-group-item-action"' : '';
?>
      <div <?=$sEventClass?> >

        <dl class="row">

          <!-- When -->
          <dt <?=$sDtClass?> >
            When
          </dt>

          <dd <?=$sDdClass?> >
            <?=formatOptionalLink($aEvent['when'])?>
          </dd>

          <!-- Where, with optional link -->
          <dt <?=$sDtClass?> >
            Where
          </dt>
          <dd <?=$sDdClass?> >
            <?=formatOptionalLink($aEvent['where'])?>
          </dd>

          <!-- Topic, with optional link and details -->
          <dt <?=$sDtClass?> >
            Topic
          </dt>
          <dd <?=$sDdClass?> >
            <?=formatOptionalLink($aEvent['topic'])?>
<?php
            $sBullet = ( count( $aEvent['topic_details'] ) > 1 ) ? $sBulletCode : '';
            foreach ( $aEvent['topic_details'] as $sLine )
            {
?>
              <div>
                <small class="event-text-general" >
                  <?=$sBullet?> <?=$sLine?>
                </small>
              </div>
<?php
            }
?>
          </dd>

          <!-- Presenters, optional -->
<?php
          if ( count( $aEvent['presenters'] ) )
          {
?>
            <dt <?=$sDtClass?> >
              Presenters
            </dt>
            <dd <?=$sDdClass?> >
<?php
              foreach ( $aEvent['presenters'] as $aItem )
              {
?>
                <div>
                  <?=formatOptionalLink($aItem)?>
                </div>
<?php
              }
?>
            </dd>

<?php
          }
?>

          <!-- Sponsosrs, optional -->
<?php
          if ( count( $aEvent['sponsors'] ) )
          {
?>
            <dt <?=$sDtClass?> >
              Sponsors
            </dt>

            <dd <?=$sDdClass?> >
<?php
              foreach ( $aEvent['sponsors'] as $aItem )
              {
?>
                <div>
                  <?=formatOptionalLink($aItem)?>
                </div>
<?php
              }
?>
            </dd>
<?php
          }
?>
        </dl>
      </div>
<?php
    }

    return $aEvent['show'];
  }
?>

<style>
  .event-text-topic
  {
    color: #006600;
    font-size: 1.25rem;
  }

  .event-text-alert
  {
    color: red;
  }
</style>