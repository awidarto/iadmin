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
        font-size: 14px;
        font-family: Arial,Helvetica, sans-serif;
        width: 100%;
    }

    th.item{
        text-align: left;
        vertical-align: top;
    }

    .contact-box{
        background-color: #ddd;
        padding: 8px;
        margin-right: 10px;
        border: thin solid #ccc;
        height:222px;
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
</style>
    {{-- print_r($prop['defaultpictures']) --}}
    <div class="row-fluid">
        <div class="span2" id="side-bar">
            {{ HTML::image('images/v-ialogo-med.png')}}
        </div>
        <div class="span10" id="broc-content" >
            <div class="head-container">
                {{ HTML::image($prop['defaultpictures']['brchead'])}}
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
                    <table style="margin:0px;margin-top:10px;margin-left:8px" >
                        <thead>
                            <tr>
                                <th colspan="2" class="header" style="text-align:left;font-size:16px;font-weight:normal;">
                                    Property Info
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="item">ID</th>
                                <td>{{ $prop['propertyId'] }}</td>
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
                                <th class="item">Price</th>
                                <td>{{ Ks::usd($prop['listingPrice']) }}</td>
                            </tr>
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
                                <th class="item" style="width:50%" >Type</th>
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
                                <th class="item">Size</th>
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

                    <table style="width:100%;margin-bottom:0px;">
                        <thead>
                            <tr>
                                <th colspan="2" class="header" style="text-align:left;font-size:16px;font-weight:normal;">
                                    Financial Info
                                </th>
                            </tr>
                        </thead>
                        <tbody>
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
                                <th colspan="2" style="text-align:left;font-style:italic;border-bottom:thin solid #ddd;">Annual Expenses</th>
                            </tr>
                            <tr>
                                <th class="item" >Taxes*</th><td>{{str_replace(array(',','.'),'',$prop['tax']) }}</td>
                            </tr>
                            <tr>
                                <th class="item" >Insurance**</th><td>{{$prop['insurance']}}</td>
                            </tr>
                            <tr>
                                <th class="item" >Property Management</th><td><span class="pull-left" >10%</span> <span id="propManagementFee">{{ Ks::usd($propManagementFee)}}</span></td>
                            </tr>
                            <tr>
                                <th class="item" >HOA</th><td><span class="pull-left" >{{ number_format( ($prop['HOA'] / 12) , 1, '.', '') }}</span> <span>{{ $prop['HOA'] }}</span></td>
                            </tr>

                            <tr>
                                <th class="item" >Maintenance Allowance</th><td><span id="maintenanceAllowance">${{ Ks::us($maintenanceAllowance) }}</span></td>
                            </tr>
                            <tr>
                                <th class="item" >Vacancy Allowance</th><td><span id="vacancyAllowance">${{ Ks::us($vacancyAllowance)}}</span></td>
                            </tr>

                            <tr>
                                <th style="text-align:left;font-style:italic;border-bottom:thin solid #ddd;">Total Expenses</th><td id="totalExpense" style="border-bottom:thin solid #ddd;" >${{ Ks::us($totalExpense) }}</td>
                            </tr>
                            <tr>
                                <th class="item" >Net Annual Cash Flow</th><td id="netAnnualCashFlow">${{ Ks::us($netAnnualCashFlow) }}</td>
                            </tr>
                            <tr class="yield">
                                <th class="item" >Net Rental Return</th><td id="calcROI">{{ $roi }}%</td>
                            </tr>
                        </tbody>

                    </table>
                    <p class="disclaimer">*&nbsp;&nbsp; Approximately to latest current year available<br />** Approximately</p>

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

                                <img src="http://maps.googleapis.com/maps/api/staticmap?center={{ $address_url }}&zoom=13&size=400x250&maptype=roadmap&markers=color:{{ $color }}%7Clabel:{{ $label }}%7C{{ $address_url }}&sensor=false" style="float:left;border:thin solid #ccc;"/>
                                </div>

                </div>
                <div class="span6">
                    <div class="contact-box"" >
                        <p style="font-weight:bold">
                            @if( $contact['fullname'].$contact['email'].$contact['mobile'] != '')
                                Please contact:<br />{{ $contact['fullname'] }}<br />at {{ $contact['email'] }} or call {{ $contact['mobile']}}.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12 disclaimer">
                    <p>
                        Disclaimer - While every effort is made to ensure that this information is accurate and conforms with all applicable legal requirements it is supplied in good faith as an aid to users. Investors Alliance do not warrant that it is complete, comprehensive or accurate, or commit to its being updated. In no event shall Investors Alliance be liable for any incidental, indirect, consequential or special damages of any kind, or any damages whatsoever, including, without limitation, those resulting from loss of profit, loss of contracts, goodwill, data, information, income, expected savings or business relationships, whether or not advised of the possibility of such damage, arising out of or in connection with the use of this information.
                    </p>
                </div>
            </div>

        </div>
    </div>

@stop