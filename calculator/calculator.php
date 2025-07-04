<?php
  // Copyright 2022 Energize Lawrence.  All rights reserved.

  include $_SERVER["DOCUMENT_ROOT"]."/util/security.php";

  $g_aSampleCustomers =
  [
    [
      'description' => '2-bedroom apartment with heat pump',    // Tenant
      'start_month' => '12.23',
      'readings' => [ 1401, 1618, 1748, 1238,  767,  371,  418,  557,  755,  472,  392,  335,  607 ]
    ],
    [
      'description' => '3-bedroom apartment with heat pump',    // Owner
      'start_month' => '12.23',
      'readings' => [ 1254, 1026,  958, 1152,  523,  630,  601,  617,  655,  469,  424,  629, 1032 ]
    ],
    [
      'description' => '2-bedroom apartment',                   // Clarke St
      'start_month' => '11.23',
      'readings' => [  772,  898, 1116,  732,  660,  584,  572,  695,  883,  931,  686,  592,  165 ]
    ],
    [
      'description' => '2-bedroom apartment',                   // Methuen
      'start_month' => '1.24',
      'readings' => [  417,  341,  254,  201,  183,  206,  298,  466,  245,  209,  198,  386,  558 ]
    ],
    [
      'description' => '4-bedroom house, A/C, electric vehicle',
      'start_month' => '5.18',
      'readings' => [ 1131, 1828, 2295, 2397, 1751, 1608, 1783, 2222, 2330, 2158, 1983, 1960, 1591 ]
    ],
    [
      'description' => 'Small business in town center',
      'start_month' => '1.19',
      'readings' => [  1475,  1361,  1279,  1329,  1451,  1288,  1578,  1972, 1822, 1331, 1119,  1268,  1458 ]
    ],
  ];
?>

<style>
.tooltip-inner
{
  background-color:#006600;
}

.table-backdrop
{
  padding: 3px;
  background-color: #f6fbf7;
  margin: auto;
}

.ng-row
{
  background-color: #ffffe6;
}

div.error
{
  color: #b30000;
}

input.error
{
  border: solid 1px #b30000;
  color: #b30000;
}
</style>

<div class="container">

  <div class="h5 py-2">
    What if Lawrence were to adopt CCA?
  </div>

  <p>
    CCA has the potential to increase your use of renewable energy while reducing your electricity costs.  Use the <i>CCA Bill Calculator</i> below to assess the potential impact of CCA on your electric bill.
  </p>
  <button type="button" id="help-button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#help-modal">
    Instructions
  </button>
  <hr/>

  <div class="h6 pb-3">
    CCA Bill Calculator
  </div>

  <!-- Input -->
  <form action="javascript:makeOutput();" style="display:none">

    <!-- Sample customers -->
    <div class="form-group row">
      <div class="col-3 col-md-2">
        <label>Sample Customers</label>
      </div>
      <div class="col-9 col-md-10">
        <div class="btn-group btn-group-sm">
          <?php
            foreach ( $g_aSampleCustomers as $iCustomer => $tCustomer )
            {
              $iCustomerNumber = $iCustomer + 1;
              ?>
                <button type="button" id="customer-<?=$iCustomerNumber?>" class="btn btn-outline-secondary customer-button" data-toggle="tooltip" data-html="true" title="Sample Customer <?=$iCustomerNumber?>:<br><?=$tCustomer['description']?>" ><?=$iCustomerNumber?></button>
              <?php
            }
          ?>
        </div>
      </div>
    </div>

    <!-- Dropdown to select start month -->
    <div class="form-group row">
      <div class="col-3 col-md-2">
        <label for="start-month">Start Month</label>
      </div>

      <div class="col-9 col-md-10">
        <select id="start-month" class="form-control form-control-sm" >
          <?php

            // Get upper boundary: this month last year
            $iTime = time();
            $iThisMonth = intval( date( 'm', $iTime ) );
            $iLastYear = intval( date( 'y', $iTime ) ) - 1;

            // Initialize month and year counters, establishing earliest start date available in dropdown
            $iMonth = 1;
            $iYear = 18;

            // Generate dropdown options
            do
            {
              // Format option display text
              $sOptionText = date( 'M', mktime( 0, 0, 0, $iMonth, 1 ) ) . ' ' . $iYear;
              $sOptionValue = $iMonth . '.' . $iYear;

              // Determine whether this is the last option in the dropdown
              $bLastOption = ( $iYear == $iLastYear ) && ( $iMonth == $iThisMonth );

              // Echo option
              echo( '<option ' . ( $bLastOption ? 'selected' : '' ) . ' value="' . $sOptionValue . '" >' . $sOptionText . '</option>' );

              // Increment month and year counters
              $iMonth ++;
              if ( $iMonth > 12 )
              {
                $iMonth = 1;
                $iYear ++;
              }
            }
            while( ! $bLastOption );
          ?>

        </select>
      </div>
    </div>

    <!-- kWh input fields -->
    <?php
      for ( $iRow = 1; $iRow <= 7; $iRow ++ )
      {
        $sId1 = 'kwh-' . $iRow;
        $sId2 = 'kwh-' . ( $iRow + 7 );
    ?>
        <div class="form-group row">
          <div class="col-2 col-md-1">
            <label for="<?=$sId1?>" class="col-form-label col-form-label-sm kwh-label" ></label>
          </div>
          <div class="col-4 col-md-4">
            <input id="<?=$sId1?>" type="text" class="form-control form-control-sm kwh-input" placeholder="kWh" autocomplete="off">
          </div>
          <div class="d-none d-md-block col-md-2">
          </div>
          <div class="col-2 col-md-1">
            <label for="<?=$sId2?>" class="col-form-label col-form-label-sm kwh-label"></label>
          </div>
          <div class="col-4 col-md-4">
            <input id="<?=$sId2?>" type="text" class="form-control form-control-sm kwh-input" placeholder="kWh" autocomplete="off">
          </div>
        </div>
    <?php
      }
    ?>

    <!-- Buttons -->
    <div id="form-buttons" class="row pt-3 pb-2">
      <div class="col text-center">
        <button id="calculate-button" type="submit" class="btn btn-primary mr-2" >
          Calculate
        </button>
        <button id="clear-button" type="button" class="btn btn-danger ml-2" >
          Clear
        </button>
      </div>
    </div>

  </form>

  <div id="error-message" class="alert alert-danger"  style="display:none" role="alert">
    There are errors.
  </div>
</div>


