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
        background-blend-mode: transparent;
        height: 225px;
        padding: 8px;
        margin-left: 10px;
        border: thin solid #ccc;
    }

    .head-container{
        width:100%;
        max-height:400px;
        overflow:hidden;
        height:400px;
    }

    .disclaimer{
        font-size: 6px;
        padding:8px;
        text-alignment:justify;
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
                        <tbody>
                            <tr>
                                <th class="item">Address</th>
                                <td>
                                    <span class="title-span">{{$prop['number'].' '.$prop['address']}}</span>
                                    <span class="title-span">{{$prop['city'].' '.$prop['state'].' '.$prop['zipCode']}}</span>
                                </td>
                            </tr>
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
                            <tr>
                                <th class="item">Rental Yield</th>
                                <td>{{ number_format((($prop['monthlyRental']*12)/$prop['listingPrice'])*100,1)}}%</td>
                            </tr>
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
                <div class="span6" style="padding-right:10px;" >
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
                                <img src="http://maps.googleapis.com/maps/api/staticmap?center={{ $address_url }}&zoom=13&size=400x250&maptype=roadmap&markers=color:{{ $color }}%7Clabel:{{ $label }}%7C{{ $address_url }}&sensor=false" style="float:left;border:thin solid #ccc;"/>

                </div>
            </div>
            <div class="row-fluid" >
                <div class="span6">
                    <div class="contact-box"" >
                        <p style="font-weight:bold">
                        Please contact:<br /><br />{{ $contact['fullname'] }} at {{ $contact['email'] }} or call {{ $contact['mobile']}}.
                        </p>
                    </div>
                </div>
                <div class="span6">
                    <p>
                        Disclaimer - While every effort is made to ensure that this information is accurate and conforms with all applicable legal requirements it is supplied in good faith as an aid to users. Investors Alliance do not warrant that it is complete, comprehensive or accurate, or commit to its being updated. In no event shall Investors Alliance be liable for any incidental, indirect, consequential or special damages of any kind, or any damages whatsoever, including, without limitation, those resulting from loss of profit, loss of contracts, goodwill, data, information, income, expected savings or business relationships, whether or not advised of the possibility of such damage, arising out of or in connection with the use of this information.
                    </p>
                    <p>
                        Copyright - All materials contained herein are, unless otherwise stated, the property of Investors Alliance. Reproduction or retransmission of the materials, in whole or in part, in any manner, without the prior written consent of the copyright holder, is a violation of copyright law.
                    </p>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12 disclaimer">
                    <p>
                        Investors Alliance is a Trading Name of Terra Rossa, LLC with registered office in 125 East Main St #350 American Fork, UT 84003 USA. Each Office is independently owned and operated.
                    </p>
                </div>
            </div>

        </div>
    </div>

@stop