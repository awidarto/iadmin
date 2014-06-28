@extends('layout.bootbrochure')

@section('content')
<style type="text/css">
    html, body{
        height:100%;
    }

    .container{
        min-height: 100%;
        height: 1330px;
        overflow: hidden;
        background-image: url({{ URL::to('/') }}/images/broc-bg-bottom.png);
        background-repeat: no-repeat;
        background-position: right bottom;
        /*width:1076px;*/
    }

    #side-bar{
        background-color: #d10a11;
        text-align: center;
        padding-top: 30px;
        min-height: 100%;
        height:100%;
    }

    #side-bar img{
        margin-top: 15px;
    }

    #side-bar, #broc-content{
        margin-bottom: -9999px;
        padding-bottom: 9999px;
    }

    .sub{
        margin: 0px;
        width:104%;
    }

    #broc-content{
        margin-left: 0px;
        margin-right: 0px;
        width: 800px;
    }

    table{
        font-size: 12px;
        font-family: Arial,Helvetica, sans-serif;
        width: 100%;
        border-collapse:collapse;
        border-spacing:0;
    }

    .table th, .table td{
        line-height:18px;
        padding:2px 5px;
    }

    th.item{
        text-align: left;
        vertical-align: top;
        padding-left: 5px;
    }

    thead th{
        padding-left: 5px;
    }

    thead th.header{
        line-height: 24px;
        font-weight: bolder;
    }

    thead{
        background-color:powderblue;
    }

    .contact-box{
        background-color: #dce6f2;
        padding: 8px;
        margin-right: 10px;
        border: thin solid #95b3d7;
        height:175px;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
    }

    #map-box{
        margin-left: 10px;
        border: thin solid #ccc;
    }

    .head-container{
        width:100%;
        max-height:450px;
        overflow:hidden;
        height:450px;
        margin-bottom:5px;
        padding:0px;
    }

    .head-container img{
        width:101%;
    }

    .disclaimer{
        font-size: 8px;
        padding:8px;
        text-alignment:justify;
        color: grey;
        line-height: 12px;
    }

    .license{
        padding:11px;
        padding-top:20px;
        text-alignment:justify;

    }

    .row-fluid span.title-span{
        min-height:none;
    }

    img#ia-motto{
        margin-top: 300px;
    }

    .rounded{
        background-color: #dce6f2;
        border: thin solid #95b3d7;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
    }

    .table-bordered{
        border: thin solid #95b3d7;
        background-color: #dce6f2;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
    }

    .table-bordered th,
    .table-bordered td{
        border:none;
    }


    .table-bordered thead:first-child tr:first-child > th:last-child,
    .table-bordered tbody:first-child tr:first-child > td:last-child,
    .table-bordered tbody:first-child tr:first-child > th:last-child{
        -webkit-border-top-right-radius: 8px;
        -moz-border-top-right-radius: 8px;
        border-top-right-radius: 8px;
    }

    .table-bordered thead:last-child tr:last-child > th:first-child,
    .table-bordered tbody:last-child tr:last-child > td:first-child,
    .table-bordered tbody:last-child tr:last-child > th:first-child,
    .table-bordered tfoot:last-child tr:last-child > td:first-child,
    .table-bordered tfoot:last-child tr:last-child > th:first-child{
        -webkit-border-bottom-left-radius: 8px;
        -moz-border-bottom-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .table-bordered thead:last-child tr:last-child > th:last-child,
    .table-bordered tbody:last-child tr:last-child > td:last-child,
    .table-bordered tbody:last-child tr:last-child > th:last-child,
    .table-bordered tfoot:last-child tr:last-child > td:last-child,
    .table-bordered tfoot:last-child tr:last-child > th:last-child{
        -webkit-border-bottom-right-radius: 8px;
        -moz-border-bottom-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }
</style>
    {{-- print_r($prop['defaultpictures']) --}}
    <div class="row-fluid">
        <div class="span2" id="side-bar">
            {{ HTML::image('images/v-ialogo-med.png')}}
            {{ HTML::image('images/v-iamotto-med.png','IA Motto',array('id'=>'ia-motto','style'=>'margin-top:310px;'))}}
        </div>
        <div class="span10" id="broc-content" >
            <div class="head-container">
                {{ HTML::image($prop['defaultpictures']['brchead'],'Head Image',array('style="width:100%;"') )}}
            </div>
            <div class="row-fluid">
                <div class="span4">
                    <div class="sub">
                        {{ HTML::image($prop['defaultpictures']['brc1'])}}
                    </div>
                </div>
                <div class="span4">
                    <div class="sub">
                        {{ HTML::image($prop['defaultpictures']['brc2'])}}
                    </div>
                </div>
                <div class="span4">
                    <div class="sub">
                        {{ HTML::image($prop['defaultpictures']['brc3'])}}
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <table class="table table-bordered" style="width:385px;margin:0px;margin-top:10px;margin-bottom:8px;margin-left:8px;height:310px;" >
                        <thead >
                            <tr>
                                <th colspan="2" class="header" style="text-align:left;font-size:16px;">
                                    Property Info
                                </th>
                            </tr>
                        </thead>
                        <tbody class="rounded" >
                            <tr>
                                <th class="item">ID</th>
                                <td>{{ $prop['propertyId'] }}</td>
                            </tr>
                            <tr>
                                <th class="item">Purchase Price</th>
                                <td>{{ Ks::usd($prop['listingPrice']) }}</td>
                            </tr>
                            <tr>
                                <th class="item">Address</th>
                                <td>
                                    <span class="title-span">{{$prop['number'].' '.$prop['address']}}<br />
                                    {{$prop['city'].' '.$prop['state'].' '.$prop['zipCode']}}</span>
                                </td>
                            </tr>
                            {{--
                            <tr>
                                <th class="item">Monthly Rent</th>
                                <td>{{ Ks::usd($prop['monthlyRental'])}}</td>
                            </tr>
                            <tr>
                                <th class="item">Tax</th>
                                <td>{{ Ks::usd($prop['tax'])}}</td>
                            </tr>

                            --}}
                            <tr>
                                <th class="item">Type</th>
                                <td>{{ $prop['type']}}</td>
                            </tr>
                            <tr>
                                <th class="item">Bed</th>
                                <td>{{ $prop['bed']}}</td>
                            </tr>
                            <tr>
                                <th class="item">Bath</th>
                                <td>{{ $prop['bath']}}</td>
                            </tr>
                            <tr>
                                <th class="item">Year Built</th>
                                <td>{{ $prop['yearBuilt']}}</td>
                            </tr>
                            <tr>
                                <th class="item">Building Size</th>
                                <td>{{ number_format($prop['houseSize'],0) }} sqft.</td>
                            </tr>
                            <tr>
                                <th class="item">Lot Size</th>
                                <td>
                                    @if( $prop['lotSize'] < 100)
                                        {{ number_format($prop['lotSize'] * 43560,0, '.',',') }} sqft
                                    @else
                                        {{ number_format($prop['lotSize'],0, '.',',') }} sqft
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="item">Average $/sqft</th>
                                <td>
                                    ${{ Ks::us($prop['listingPrice']/$prop['houseSize']) }}/sqft
                                </td>
                            </tr>
                            <tr>
                                <th class="item">Category</th>
                                <td>{{ ucwords( strtolower($prop['category'] ) ) }}</td>
                            </tr>
                            <tr>
                                <th class="item">Parcel Number</th>
                                <td>{{ ucwords( strtolower($prop['parcelNumber'] ) ) }}</td>
                            </tr>
                            {{--
                            <tr>
                                <th class="item">Property Management</th>
                                <td>{{ ucwords( strtolower($prop['propertyManager'] ) ) }}</td>
                            </tr>
                                <tr>
                                    <th colspan="2" class="item">Description</th>
                                </tr>
                                <tr>
                                    <td colspan="2">{{ $prop['description']}}</td>
                                </tr>
                            --}}
                        </tbody>
                    </table>
                </div>
                <div class="span6" style="padding-right:10px;padding-top:10px;" >

                    <table class="table table-bordered" style="width:380px;margin-bottom:8px;height:310px;">
                        <thead >
                            <tr>
                                <th colspan="2" class="header" style="text-align:left;font-size:16px;">
                                    Financial Info
                                </th>
                            </tr>
                        </thead>
                        <tbody class="rounded" >
                            <?php
                                $annualRental = 12*$prop['monthlyRental'];
                                $propManagementFee = $annualRental * 0.1;
                                $maintenanceAllowance = $annualRental * 0;
                                $vacancyAllowance = $annualRental * 0;

                                $totalExpense = $propManagementFee + $maintenanceAllowance + $vacancyAllowance + $prop['tax'] + $prop['insurance'];

                                $netAnnualCashFlow = $annualRental - $totalExpense;
                                $netMonthlyCashFlow = round($netAnnualCashFlow / 12, 0, PHP_ROUND_HALF_UP);

                                $roi = ($netAnnualCashFlow / $prop['listingPrice']) * 100;
                                $roi = round($roi, 1, PHP_ROUND_HALF_UP);

                                $roi3 = round($roi3 * 100, 1, PHP_ROUND_HALF_UP);
                                $roi5 = round($roi5 * 100, 1, PHP_ROUND_HALF_UP);

                            ?>
                            <tr>
                                <th class="item" >Purchase Price</th><td>${{ Ks::us( $prop['listingPrice'])}}</td>
                            </tr>
                            <tr>
                                <th class="item" >Monthly Rent</th><td>${{ Ks::us( $prop['monthlyRental'] ) }}</td>
                            </tr>
                            <tr>
                                <th class="item" >Annual Rent</th><td id="txt_annualRental">${{ Ks::us($annualRental) }}</td>
                            </tr>
                            <tr>
                                <th class="item" colspan="2" style="text-align:left;font-style:italic;">Annual Expenses</th>
                            </tr>
                            <tr>
                                <th class="item" >Taxes <span style="font-weight:normal;" >(estimated)</span></th><td>${{ Ks::us($prop['tax'] ) }}</td>
                            </tr>
                            <tr>
                                <th class="item" >Insurance <span style="font-weight:normal;" >(estimated)</span></th><td>${{ Ks::us($prop['insurance'])}}</td>
                            </tr>
                            <tr>
                                <th class="item" >Property Management <span style="font-weight:normal;" >(10%)</span></th><td><span id="propManagementFee">{{ Ks::us($propManagementFee)}}</span></td>
                            </tr>
                            <tr>
                                <th class="item" >HOA</th><td><span>{{ Ks::usd($prop['HOA']) }}</span></td>
                            </tr>
                            {{--

                            <tr>
                                <th class="item" >Maintenance Allowance</th><td><span id="maintenanceAllowance">${{ Ks::us($maintenanceAllowance) }}</span></td>
                            </tr>
                            <tr>
                                <th class="item" >Vacancy Allowance</th><td><span id="vacancyAllowance">${{ Ks::us($vacancyAllowance)}}</span></td>
                            </tr>

                            --}}

                            <tr>
                                <th class="item"  style="text-align:left;font-style:italic;border-bottom:none;">Total Expenses</th><td id="totalExpense" style="border-bottom:none;" >${{ Ks::us($totalExpense) }}</td>
                            </tr>
                            <tr>
                                <th class="item" >Net Annual Cash Flow</th><td id="netAnnualCashFlow">${{ Ks::us($netAnnualCashFlow) }}</td>
                            </tr>
                            <tr>
                                <th class="item" >Net Monthly Cash Flow</th><td id="netAnnualCashFlow">${{ Ks::us($netAnnualCashFlow / 12 ) }}</td>
                            </tr>
                            <tr class="yield">
                                <th class="item" >Net Annual Rental Return</th><td id="calcROI" style="font-weight:bold;">{{ $roi }}%</td>
                            </tr>
                        </tbody>

                    </table>

                </div>
            </div>
            <div class="row-fluid" >
                <div class="span6">
                                <?php

                                    $address = $prop['number'].' '.$prop['address'].' '.$prop['city'].' '.$prop['state'].' '.$prop['zipCode'];
                                    if($prop['type'] == 'LAND'){
                                        $color = 'green';
                                        $label = 'L';
                                    }else{
                                        $color = 'blue';
                                        $label = 'H';
                                    }
                                    $address_url = urlencode($address);
                                ?>
                                <div id="map-box" style="display:inline-block">

                                <img src="http://maps.googleapis.com/maps/api/staticmap?center={{ $address_url }}&zoom=13&size=400x245&maptype=roadmap&markers=color:{{ $color }}%7Clabel:{{ $label }}%7C{{ $address_url }}&sensor=false" style="float:left;border:thin solid #ccc;"/>
                                </div>

                </div>
                <div class="span6">
                        <table class="table table-bordered" style="width:380px;margin-bottom:8px;">
                        <thead >
                            <tr>
                                <th colspan="2" class="header" style="text-align:left;font-size:16px;">
                                    Projected ROI
                                </th>
                            </tr>
                        </thead>
                        <tbody class="rounded" >
                            <tr class="yield">
                                <th class="item" >3 years with 5% return rate</th><td id="calcROI" style="font-weight:bold;">{{ $roi3 }}%</td>
                            </tr>
                            <tr class="yield">
                                <th class="item" >3 Years with 10% return rate</th><td id="calcROI" style="font-weight:bold;">{{ $roi5  }}%</td>
                            </tr>
                        </tbody>

                    </table>

                    <div class="contact-box" style="position:relative">
                            @if( $contact['fullname'].$contact['email'].$contact['mobile'] != '')
                                <p style="font-weight:bold">
                                        Please contact:<br />{{ $contact['fullname'] }}<br />at {{ $contact['email'] }} or call {{ $contact['mobile']}}.
                                </p>
                            @endif
                            <div style="position:absolute;bottom:0px;display:block;">
                                <p style="color:red;font-weight:bold;font-size:10px;text-align:center;padding-right:15px;padding-left:15px;line-height:14px;font-style:italic;">
                                        The greatest compliment you can pay me is to refer my services onto one of your associates.
                                </p>
                            </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12 disclaimer">
                    <p>
                        <b>Disclaimer</b> - While every effort is made to ensure that descriptions of properties, homes, locations or developments particulars, financials, market value, rents, tax, insurance, management fees, HOA fees, return on investment, specifications and pictures of any property, home, building, location or development are given in good faith and believed to be correct, but they do not form part of any offer or solicitation and are intended only as a general guide. Investors Alliance does not warrant that it is complete, comprehensive or accurate, or commit to its being updated. In no event shall Investors Alliance be liable for any incidental, indirect, consequential or special damages of any kind, or any damages whatsoever, including, without limitation, those resulting from loss of profit, loss of contracts, goodwill, data, information, income or expected savings, whether or not advised of the possibility of such damage, arising out of or in connection with the use of this information. In all cases Investors Alliance informs "buyers beware". This is real estate and with it carries inherent risks that you must accept and seek your own legal advice for each purchase.
                    </p>
                </div>
            </div>

        </div>
    </div>

@stop