<!-- Output -->
<div id="output" style="display:none" >

  <div class="container-fluid text-center mb-1" >
    <div id="which-customer">
    </div>
    <div id="total-kwh">
    </div>

    <div class="row justify-content-center pt-2">
      <div class="col-auto">
        <table class="table table-sm">
          <tr>
            <td><small>CCA options shown</small></td>
            <td class="text-right" >
              <small>
                <span id="options-count" class="font-weight-bold"></span>
              </small>
            </td>
          </tr>
          <tr>
            <td><small>Average annual savings</small></td>
            <td class="text-right" >
              <small>
                <span id="average-savings" class="font-weight-bold"></span>
              </small>
            </td>
          </tr>
          <tr>
            <td><small>Average local renewable content</small></td>
            <td class="text-right" >
              <small>
                <span id="average-green" class="font-weight-bold"></span>
              </small>
            </td>
          </tr>
          <tr>
            <td><small>Median local renewable content</small></td>
            <td class="text-right" >
              <small>
                <span id="median-green" class="font-weight-bold"></span>
              </small>
            </td>
          </tr>
        </table>
      </div>
    </div>

  </div>

  <div class="card table-backdrop">
    <div class="container-fluid" >
      <table id="cca-table" class="tablesorter" >
        <thead>
          <tr>
            <th data-toggle="tooltip" title="Town and CCA contract name" class="filter-match" >
              CCA Option
            </th>
            <th data-toggle="tooltip" title="Energy component of electric bill" >
              Annual Cost
            </th>
            <th data-toggle="tooltip" title="Difference relative to <?=NGBS?>" >
              Annual Savings
            </th>
            <th data-toggle="tooltip" title="Cost per kWh" >
              Rate
            </th>
            <th data-toggle="tooltip" title="Percent of total energy mix derived from renewable sources located in New England" >
              Local Renewable Content
            </th>
            <!---------------------- Replace with GHG math ---------------------- >
            <th data-toggle="tooltip" title="Percent of total energy mix derived from renewable sources" >
              Total Renewable Content
            </th>
            <!-------------------------------------------------------------------->
            <th data-toggle="tooltip" title="Mediator between town government and bulk energy supplier" class="filter-match" >
              Broker
            </th>
            <th data-toggle="tooltip" title="First month of contract" >
              Start Month
            </th>
            <th data-toggle="tooltip" title="Last month of contract" >
              End Month
            </th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Footnotes -->
  <div class="container-fluid pt-3" >
    <p>
      <small class="text-muted">
        (D) - Default option for the community
      </small>
    </p>
    <hr/>
    <p>
      <small class="text-muted">
        <h6>
          Note on Renewable Content
        </h6>

        We could not calculate the percentages of
        <i>
          Local Renewable Content
        </i>
        precisely in some cases, because of uncertainties or ambiguities in the online descriptions of contract terms.

        In those instances, the percentages shown reflect our best estimate based on available data.
      </small>
    </p>
  </div>
</div>


