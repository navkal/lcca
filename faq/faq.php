<?php
  // Copyright 2020 Energize Andover.  All rights reserved.

  include $_SERVER["DOCUMENT_ROOT"]."/util/security.php";
?>

<style>
[data-toggle="collapse"].collapsed .faq-toggle:before
{
  content: "\f067"; /* Font Awesome plus */
}

[data-toggle="collapse"] .faq-toggle:before
{
  content: "\f068"; /* Font Awesome minus */
}

.card-body
{
  border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.search-highlight
{
  background-color: #bbff99;
}
</style>

<div class="container">

  <!-- Title -->
  <div class="row">
    <div class="col-12 col-md-10 col-lg-8 mx-auto">
      <div class="h5 py-2">
        Frequently Asked Questions
        <span class="float-right">
          <a class="btn btn-link" href="faq/download_pdf.php" role="button" title="Download brochure">
            <i class="fas fa-file-pdf"></i> Brochure
          </a>
        </span>
      </div>
    </div>
  </div>

  <!-- Search input -->
  <div class="row">
    <div class="col-12 col-md-10 col-lg-8 mx-auto">
      <div class="h5 pb-2">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text text-muted">
              <i class="fas fa-search"></i>
            </span>
          </div>
          <input id="search-input" type="text" class="form-control form-control-sm" placeholder="Search..." autocomplete="off" />
        </div>
      </div>
    </div>
  </div>

  <!-- Q/A list -->
  <div class="row">
    <div class="col-12 col-md-10 col-lg-8 mx-auto">
       <div class="accordion" >
        <div id="faq">
        </div>
        <div id="search-text-not-found" style="display:none" >
          <div class="alert alert-danger" >
            Search text <span id="search-text" class="font-weight-bold font-italic"></span> not found.
          </div>
        </div>
      </div>

    </div>
  </div>

</div>

<script>
var g_aQa =
[
  {
    q: 'What is <i>Community Choice Aggregation</i> (CCA)?',
    a: 'CCA is a Massachusetts state-regulated program that offers the potential to increase the renewable content of the electricity used by an entire town while keeping supply rates stable and affordable for everyone.'
  },
  {
    q: 'How does CCA work?',
    a: 'CCA empowers cities and towns to create large buying groups of customers in order to seek bids for cheaper supply rates.  By grouping electricity customer accounts within municipal boundaries, CCA creates economies of scale, enabling participating cities and towns to obtain competitive rates for account holders as a whole.'
  },
  {
    q: 'What are the steps for implementing a CCA program in Andover?',
    a:
      '<ol>' +
      '  <li>' +
      '    Approval by voters at Town Meeting' +
      '  </li>' +
      '  <li>' +
      '    Development of plan by town government' +
      '  </li>' +
      '  <li>' +
      '    Public review of plan' +
      '  </li>' +
      '  <li>' +
      '    Approval of plan by Select Board' +
      '  </li>' +
      '  <li>' +
      '    Submission of plan to MA DPU for final approval' +
      '  </li>' +
      '</ol>'
  },
  {
    q: 'How do CCA programs acquire their renewable energy supply?',
    a: 'There are several organizations that supply renewable energy to CCA programs.  One example is the <a href="http://www.greenenergyconsumers.org/aggregation" target="_blank">Green Energy Consumers Alliance</a>, a Boston-based nonprofit, which supplies local renewable energy to a number of CCA programs in Greater Boston.  The Green Energy Consumers Alliance purchases renewable energy wholesale, mostly from community-based wind power projects located in Massachusetts and Rhode Island.  They have a history of entering into long-term contracts with renewable energy developers, helping them grow their portfolio based on wind and solar.'
  },
  {
    q: 'How do I join or leave the CCA program?',
    a:
      '<p>' +
      '  If enacted, the Andover CCA program will offer one or more supply options to <?=NG?> customers.' +
      '</p>' +
      '<p>' +
      '  Initially, if you get your supply from <?=NGBS?>, you will be switched automatically to the program\'s default supply option.  If the program offers other options - for example, with higher or lower renewable content than the default - you can switch (<i>opt up</i> or <i>opt down</i>) at any time.  You can also return to <?=NGBS?> at any time, without penalty or cost.  You can even <i>opt out</i> before the program starts.' +
      '</p>' +
      '<p>' +
      '  If you do not subscribe to <?=NGBS?>, you will not be enrolled automatically, but you can enroll if you wish.' +
      '</p>'
  },
  {
    q: 'Do I have to participate in CCA if I don\'t want to?',
    a: 'No, residents and businesses can <i>opt out</i> before the CCA program starts, or leave the program at any time thereafter without penalty.'
  },
  {
    q: 'What if I choose to leave the CCA program early?',
    a: 'You may terminate your CCA participation at any time without any early termination or exit fee.  After leaving, you may return at any time with no associated re-enrollment fee; but upon returning, you are not guaranteed the original contract rate.'
  },
  {
    q: 'If I leave, can I return at a later date?',
    a: 'After leaving, you may return to the CCA program at any time with no associated re-enrollment fee; but upon returning, you are not guaranteed the original contract rate.  After you re-enroll, the CCA program rate will appear on your utility bill in the next available billing cycle.  Since <?=NG?> takes at least two days to process a change of supplier, you are encouraged to re-enroll at least five business days before the meter read date indicated on your utility bill, to help ensure that re-enrollment occurs on a timely basis.'
  },
  {
    q: 'Can businesses participate in the CCA program?',
    a: 'Yes, businesses can participate.'
  },
  {
    q: 'How will CCA affect my electric bill?',
    a:
      '<p>' +
      '  Your electric utility bill has two cost components – delivery and supply.  CCA affects only the supply component.  CCA does not affect the price of delivery, which is set by the utility and regulated by MA DPU.' +
      '</p>' +
      '<p>' +
      '  Once enrolled in CCA, you will continue to receive one bill for both delivery and supply from <?=NG?>, and <?=NG?> will continue to provide the same level of service as before.  While CCA aims to offer favorable supply prices, savings relative to <?=NGBS?> is not guaranteed, due to potential fluctuations in <?=NG?> pricing.' +
      '</p>'
  },
  {
    q: 'Will the CCA supply rate be lower than <?=NGBS?> rate?',
    a: 'While CCA aims to offer favorable supply prices, savings relative to <?=NGBS?> is not guaranteed, due to potential fluctuations in <?=NG?> pricing.  The CCA rate is typically fixed for the duration of the supply contract, which may last several years.  On the other hand, the <?=NGBS?> rate changes every six months for residential, small business, and lighting accounts, and every three months for large commercial and industrial customers.'
  },
  {
    q: 'Will I have to pay a deposit to participate in CCA?',
    a: 'No, you will not have to pay a deposit.'
  },
  {
    q: 'Will there be a change to my electric meter?',
    a: 'No, there will be no change to your meter.  <?=NG?> will continue to read your meter to determine how much energy you consume.'
  },
  {
    q: 'What if I have a solar photo-voltaic (PV) system?',
    a: 'If you have a solar PV system, you can participate in the CCA program, as long as you subscribe to <?=NGBS?>.  You will continue to receive net metering credits, which will appear on your <?=NG?> bill and be calculated based on the <?=NGBS?> price, as before.  There will be no change in your eligibility to earn or sell SRECs.'
  },
  {
    q: 'How can I trust that Andover will select a competent and reputable supplier?',
    a: 'Only suppliers licensed by the state are eligible to bid for CCA contracts.'
  },
  {
    q: 'Can <?=NG?> be a CCA supplier?',
    a: 'No.  By law, utilities cannot bid for CCA supply contracts.'
  },
  {
    q: 'Will Andover municipality profit from the CCA program?',
    a: 'No, Andover will not profit from the CCA program.  However, as a current customer of <?=NGBS?>, the municipality has the potential to benefit from the program in the same way as residential and business customers would.'
  },
  {
    q: 'Under the program, can I continue to participate in <?=NG?>’s budget billing/equal payment plan?',
    a: 'Yes, you may continue to participate in a budget billing/equal payment plan.'
  },
  {
    q: 'Whom should I call in case of an outage or an issue with my electric bill?',
    a: 'To report an outage, contact <?=NG?> at (800) 465-1212. All billing questions will continue to be directed to <?=NG?> at (800) 322-3223.'
  },
  {
    q: 'What is a <i>Third-Party Supplier</i> (TPS)?',
    a: 'A TPS is a for-profit company that sells electricity supply into the grid which may be purchased by individual electricity customers under contract.'
  },
  {
    q: 'If I participate in a CCA program or enroll with a TPS, will the utility take longer to restore my electricity in the event of an outage?',
    a: 'No.  Since the 1997 deregulation law stipulates that your utility can generate revenue only by delivering your electricity, and not by providing the electricity supply, they have no incentive to give priority to their own <?=BS?> customers when restoring service.'
  },
  {
    q: 'I have received offers from TPS\'s promising lower electricity rates. What should I do?',
    a:
      '<p>' +
      '  In March 2018, the Attorney General\'s office released a <a href="https://www.mass.gov/news/ag-healey-calls-for-shut-down-of-individual-residential-competitive-supply-industry-to-protect" target="_blank">report</a> citing "aggressive sales tactics and false promises of cheaper electric bills", and calling for the TPS industry to be shut down.' +
      '</p>' +
      '<p>' +
      '  Beware of scams.  Before agreeing to purchase electricity from a TPS, you should read the complete contract fine print and have a clear understanding of rate details and any termination penalties.' +
      '</p>'
  },
  {
    q: 'A few months after I switched to a TPS, my rate jumped above the utility <?=BS?> rate. Can this happen with a CCA supplier?',
    a: 'CCA supply rates are typically fixed for the contract period (months or years).  While they aim to offer a favorable price for the promised energy mix, there is no guarantee that they will be lower than the utility <?=BS?> rate.'
  },
  {
    q: 'What is the origin of CCA in Massachusetts?',
    a: 'CCA came out of the Restructuring Act of 1997, specifically <a href="https://malegislature.gov/Laws/GeneralLaws/PartI/TitleXXII/Chapter164/Section134" target="_blank">Massachusetts General Law, M.G.L. c. 164 sec. 134</a>.  The law was enacted to ensure that the benefits of energy deregulation would be passed on to residential and business electricity customers.'
  },
  {
    q: 'What is the history of CCA?',
    a: 'In 1997, Massachusetts passed the first CCA legislation in the country and formed the Cape Light Compact, the first CCA program.  Six other states have since followed.  The programs go by different names in different states, but the bulk-purchasing principles upon which they operate are largely the same.  By aggregating a large number of electric accounts together, the programs create economies of scale, enabling participating municipalities to reap savings for electricity consumers within their borders.'
  },
];

$( document ).ready( onDocumentReady );

function onDocumentReady()
{
  makeFaq();
  // makeFaqForBrochure();

  // Set search handler
  $( '#search-input' ).on( 'input', onSearchInput );
}

function makeFaq()
{
  sHtml = '';

  for ( var iQa = 0; iQa < g_aQa.length; iQa ++ )
  {
    var iCard = iQa - 1;
    sHtml += '<div class="card qa">';
    sHtml += '  <div class="card-header" id="' + iCard + '">';
    sHtml += '    <a class="collapsed" data-toggle="collapse" href="#a' + iCard + '">';
    sHtml += '      <table>';
    sHtml += '        <tr class="text-primary">';
    sHtml += '          <td>';
    sHtml += '            <i class="fas faq-toggle pr-3" style="font-size:13px" aria-hidden="true"></i>';
    sHtml += '          </td>';
    sHtml += '          <td class="search-content" >';
    sHtml +=              g_aQa[iQa].q;
    sHtml += '          </td>';
    sHtml += '        </tr>';
    sHtml += '      </table>';
    sHtml += '    </a>';
    sHtml += '  </div>';
    sHtml += '  <div id="a' + iCard + '" class="collapse" aria-labelledby="q' + iCard + '" data-parent="#faq">';
    sHtml += '    <div class="card-body search-content">';
    sHtml +=        g_aQa[iQa].a;
    sHtml += '    </div>';
    sHtml += '  </div>';
    sHtml += '</div>';
  }

  $( '#faq' ).html( sHtml );
}

function makeFaqForBrochure()
{
  sHtml = '';

  for ( var iQa = 0; iQa < g_aQa.length; iQa ++ )
  {
    sHtml += '<p>';
    sHtml += '<b>';
    sHtml +=              g_aQa[iQa].q;
    sHtml += '</b>';
    sHtml += '<br/>';
    sHtml +=              g_aQa[iQa].a;
    sHtml += '</p>';
  }

  $( '#faq' ).html( sHtml );
}

// Define jQuery selector for case-insensitive search
$.expr[':'].icontains = function( tElement, iNotUsed, aMatch )
{
  return $( tElement ).text().toUpperCase().indexOf( aMatch[3].toUpperCase() ) >= 0;
};

// Search handler
function onSearchInput()
{
  // Regenerate the full FAQ
  makeFaq();

  // Get the search text
  var sText = $( '#search-input' ).val().trim();

  // Hide Q/A elements and error message
  $( '.qa' ).hide();
  $( '#search-text-not-found' ).hide();

  // Look for matches
  console.log( '==> looking for [' + sText + '] <==' );
  var aShow = $( '.search-content:icontains(' + sText + ')' ).closest( '.qa' );

  // Update display
  if ( aShow.length )
  {
    // Highlight matched text
    if ( sText.length )
    {
      highlightSearchText( sText, aShow );
    }

    // Show Q/A elements that contain matches
    aShow.show();
  }
  else
  {
    // Show error message
    $( '#search-text' ).html( sText );
    $( '#search-text-not-found' ).show();
  }
}

function highlightSearchText( sText, aShow )
{
  var aContent = aShow.find( '.search-content' );
  var iTextLen = sText.length;

  // Iterate over content elements
  for ( var iContent = 0; iContent < aContent.length; iContent ++ )
  {
    // Get next content element
    var tContent =  $( aContent[iContent] );

    // Find matches of search text in content
    var aOffsets = findMatches( sText, tContent );

    // Build new content as array of substring parts
    sContentHtml = tContent.html();
    var aParts = [];
    var iPartOffset = 0;
    for ( var iOffset = 0; iOffset < aOffsets.length; iOffset ++ )
    {
      // Add substring between matches
      var iMatchOffset = aOffsets[iOffset];
      aParts.push( sContentHtml.substring( iPartOffset, iMatchOffset ) );

      // Add matched substring with markup
      var sMatch = sContentHtml.substr( iMatchOffset, iTextLen );
      var sMarkup = '<span class="search-highlight">' + sMatch + '</span>';
      aParts.push( sMarkup );

      // Set offset of next part
      iPartOffset = iMatchOffset + iTextLen;
    }

    // Add substring following last match
    aParts.push( sContentHtml.substring( iPartOffset, sContentHtml.length ) );

    // Deploy newly marked-up content
    tContent.html( aParts.join( '' ) );
  }
}

function findMatches( sText, tContent )
{
  // Normalize search text
  var sTextUpper = sText.toUpperCase();

  // Normalize content for searching
  var sContent = preprocessHtml( tContent );

  // Build array of offsets where matches occur
  var aOffsets = [];
  var iOffset = 0;
  do
  {
    iOffset = sContent.indexOf( sTextUpper, iOffset );

    if ( iOffset != -1 )
    {
      aOffsets.push( iOffset );
      iOffset += sTextUpper.length;
    }
  }
  while( iOffset != -1 );

  return aOffsets;
}

function preprocessHtml( tContent )
{
  // Extract HTML and convert to uppercase
  var sHtml = tContent.html().toUpperCase();

  // Find all HTML tags
  var aTags = sHtml.match( /<[^>]*>/g ) || [];

  // Replace HTML tags with spaces
  for ( var iTag = 0; iTag < aTags.length; iTag ++ )
  {
    // Replace next HTML tag
    var sTag = aTags[iTag];
    sHtml = sHtml.replace( sTag, ' '.repeat( sTag.length ) );
  }

  return sHtml;
}

</script>