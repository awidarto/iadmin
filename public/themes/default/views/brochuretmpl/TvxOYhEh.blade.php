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
        margin: 20px;
    }

    #broc-content{
        margin-left: 0px;
        margin-right: 0px;
        width: 800px;
    }

    table{
        font-size: 18px;
        font-family: Arial,Helvetica, sans-serif;
        width: 100%;
    }

    th.item{
        text-align: left;
        vertical-align: top;
    }

</style>
    {{-- print_r($prop['defaultpictures']) --}}
    <div class="row-fluid">
        <div class="span2" id="side-bar">
            {{ HTML::image('images/v-ialogo-med.png')}}
        </div>
        <div class="span10" id="broc-content" >
            {{ HTML::image($prop['defaultpictures']['brchead'])}}
            <div class="row-fluid">
                <div class="span4">
                    <div class="sub">
                        {{ HTML::image($prop['defaultpictures']['brc1'])}}
                    </div>
                    <div class="sub">
                        {{ HTML::image($prop['defaultpictures']['brc2'])}}
                    </div>
                    <div class="sub">
                        {{ HTML::image($prop['defaultpictures']['brc3'])}}
                    </div>
                </div>
                <div class="span8">
                    <table style="margin:0px;margin-top:10px;" >
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
                                <th class="item">FMV</th>
                                <td>{{ Ks::usd($prop['FMV'])}}</td>
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
                                <th class="item">Property Management</th>
                                <td>{{ ucwords( strtolower($prop['propertyManager'] ) ) }}</td>
                            </tr>
                            {{--
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
            </div>

        </div>
    </div>

@stop