<!-- Help modal dialog -->
<div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">CCA Bill Calculator</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <small>
          <p>
            The CCA Bill Calculator uses your meter readings to show what you would have paid under actual CCA contracts negotiated by nearby towns.
          </p>

          <div>
            <b>
              Instructions
            </b>
            <p>
              View results for actual Sample Customers in Lawrence by clicking the numbered buttons, or follow the steps below to enter readings from your own bill:
            </p>
            <ol>
              <li>
                Find the <i>Electric Usage History</i> on your <?=NG?> bill.
              </li>
              <li>
                Set <b>Start Month</b> to the first month in the <i>Electric Usage History</i>.
              </li>
              <li>
                Enter monthly <i>kWh</i> readings as listed in the <i>Electric Usage History</i>.
              </li>
              <li>
                Click <b>Calculate</b>.
              </li>
            </ol>
          </div>
        </small>

        <img src="calculator/bill.png" class="img-fluid" ></img>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  var g_iCustomer = 0;
  var g_aSampleCustomers = JSON.parse( '<?=json_encode( $g_aSampleCustomers )?>' );

  // Determine minimum standard for current year (https://www.mass.gov/doc/minimum-standards)
  var g_tMinimumLocalGreen =
  {
    2019: 14,
    2020: 16,
    2021: 18,
    2022: 20,
    2023: 22,
    2024: 24,
    2025: 27
  };
  var g_iCurrentYear = new Date().getFullYear()
  if ( g_iCurrentYear in g_tMinimumLocalGreen )
  {
    var g_nMinimumLocalGreen = g_tMinimumLocalGreen[g_iCurrentYear];
  }
  else
  {
    console.log( 'Need RPS Minimum Standard for year ' + g_iCurrentYear  + '. See https://www.mass.gov/doc/minimum-standards' );
    g_nMinimumLocalGreen = -999;
  }
  var g_nMinimumTotalGreen = g_nMinimumLocalGreen;

  var g_tCcaOptions =
  {
    'Acton Power Choice Basic':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/acton/options-pricing',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-12',
      end: '2026-12',
      rate: 14.411
    },
    'Acton Power Choice Standard':
    {
      is_default: true,
      url: 'https://masspowerchoice.com/acton/options-pricing',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: g_nMinimumTotalGreen + 45,
      local: g_nMinimumLocalGreen + 45,
      start: '2023-12',
      end: '2026-12',
      rate: 16.031
    },
    'Acton Power Choice Green':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/acton/options-pricing',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 100,
      local: 100,
      start: '2023-12',
      end: '2026-12',
      rate: 17.039
    },
   'Andover Basic':
    {
      is_default: false,
      url: 'https://acp.andoverma.gov/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-06',
      end: '2027-12',
      rate: 13.286
    },
   'Andover Standard':
    {
      is_default: true,
      url: 'https://acp.andoverma.gov/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 15,
      local: g_nMinimumLocalGreen + 15,
      start: '2024-06',
      end: '2027-12',
      rate: 13.905
    },
   'Andover Ultimate':
    {
      is_default: false,
      url: 'https://acp.andoverma.gov/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: 100,
      local: 100,
      start: '2024-06',
      end: '2027-12',
      rate: 14.637
    },
    'Arlington Basic':
    {
      is_default: false,
      url: 'https://ace.arlingtonma.gov/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-11',
      end: '2027-12',
      rate: 13.380
    },
    'Arlington Local Green':
    {
      is_default: true,
      url: 'https://ace.arlingtonma.gov/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: 100,
      local: 100,
      start: '2024-11',
      end: '2027-12',
      rate: 16.230
    },
    'Arlington Local Greenest':
    {
      is_default: false,
      url: 'https://ace.arlingtonma.gov/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 100,
      local: g_nMinimumLocalGreen + 100,
      start: '2024-11',
      end: '2027-12',
      rate: 17.430
    },
    'Ashland':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/ashland/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen + 25,
      local: g_nMinimumLocalGreen + 25,
      start: '2023-12',
      end: '2025-12',
      rate: 15.988
    },
    'Bedford Basic':
    {
      is_default: false,
      url: 'https://bedfordcca.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-11',
      end: '2027-11',
      rate: 13.300
    },
    'Bedford Mid':
    {
      is_default: false,
      url: 'https://bedfordcca.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 15,
      local: g_nMinimumLocalGreen + 15,
      start: '2024-11',
      end: '2027-11',
      rate: 13.923
    },
    'Bedford Local Green':
    {
      is_default: true,
      url: 'https://bedfordcca.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: 100,
      local: 100,
      start: '2024-11',
      end: '2027-11',
      rate: 14.620
    },
    'Billerica Standard':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/billerica/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-01',
      end: '2026-01',
      rate: 14.011
    },
    'Billerica Optional':
    {
      is_default: false,
      url: 'https://colonialpowergroup.com/billerica/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen + 38,
      local: g_nMinimumLocalGreen,
      start: '2024-01',
      end: '2026-01',
      rate: 14.152
    },
    'Boston Optional Basic':
    {
      is_default: false,
      url: 'https://www.cityofbostoncce.com/',
      broker: 'Constellation NewEnergy',
      broker_url: 'https://www.constellation.com/solutions/for-your-home/governmental-aggregation/massachusetts-aggregation-programs/Boston.html',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-12',
      end: '2025-12',
      rate: 14.205
    },
    'Boston Standard':
    {
      is_default: true,
      url: 'https://www.cityofbostoncce.com/',
      broker: 'Constellation NewEnergy',
      broker_url: 'https://www.constellation.com/solutions/for-your-home/governmental-aggregation/massachusetts-aggregation-programs/Boston.html',
      green: g_nMinimumTotalGreen + 15,
      local: g_nMinimumLocalGreen + 15,
      start: '2023-12',
      end: '2025-12',
      rate: 14.805
    },
    'Boston Optional Green 100':
    {
      is_default: false,
      url: 'https://www.cityofbostoncce.com/',
      broker: 'Constellation NewEnergy',
      broker_url: 'https://www.constellation.com/solutions/for-your-home/governmental-aggregation/massachusetts-aggregation-programs/Boston.html',
      green: 100,
      local: 100,
      start: '2023-12',
      end: '2025-12',
      rate: 17.195
    },
    'Brookline Basic':
    {
      is_default: false,
      url: 'https://brooklinegreen.com/electricity-options/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-12',
      end: '2027-12',
      rate: 13.400
    },
    'Brookline Green':
    {
      is_default: true,
      url: 'https://brooklinegreen.com/electricity-options/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: 100,
      local: 100,
      start: '2024-12',
      end: '2027-12',
      rate: 14.681
    },
    'Brookline All Green':
    {
      is_default: false,
      url: 'https://brooklinegreen.com/electricity-options/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 100,
      local: g_nMinimumLocalGreen + 100,
      start: '2024-12',
      end: '2027-12',
      rate: 17.525
    },
    'Cambridge Economy Green':
    {
      is_default: false,
      url: 'http://masspowerchoice.com/cambridge',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-01',
      end: '2026-01',
      rate: 13.82
    },
    'Cambridge Standard Green':
    {
      is_default: true,
      url: 'http://masspowerchoice.com/cambridge',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 50,
      local: 50,
      start: '2024-01',
      end: '2026-01',
      rate: 14.81
    },
    'Cambridge 100% Green Plus':
    {
      is_default: false,
      url: 'http://masspowerchoice.com/cambridge',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 100,
      local: 100,
      start: '2024-01',
      end: '2026-01',
      rate: 16.82
    },
    'Carlisle Optional Basic':
    {
      is_default: false,
      url: 'https://colonialpowergroup.com/carlisle/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-01',
      end: '2026-01',
      rate: 15.025
    },
    'Carlisle Standard':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/carlisle/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen + 25,
      local: g_nMinimumLocalGreen + 25,
      start: '2024-01',
      end: '2026-01',
      rate: 15.989
    },
    'Carlisle Optional Green 100':
    {
      is_default: false,
      url: 'https://colonialpowergroup.com/carlisle/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: 100,
      local: 100,
      start: '2024-01',
      end: '2026-01',
      rate: 17.896
    },
    'Chelmsford Choice Basic':
    {
      is_default: true,
      url: 'https://masspowerchoice.com/chelmsford/your-options',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-11',
      end: '2025-11',
      rate: 14.843
    },
    'Chelmsford Choice Greener':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/chelmsford/your-options',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: g_nMinimumTotalGreen + 40,
      local: g_nMinimumLocalGreen + 40,
      start: '2023-11',
      end: '2025-11',
      rate: 16.415
    },
    'Chelmsford Choice Greenest':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/chelmsford/your-options',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 100,
      local: 100,
      start: '2023-11',
      end: '2025-11',
      rate: 17.849
    },
    'Dracut Standard':
    {
      is_default: true,
      url: 'https://masscea.com/dracut/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-12',
      end: '2025-11',
      rate: 15.784
    },
    'Dracut Plus':
    {
      is_default: false,
      url: 'https://masscea.com/dracut/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: 100,
      local: 100,
      start: '2023-12',
      end: '2025-11',
      rate: 17.372
    },
    'Gill Optional Green 5':
    {
      is_default: false,
      url: 'https://colonialpowergroup.com/gill-further-pricing/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen + 5,
      local: g_nMinimumLocalGreen + 5,
      start: '2024-01',
      end: '2026-01',
      rate: 14.354
    },
    'Gill Standard':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/gill-further-pricing/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen + 25,
      local: g_nMinimumLocalGreen + 25,
      start: '2024-01',
      end: '2026-01',
      rate: 15.136
    },
    'Gill Optional Green 100':
    {
      is_default: false,
      url: 'https://colonialpowergroup.com/gill-further-pricing/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: 100,
      local: 100,
      start: '2024-01',
      end: '2026-01',
      rate: 18.092
    },
    'Gloucester Basic':
    {
      is_default: false,
      url: 'https://gloucester-cea.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-12',
      end: '2027-12',
      rate: 13.786
    },
    'Gloucester Local Green':
    {
      is_default: true,
      url: 'https://gloucester-cea.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 15,
      local: g_nMinimumLocalGreen + 15,
      start: '2024-12',
      end: '2027-12',
      rate: 14.405
    },
    'Gloucester 100% Local Green':
    {
      is_default: false,
      url: 'https://gloucester-cea.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: 100,
      local: 100,
      start: '2024-12',
      end: '2027-12',
      rate: 15.065
    },
    'Hamilton Basic 0%':
    {
      is_default: false,
      url: 'https://hamiltoncca.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-12',
      end: '2025-11',
      rate: 14.950
    },
    'Hamilton Standard':
    {
      is_default: true,
      url: 'https://hamiltoncca.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 5,
      local: g_nMinimumLocalGreen + 5,
      start: '2023-12',
      end: '2025-11',
      rate: 15.148
    },
    'Hamilton Plus':
    {
      is_default: false,
      url: 'https://hamiltoncca.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: 100,
      local: 100,
      start: '2023-12',
      end: '2025-11',
      rate: 16.455
    },
    'Haverhill':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/haverhill/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-11',
      end: '2026-11',
      rate: 14.377
    },
    'Lancaster':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/lancaster/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-12',
      end: '2025-12',
      rate: 15.100
    },
    'Lawrence':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/lawrence/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2025-05',
      end: '2027-11',
      rate: 13.263
    },
    'Lexington Basic':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/lexington',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-12',
      end: '2025-12',
      rate: 13.087
    },
    'Lexington 100% Green':
    {
      is_default: true,
      url: 'https://masspowerchoice.com/lexington',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 100,
      local: g_nMinimumLocalGreen + 20,
      start: '2023-12',
      end: '2025-12',
      rate: 13.998
    },
    'Lexington New England Green':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/lexington',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 100,
      local: 100,
      start: '2023-12',
      end: '2025-12',
      rate: 16.654
    },
    'Lowell Optional Green 5':
    {
      is_default: false,
      url: 'https://colonialpowergroup.com/lowell/lowell-further-pricing/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen + 5,
      local: g_nMinimumLocalGreen + 5,
      start: '2024-12',
      end: '2027-12',
      rate: 13.655
    },
    'Lowell Standard':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/lowell/lowell-further-pricing/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen + 45,
      local: g_nMinimumLocalGreen + 45,
      start: '2024-12',
      end: '2027-12',
      rate: 15.255
    },
    'Lowell Optional Green 100':
    {
      is_default: false,
      url: 'https://colonialpowergroup.com/lowell/lowell-further-pricing/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: 100,
      local: 100,
      start: '2024-12',
      end: '2027-12',
      rate: 16.575
    },
    'Melrose Basic':
    {
      is_default: false,
      url: 'https://mcp.cityofmelrose.org/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-11',
      end: '2027-12',
      rate: 14.310
    },
    'Melrose Local Green':
    {
      is_default: true,
      url: 'https://mcp.cityofmelrose.org/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 10,
      local: g_nMinimumLocalGreen + 10,
      start: '2024-11',
      end: '2027-12',
      rate: 14.723
    },
    'Melrose Local Green 100%':
    {
      is_default: false,
      url: 'https://mcp.cityofmelrose.org/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 100,
      local: g_nMinimumLocalGreen + 100,
      start: '2024-11',
      end: '2027-12',
      rate: 15.599
    },
    'Methuen Standard':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/methuen-further-pricing/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-12',
      end: '2027-12',
      rate: 12.830
    },
    'Natick Basic/Brown':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/natick',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-12',
      end: '2025-12',
      rate: 14.680
    },
    'Natick Standard Green':
    {
      is_default: true,
      url: 'https://masspowerchoice.com/natick',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: g_nMinimumTotalGreen + 25,
      local: g_nMinimumLocalGreen + 25,
      start: '2023-12',
      end: '2025-12',
      rate: 15.561
    },
    'Natick 100% Green':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/natick',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 100,
      local: 100,
      start: '2023-12',
      end: '2025-12',
      rate: 17.474
    },
    'Newton Basic':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/newton',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-01',
      end: '2026-01',
      rate: 14.36
    },
    'Newton Standard':
    {
      is_default: true,
      url: 'https://masspowerchoice.com/newton',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 95,
      local: 95,
      start: '2024-01',
      end: '2026-01',
      rate: 17.18
    },
    'Newton 100% Green':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/newton',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 100,
      local: 100,
      start: '2024-01',
      end: '2026-01',
      rate: 17.26
    },
    'North Andover Standard':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/north-andover',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-10',
      end: '2025-10',
      rate: 15.543
    },
    'North Andover Optional':
    {
      is_default: false,
      url: 'https://colonialpowergroup.com/north-andover',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen + 41,
      local: g_nMinimumLocalGreen,
      start: '2023-10',
      end: '2025-10',
      rate: 15.751
    },
   'Quincy Basic':
    {
      is_default: false,
      url: 'https://quincycea.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-06',
      end: '2027-12',
      rate: 13.394
    },
   'Quincy Standard':
    {
      is_default: true,
      url: 'https://quincycea.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 10,
      local: g_nMinimumLocalGreen + 10,
      start: '2024-06',
      end: '2027-12',
      rate: 13.804
    },
   'Quincy Ultimate':
    {
      is_default: false,
      url: 'https://quincycea.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: 100,
      local: 100,
      start: '2024-06',
      end: '2027-12',
      rate: 14.737
    },
    'Sudbury Basic':
    {
      is_default: false,
      url: 'https://sudbury-cea.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-12',
      end: '2025-11',
      rate: 14.950
    },
    'Sudbury Standard':
    {
      is_default: true,
      url: 'https://sudbury-cea.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 10,
      local: g_nMinimumLocalGreen + 10,
      start: '2023-12',
      end: '2025-11',
      rate: 15.148
    },
    'Sudbury Plus':
    {
      is_default: false,
      url: 'https://sudbury-cea.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: 100,
      local: 100,
      start: '2023-12',
      end: '2025-11',
      rate: 16.455
    },
    'Tewksbury Optional Basic':
    {
      is_default: false,
      url: 'https://colonialpowergroup.com/tewksbury/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-12',
      end: '2026-12',
      rate: 12.855
    },
    'Tewksbury Standard':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/tewksbury/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen + 5,
      local: g_nMinimumLocalGreen + 5,
      start: '2024-12',
      end: '2026-12',
      rate: 13.056
    },
    'Tewksbury Optional Green 25':
    {
      is_default: false,
      url: 'https://colonialpowergroup.com/tewksbury/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen + 25,
      local: g_nMinimumLocalGreen + 25,
      start: '2024-12',
      end: '2026-12',
      rate: 13.859
    },
    'Tyngsborough Standard':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/tyngsborough/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-11',
      end: '2026-11',
      rate: 12.994
    },
    'Tyngsborough Optional':
    {
      is_default: false,
      url: 'https://colonialpowergroup.com/tyngsborough/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: 100,
      local: g_nMinimumLocalGreen,
      start: '2024-11',
      end: '2026-11',
      rate: 13.175
    },
    'Watertown Basic':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/watertown/options-prices',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-12',
      end: '2026-12',
      rate: 13.450
    },
    'Watertown Standard':
    {
      is_default: true,
      url: 'https://masspowerchoice.com/watertown/options-prices',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 100,
      local: g_nMinimumLocalGreen + 5,
      start: '2023-12',
      end: '2026-12',
      rate: 13.740
    },
    'Watertown 100% Green':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/watertown/options-prices',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 138,
      local: 100,
      start: '2023-12',
      end: '2026-12',
      rate: 15.900
    },
    'Westford Basic':
    {
      is_default: false,
      url: 'https://masscea.com/westford/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2023-12',
      end: '2025-11',
      rate: 15.784
    },
    'Westford Standard':
    {
      is_default: true,
      url: 'https://masscea.com/westford/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 10,
      local: g_nMinimumLocalGreen + 10,
      start: '2023-12',
      end: '2025-11',
      rate: 16.178
    },
    'Westford Plus':
    {
      is_default: false,
      url: 'https://masscea.com/westford/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: 100,
      local: 100,
      start: '2023-12',
      end: '2025-11',
      rate: 17.282
    },
    'Williamsburg':
    {
      is_default: true,
      url: 'https://colonialpowergroup.com/williamsburg/',
      broker: 'Colonial Power Group',
      broker_url: 'https://colonialpowergroup.com/',
      green: g_nMinimumTotalGreen + 41,
      local: g_nMinimumLocalGreen,
      start: '2023-11',
      end: '2025-11',
      rate: 14.391
    },
    'Winchester WinPower Basic':
    {
      is_default: false,
      url: 'https://winpowerma.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen,
      local: g_nMinimumLocalGreen,
      start: '2024-12',
      end: '2027-12',
      rate: 13.120
    },
    'Winchester WinPower Standard':
    {
      is_default: true,
      url: 'https://winpowerma.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: g_nMinimumTotalGreen + 25,
      local: g_nMinimumLocalGreen + 25,
      start: '2024-12',
      end: '2027-12',
      rate: 14.151
    },
    'Winchester WinPower 100':
    {
      is_default: false,
      url: 'https://winpowerma.com/',
      broker: 'Good Energy',
      broker_url: 'http://goodenergy.com/',
      green: 100,
      local: 100,
      start: '2024-12',
      end: '2027-12',
      rate: 14.403
    },
    'Worcester Standard Green':
    {
      is_default: true,
      url: 'https://masspowerchoice.com/worcester/options-prices',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: g_nMinimumTotalGreen + 40,
      local: g_nMinimumLocalGreen + 40,
      start: '2023-12',
      end: '2025-12',
      rate: 16.310
    },
    'Worcester 100% Green':
    {
      is_default: false,
      url: 'https://masspowerchoice.com/worcester/options-prices',
      broker: 'Mass Power Choice',
      broker_url: 'https://masspowerchoice.com/',
      green: 100,
      local: 100,
      start: '2023-12',
      end: '2025-12',
      rate: 17.730
    },
  };

  var g_tRatesNg =
  {
    'Jan 18':
    {
      '<?=NGBS?>': 12.673,
    },
    'Feb 18':
    {
      '<?=NGBS?>': 12.673,
    },
    'Mar 18':
    {
      '<?=NGBS?>': 12.673,
    },
    'Apr 18':
    {
      '<?=NGBS?>': 12.673,
    },
    'May 18':
    {
      '<?=NGBS?>': 10.87,
    },
    'Jun 18':
    {
      '<?=NGBS?>': 10.87,
    },
    'Jul 18':
    {
      '<?=NGBS?>': 10.87,
    },
    'Aug 18':
    {
      '<?=NGBS?>': 10.87,
    },
    'Sep 18':
    {
      '<?=NGBS?>': 10.87,
    },
    'Oct 18':
    {
      '<?=NGBS?>': 10.87,
    },
    'Nov 18':
    {
      '<?=NGBS?>': 13.718,
    },
    'Dec 18':
    {
      '<?=NGBS?>': 13.718,
    },
    'Jan 19':
    {
      '<?=NGBS?>': 13.718,
    },
    'Feb 19':
    {
      '<?=NGBS?>': 13.718,
    },
    'Mar 19':
    {
      '<?=NGBS?>': 13.718,
    },
    'Apr 19':
    {
      '<?=NGBS?>': 13.718,
    },
    'May 19':
    {
      '<?=NGBS?>': 10.793,
    },
    'Jun 19':
    {
      '<?=NGBS?>': 10.793,
    },
    'Jul 19':
    {
      '<?=NGBS?>': 10.793,
    },
    'Aug 19':
    {
      '<?=NGBS?>': 10.793,
    },
    'Sep 19':
    {
      '<?=NGBS?>': 10.793,
    },
    'Oct 19':
    {
      '<?=NGBS?>': 10.793,
    },
    'Nov 19':
    {
      '<?=NGBS?>': 13.957,
    },
    'Dec 19':
    {
      '<?=NGBS?>': 13.957,
    },
    'Jan 20':
    {
      '<?=NGBS?>': 13.957,
    },
    'Feb 20':
    {
      '<?=NGBS?>': 13.957,
    },
    'Mar 20':
    {
      '<?=NGBS?>': 13.957,
    },
    'Apr 20':
    {
      '<?=NGBS?>': 13.957,
    },
    'May 20':
    {
      '<?=NGBS?>': 9.898,
    },
    'Jun 20':
    {
      '<?=NGBS?>': 9.898,
    },
    'Jul 20':
    {
      '<?=NGBS?>': 9.898,
    },
    'Aug 20':
    {
      '<?=NGBS?>': 9.898,
    },
    'Sep 20':
    {
      '<?=NGBS?>': 9.898,
    },
    'Oct 20':
    {
      '<?=NGBS?>': 9.898,
    },
    'Nov 20':
    {
      '<?=NGBS?>': 12.388,
    },
    'Dec 20':
    {
      '<?=NGBS?>': 12.388,
    },
    'Jan 21':
    {
      '<?=NGBS?>': 12.388,
    },
    'Feb 21':
    {
      '<?=NGBS?>': 12.388,
    },
    'Mar 21':
    {
      '<?=NGBS?>': 12.388,
    },
    'Apr 21':
    {
      '<?=NGBS?>': 12.388,
    },
    'May 21':
    {
      '<?=NGBS?>': 9.707,
    },
    'Jun 21':
    {
      '<?=NGBS?>': 9.707,
    },
    'Jul 21':
    {
      '<?=NGBS?>': 9.707,
    },
    'Aug 21':
    {
      '<?=NGBS?>': 9.707,
    },
    'Sep 21':
    {
      '<?=NGBS?>': 9.707,
    },
    'Oct 21':
    {
      '<?=NGBS?>': 9.707,
    },
    'Nov 21':
    {
      '<?=NGBS?>': 14.821,
    },
    'Dec 21':
    {
      '<?=NGBS?>': 14.821,
    },
    'Jan 22':
    {
      '<?=NGBS?>': 14.821,
    },
    'Feb 22':
    {
      '<?=NGBS?>': 14.821,
    },
    'Mar 22':
    {
      '<?=NGBS?>': 14.821,
    },
    'Apr 22':
    {
      '<?=NGBS?>': 14.821,
    },
    'May 22':
    {
      '<?=NGBS?>': 11.491,
    },
    'Jun 22':
    {
      '<?=NGBS?>': 11.491,
    },
    'Jul 22':
    {
      '<?=NGBS?>': 11.491,
    },
    'Aug 22':
    {
      '<?=NGBS?>': 11.491,
    },
    'Sep 22':
    {
      '<?=NGBS?>': 11.491,
    },
    'Oct 22':
    {
      '<?=NGBS?>': 11.491,
    },
    'Nov 22':
    {
      '<?=NGBS?>': 33.891,
    },
    'Dec 22':
    {
      '<?=NGBS?>': 33.891,
    },
    'Jan 23':
    {
      '<?=NGBS?>': 33.891,
    },
    'Feb 23':
    {
      '<?=NGBS?>': 33.891,
    },
    'Mar 23':
    {
      '<?=NGBS?>': 33.891,
    },
    'Apr 23':
    {
      '<?=NGBS?>': 33.891,
    },
    'May 23':
    {
      '<?=NGBS?>': 14.115,
    },
    'Jun 23':
    {
      '<?=NGBS?>': 14.115,
    },
    'Jul 23':
    {
      '<?=NGBS?>': 14.115,
    },
    'Aug 23':
    {
      '<?=NGBS?>': 14.115,
    },
    'Sep 23':
    {
      '<?=NGBS?>': 14.115,
    },
    'Oct 23':
    {
      '<?=NGBS?>': 14.115,
    },
    'Nov 23':
    {
      '<?=NGBS?>': 18.213,
    },
    'Dec 23':
    {
      '<?=NGBS?>': 18.213,
    },
    'Jan 24':
    {
      '<?=NGBS?>': 18.213,
    },
    'Feb 24':
    {
      '<?=NGBS?>': 18.213,
    },
    'Mar 24':
    {
      '<?=NGBS?>': 18.213,
    },
    'Apr 24':
    {
      '<?=NGBS?>': 18.213,
    },
    'May 24':
    {
      '<?=NGBS?>': 18.213,
    },
    'Jun 24':
    {
      '<?=NGBS?>': 18.213,
    },
    'Jul 24':
    {
      '<?=NGBS?>': 18.213,
    },
    'Aug 24':
    {
      '<?=NGBS?>': 16.055,
    },
    'Sep 24':
    {
      '<?=NGBS?>': 16.055,
    },
    'Oct 24':
    {
      '<?=NGBS?>': 16.055,
    },
    'Nov 24':
    {
      '<?=NGBS?>': 16.055,
    },
    'Dec 24':
    {
      '<?=NGBS?>': 16.055,
    },
    'Jan 25':
    {
      '<?=NGBS?>': 16.055,
    },
    'Feb 25':
    {
      '<?=NGBS?>': 14.672,
    },
    'Mar 25':
    {
      '<?=NGBS?>': 14.672,
    },
    'Apr 25':
    {
      '<?=NGBS?>': 14.672,
    },
    'May 25':
    {
      '<?=NGBS?>': 14.672,
    },
    'Jun 25':
    {
      '<?=NGBS?>': 14.672,
    },
    'Jul 25':
    {
      '<?=NGBS?>': 14.672,
    },
    'Aug 25':
    {
      '<?=NGBS?>': 15.484,
    },
    'Sep 25':
    {
      '<?=NGBS?>': 15.484,
    },
    'Oct 25':
    {
      '<?=NGBS?>': 15.484,
    },
    'Nov 25':
    {
      '<?=NGBS?>': 15.484,
    },
    'Dec 25':
    {
      '<?=NGBS?>': 15.484,
    },
    'Jan 26':
    {
      '<?=NGBS?>': 15.484,
    },
};
// End g_tRatesNg

  var g_iTotalKwh = 0;

  $( document ).ready( onDocumentReady );

  function onDocumentReady()
  {
    // Set up event handlers
    $( window ).on( 'resize', resizeBackdrop );
    for ( var iCustomer = 1; iCustomer <= g_aSampleCustomers.length; iCustomer ++ )
    {
      $( '#customer-' + iCustomer ).on( 'click', createSampleCustomerHandler( iCustomer ) );
    }
    $( '#start-month' ).on( 'change', onChangeStartMonth );
    $( '.kwh-input' ).on( 'input', onInputKwhInput );
    $( '#clear-button' ).on( 'click', clearInput );
    $( '[data-toggle="tooltip"]' ).tooltip(
      {
        template: '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner" ></div></div>'
      }
    );

    // Hide the last label and input
    $( 'label[for="kwh-14"]' ).hide().removeClass( 'kwh-label' );
    $( '#kwh-14' ).hide().removeClass( 'kwh-input' );

    // Exclude first entry from calculations
    $( 'label[for="kwh-1"]' ).removeClass( 'kwh-label' );

    // Set the tab order
    initTabOrder();

    // Handle initial dropdown selection
    onChangeStartMonth();

    // Show the form
    $( 'form' ).show();

    // Initialize the table
    initTable();
  }

  function createSampleCustomerHandler( iCustomer )
  {
    return function() { loadCustomer( iCustomer ); };
  }

  function initTabOrder()
  {
    $( '#help-button' ).prop( 'tabindex', 1 );
    $( '.customer-button' ).prop( 'tabindex', 1 );
    $( '#start-month' ).prop( 'tabindex', 1 );

    var aInputs = $( '.kwh-input' );
    for ( var iInput = 0; iInput < aInputs.length; iInput ++ )
    {
      $( aInputs[iInput] ).prop( 'tabindex', ( iInput % 2 ) + 1 );
    }

    $( '#calculate-button' ).prop( 'tabindex', 10 );
    $( '#clear-button' ).prop( 'tabindex', 10 );
  }

  function onChangeStartMonth()
  {
    showOutput( false );

    var aMonthYear = $( '#start-month' ).val().split( '.' );
    var iStartMonth = aMonthYear[0];
    var iStartYear = aMonthYear[1];

    var aInputs = $( '.kwh-input' );
    var aMonths = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];
    for ( var iInput = 1; iInput <= aInputs.length; iInput ++ )
    {
      // Set next label
      var tLabel = $( "label[for='kwh-" + iInput + "']" );
      tLabel.html( aMonths[ iStartMonth - 1 ] + '&nbsp;' +  iStartYear );

      // Increment month and year counters
      iStartMonth = ( iStartMonth % 12 ) + 1;
      if ( iStartMonth == 1 )
      {
        iStartYear ++;
      }
    }
  }

  function onInputKwhInput( tEvent )
  {
    showOutput( false );

    var tTarget = $( tEvent.target );

    // Trim the input
    tTarget.val( tTarget.val().trim() );
    var sInput = tTarget.val();

    // Validate the input
    if ( ( sInput == '' ) || /^\d+$/.test( sInput ) )
    {
      highlightError( tTarget, false );
    }
    else
    {
      highlightError( tTarget, true );
    }
  }

  function initTable()
  {
    var tTableProps =
    {
      theme : 'green',
      widgets : [ 'uitheme', 'resizable', 'filter' ],
      widgetOptions :
      {
        resizable: true,
        filter_reset : '.reset',
        filter_cssFilter: 'form-control'
      }
    };

    // Set handler to complete initialization
    $( '#cca-table' ).on(
      'tablesorter-ready',
      function()
      {
        $( '#cca-table' ).off( 'tablesorter-ready' );
        $( '#cca-table' ).on( 'tablesorter-ready', hideTooltips );
        clearInput();
      }
    );

    // Initialize the tablesorter
    $( '#cca-table' ).tablesorter( jQuery.extend( true, { sortList: [[2,1]] }, tTableProps ) );

    // Set filter completion handler
    $( '#cca-table' ).on( "filterEnd", onFilterEnd );
  }

  function makeOutput()
  {
    showErrorMessage( false );

    if ( isInputReady() )
    {
      var nCostNg = calculateCost( '<?=NGBS?>' );

      var aCcaOptions = Object.keys( g_tCcaOptions );
      aCcaOptions.push( '<?=NGBS?>' );

      // Generate the table
      var sHtml = '';
      for ( var iCcaOption = 0; iCcaOption < aCcaOptions.length; iCcaOption ++ )
      {
        // CCA option
        var sCcaOption = aCcaOptions[iCcaOption];
        var nCostCcaOption = calculateCost( sCcaOption );
        var tCcaOption = ( sCcaOption in g_tCcaOptions ) ? ( g_tCcaOptions[sCcaOption] ) : null;
        if ( tCcaOption && tCcaOption.url )
        {
          sIsDefault = tCcaOption.is_default ? '<small class="text-muted">&nbsp;(D)</small>' : '';
          sHtml += '<tr>';
          sHtml += '<td>';
          sHtml += '<a href="' + tCcaOption.url + '" target="_blank" >' + sCcaOption + sIsDefault + '</a>';
        }
        else
        {
          sHtml += '<tr class="ng-row">';
          sHtml += '<td>';
          sHtml += '<a href="https://www.nationalgridus.com/media/pdfs/billing-payments/electric-rates/ma/resitable.pdf" target="_blank">' + sCcaOption + '<a>';
        }
        sHtml += '</td>';

        // Cost
        sHtml += '<td>';
        sHtml += '$' + nCostCcaOption;
        sHtml += '</td>';

        // Savings
        var nSavings = ( nCostNg - nCostCcaOption );
        var sClass = ( ( nSavings > 0 ) ? 'font-weight-bold text-success' : ( ( nSavings < 0 ) ? 'text-danger' : '' ) );
        var sSavings = ( sCcaOption == '<?=NGBS?>' ) ? '' : ' savings="' + nSavings + '"';
        sHtml += '<td class="' + sClass + '"' + sSavings + '>';
        sHtml += '$' + nSavings;
        sHtml += '</td>';

        // Rate
        var nRate = ( tCcaOption && tCcaOption.rate ) ? ( tCcaOption.rate / 100 ) : ( nCostCcaOption / g_iTotalKwh );
        sHtml += '<td>';
        sHtml += '$' + nRate.toFixed( 5 );
        sHtml += '</td>';

        // Local green
        var nLocalGreen = ( tCcaOption && tCcaOption.local ) ? tCcaOption.local : g_nMinimumLocalGreen;
        var sLocalGreen = ( sCcaOption == '<?=NGBS?>' ) ? '' : ' local_green="' + nLocalGreen + '"';
        sHtml += '<td ' + sLocalGreen + '>';
        sHtml += nLocalGreen + '%';
        sHtml += '</td>';

        // ----------------------> Replace with GHG math ----------------------> //
        // Total green
        // sHtml += '<td>';
        // sHtml += ( ( tCcaOption && tCcaOption.green ) ? tCcaOption.green : g_nMinimumTotalGreen ) + '%';
        // sHtml += '</td>';
        // <---------------------- Replace with GHG math <---------------------- //

        // Broker
        sHtml += '<td>';
        if ( tCcaOption && tCcaOption.broker )
        {
          if ( tCcaOption.broker_url )
          {
            sHtml += '<a href="' + tCcaOption.broker_url + '" target="_blank" >' + tCcaOption.broker + '</a>';
          }
          else
          {
            sHtml += tCcaOption.broker;
          }
        }
        else
        {
          sHtml += '<a href="https://www.nationalgridus.com/media/pdfs/billing-payments/bill-inserts/mae/cm4391_ma.pdf" target="_blank" >Power Sources</a>';
        }
        sHtml += '</td>';

        // Start month
        sHtml += '<td>';
        if ( tCcaOption )
        {
          sHtml += tCcaOption.start;
        }
        sHtml += '</td>';

        // End month
        sHtml += '<td>';
        if ( tCcaOption )
        {
          sHtml += tCcaOption.end;
        }
        sHtml += '</td>';

        sHtml += '</tr>';
      }

      $( '#cca-table tbody' ).html( sHtml );

      // Update the tablesorter
      var tTable = $( '#cca-table' );
      tTable.trigger( 'updateAll' );

      // Show total kWh
      $( '#which-customer' ).html( g_iCustomer ? ( 'Sample Customer ' + g_iCustomer + ': ' + g_aSampleCustomers[g_iCustomer-1].description ) : '' );
      $( '#total-kwh' ).html( 'Energy used: <span class="font-weight-bold text-primary">' + g_iTotalKwh.toLocaleString() + ' kWh</span> from ' + $( 'label[for="kwh-2"]' ).text() + ' through ' + $( 'label[for="kwh-13"]' ).text() );
      g_iCustomer = 0;

      // Update summary statistics
      updateStatistics();

      // Show the output
      showOutput( true );
    }
    else
    {
      showErrorMessage( true );
    }
  }

  function onFilterEnd()
  {
    // Update average savings
    updateStatistics();

    // Always show National Grid row, even if it is hidden by filter
    $( '.ng-row' ).removeClass( 'filtered' );
  }

  function updateStatistics()
  {
    //
    // Savings
    //

    var aSav = $( '#cca-table>tbody>tr:not(.filtered)>[savings]' );
    $( '#options-count' ).text( aSav.length );

    var iTotal = 0;
    for ( var iSav = 0; iSav < aSav.length; iSav ++ )
    {
      var tSav = $( aSav[iSav] );
      iTotal += parseInt( tSav.attr( 'savings' ) );
    }

    $( '#average-savings' ).removeClass( 'text-success' );
    $( '#average-savings' ).removeClass( 'text-danger' );

    var sAverage = 'n/a';
    if ( aSav.length )
    {
      var nAverage = Math.round( iTotal / aSav.length );
      $( '#average-savings' ).addClass( ( nAverage >= 0 ) ? 'text-success' : 'text-danger' );
      sAverage = '$' + nAverage;
    }

    $( '#average-savings' ).text( sAverage );

    //
    // Local green content
    //

    var aGreen = $( '#cca-table>tbody>tr:not(.filtered)>[local_green]' );

    var iTotal = 0;
    var aGreenValues = []
    for ( var iGreen = 0; iGreen < aGreen.length; iGreen ++ )
    {
      var tGreen = $( aGreen[iGreen] );
      var nGreen = parseInt( tGreen.attr( 'local_green' ) )
      aGreenValues.push( nGreen );
      iTotal += nGreen;
    }

    // Average local green
    $( '#average-green' ).removeClass( 'text-primary' );
    if ( aGreen.length )
    {
      $( '#average-green' ).addClass( 'text-primary' );
      sAverage = Math.round( iTotal / aGreen.length ) + '%';
    }
    else
    {
      sAverage = 'n/a'
    }
    $( '#average-green' ).text( sAverage );

    // Median local green
    $( '#median-green' ).removeClass( 'text-primary' );
    aGreenValues.sort( function( a, b ){ return a - b; } );
    var sMedianGreen = 'n/a';
    if ( aGreenValues.length )
    {
      $( '#median-green' ).addClass( 'text-primary' );
      var nMidOffset = Math.floor( aGreenValues.length / 2 );
      var nMedianGreen = ( aGreenValues.length % 2 ) ? aGreenValues[nMidOffset] : Math.round( ( aGreenValues[nMidOffset - 1] + aGreenValues[nMidOffset] ) / 2 );
      sMedianGreen = nMedianGreen + '%';
    }
    $( '#median-green' ).text( sMedianGreen );
  }

  function isInputReady()
  {
    var aInputs = $( '.kwh-input' );

    // Highlight empty fields, if any
    for ( var iInput = 0 ; iInput < aInputs.length; iInput ++ )
    {
      var tInput = $( aInputs[iInput] );
      if ( tInput.val() == '' )
      {
        highlightError( tInput, true );
      }
    }

    var bReady = $( '.error' ).length == 0;
    return bReady;
  }

  function calculateCost( sCcaOption )
  {
    g_iTotalKwh = 0;
    var nCost = 0;

    var aLabels = $( '.kwh-label' );
    for ( var iLabel = 0; iLabel < aLabels.length; iLabel ++ )
    {
      var tLabel = $( aLabels[iLabel] );
      var sMonthYear = tLabel.text().replace( /\u00a0/g, ' ' );
      var nRate = ( sCcaOption in g_tCcaOptions ) ? g_tCcaOptions[sCcaOption].rate : g_tRatesNg[sMonthYear][sCcaOption];
      var iKwh = parseInt( $( '#' + tLabel.attr( 'for' ) ).val() );
      g_iTotalKwh += iKwh;
      nCost += nRate * iKwh;
    }

    nCost = Math.round( nCost / 100 );

    return nCost;
  }

  function clearInput()
  {
    showErrorMessage( false );
    showOutput( false );

    $( '.error' ).removeClass( 'error' );
    $( '.kwh-input' ).val( '' );

    $( 'body,html' ).animate( { scrollTop: 0 }, 300 );
  }

  function highlightError( tInput, bHighlight )
  {
    if ( bHighlight )
    {
      tInput.addClass( 'error' );
      tInput.parent().addClass( 'error' );
    }
    else
    {
      tInput.removeClass( 'error' );
      tInput.parent().removeClass( 'error' );
    }
  }

  function showErrorMessage( bShow )
  {
    if ( bShow )
    {
      $( '#error-message' ).show();
      scrollToResults();
    }
    else
    {
      $( '#error-message' ).hide();
    }
  }

  function showOutput( bShow )
  {
    if ( bShow )
    {
      $( '#footer' ).hide();
      $( '#output' ).show();
      resizeBackdrop();
      scrollToResults();
    }
    else
    {
      $( '#output' ).hide();
      $( '#footer' ).show();
    }
  }

  function resizeBackdrop()
  {
    $( '.table-backdrop' ).width( Math.max( $( '#cca-table' ).width() + $( '#cca-table' ).offset().left, $( window ).width() -  getScrollbarWidth() ) );
  }

  function getScrollbarWidth()
  {
    var tOuter = $( '<div>' ).css(
      {
        visibility: 'hidden',
        width: 100,
        overflow: 'scroll'
      }
    );
    tOuter.appendTo('body');

    var tFull = $( '<div>' ).css( { width: '100%' } );
    tFull.appendTo( tOuter );

    var iScrollbarWidth = tOuter.width() - tFull.outerWidth();
    tOuter.remove();

    return iScrollbarWidth;
  };

  function scrollToResults()
  {
    hideTooltips();

    var iScrollTop = $( '#form-buttons' ).offset().top - $( '.fixed-top' ).outerHeight( true );
    $( 'body,html' ).animate( { scrollTop: iScrollTop }, 600 );
  }

  function hideTooltips()
  {
    $('.tooltip').tooltip( 'hide' );
  }

  function loadCustomer( iCustomer )
  {
    clearInput();

    g_iCustomer = iCustomer;

    // Set the start month
    $( '#start-month' ).val( g_aSampleCustomers[iCustomer-1].start_month ).change();

    // Load the readings
    var aReadings = g_aSampleCustomers[iCustomer-1].readings;
    for ( var iReading = 1; iReading <= aReadings.length; iReading ++ )
    {
      $( '#kwh-' + iReading ).val( aReadings[iReading-1] );
    }

    // Generate the output
    makeOutput();
  }

</script>

<!-- tablesorter theme -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.1/css/theme.green.min.css" integrity="sha256-5wegm6TtJ7+Md5L+1lED6TVE+NAr0G+ZyHuPRrihJHE=" crossorigin="anonymous" />

<!-- tablesorter basic libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.1/js/jquery.tablesorter.min.js" integrity="sha256-uC1JMW5e1U5D28+mXFxzTz4SSMCywqhxQIodqLECnfU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.1/js/jquery.tablesorter.widgets.min.js" integrity="sha256-Xx4HRK+CKijuO3GX6Wx7XOV2IVmv904m0HKsjgzvZiY=" crossorigin="anonymous"></script>

