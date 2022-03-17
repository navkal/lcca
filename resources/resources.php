<?php
  // Copyright 2022 Energize Lawrence.  All rights reserved.

  include $_SERVER["DOCUMENT_ROOT"]."/util/security.php";

  $aLinks =
  [
    [
      'url' => 'https://www.mass.gov/info-details/municipal-aggregation',
      'title' => 'Massachusetts Department of Public Utilities (DPU)',
      'descr' => 'DPU explains Community Choice Aggregation (a.k.a. Municipal Aggregation).'
    ],
    [
      'url' => 'http://energyswitchma.gov/#/about/understandingbill',
      'title' => 'Understanding your electric bill',
      'descr' => 'Energy Switch MA explains your electric bill.'
    ],
    [
      'url' => 'http://www.greenenergyconsumers.org/aggregation',
      'title' => 'Green Energy Consumers Alliance (GECA)',
      'descr' => 'GECA explains green aspects of CCA.'
    ],
    [
      'url' => 'https://www.mapc.org/our-work/expertise/clean-energy/green-municipal-aggregation/',
      'title' => 'Metropolitan Area Planning Council (MAPC)',
      'descr' => 'How to start a Community Choice Aggregation program'
    ],
    [
      'url' => 'https://www.nationalgridus.com/media/pdfs/billing-payments/electric-rates/ma/resitable.pdf',
      'title' => NGBS . ' pricing',
      'descr' => NGBS . ' rates since November, 2004'
    ],
    [
      'url' => 'https://www.mass.gov/news/ag-healey-calls-for-shut-down-of-individual-residential-competitive-supply-industry-to-protect',
      'title' => 'Office of MA Attorney General',
      'descr' => 'AG Maura Healey decries agressive sales tactics and false promises by TPS industry.'
    ],
    [
      'url' => 'https://commonwealthmagazine.org/opinion/municipal-electricity-aggregation-really-works/',
      'title' => 'Commonwealth Magazine',
      'descr' => 'Newton Mayor Ruthanne Fuller explains benefits of CCA program (Dec. 29, 2018).'
    ],
    [
      'url' => 'https://photos.app.goo.gl/eZVi4Kdxn8vqawsA6',
      'title' => 'Massachusetts Climate Action Network TV Show',
      'descr' => 'Panelists from Lexington, Brookline, and MAPC discuss benefits of CCA.'
    ],
    [
      'url' => 'https://www3.epa.gov/carbon-footprint-calculator/',
      'title' => 'US EPA Carbon Footprint Calculator',
      'descr' => 'Environmental Protection Agency (EPA) estimates your household carbon footprint.'
    ],
  ];
?>

<style>
.list-group-item
{
  border: none;
}
.text-title
{
  color: #397947;
}
</style>

<div class="container">


  <div class="h5 py-2 list-group-item">
    Learn about Community Choice Aggregation
  </div>

  <?php
    foreach ( $aLinks as $aLink )
    {
      ?>

      <div class="list-group">
        <a href="<?=$aLink['url']?>" target="_blank" class="list-group-item list-group-item-action">
          <div class="text-title">
            <?=$aLink['title']?>
          </div>
          <small>
            <?=$aLink['descr']?>
          </small>
        </a>
      </div>

      <?php
    }
  ?>

</div>
