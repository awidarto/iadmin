@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

{{ Former::hidden('id')->value($formdata['_id']) }}

<div class="row-fluid">
    <div class="span6">
        {{ Former::text('title','Campaign Title') }}
        {{ Former::text('slug','Permalink')->id('permalink') }}

        {{ Former::select('status')->options(array('inactive'=>'Inactive','active'=>'Active'))->label('Status') }}

        {{ Former::text('validFromDate','Campaign Period From')->class('span7 eventdate')
            ->id('fromDate')
            ->append('<i class="icon-th"></i>') }}

        {{ Former::text('validToDate','Until')->class('span7 eventdate')
            ->id('toDate')
            ->append('<i class="icon-th"></i>') }}


        {{-- Former::select('category')->options(Config::get('ia.eventcat'))->label('Category') --}}
        {{ Former::textarea('description','Description') }}
        {{ Former::text('tags','Tags')->class('tag_keyword') }}
    </div>
    <div class="span6">
        <h6>Target</h6>
        {{ Former::select('contactGroup', 'Contact Group')
            ->options(Prefs::getContactGroup()->contactGroupToSelection('_id','title',false)) }}
        <h6>Content</h6>
        {{ Former::select('newsletterTemplate', 'Newsletter')
            ->options(Prefs::getNewsletter()->newsletterToSelection('_id','title',false)) }}
        <h6>Start Campaign</h6>
        {{ Former::select('sendOption', 'Start')
            ->options( Config::get('kickstart.send_options') ) }}

        {{ Former::select('dayOption', 'Every ')
            ->help('use for periodicals ( every week / every month )')
            ->options( Config::get('kickstart.days') ) }}

        {{ Former::text('sendDate','at Date')->class('span7 datepicker')
            //->data_format('dd-mm-yyyy')
            ->help('use if option "At Specified Date" is selected')
            ->append('<i class="icon-th"></i>') }}
   </div>
</div>

<div class="row-fluid">
    <div class="span12 pull-right">
        {{ Form::submit('Save',array('class'=>'btn primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}
    </div>
</div>

{{Former::close()}}

<script type="text/javascript">


$(document).ready(function() {

    $('#title').keyup(function(){
        var title = $('#title').val();
        var slug = string_to_slug(title);
        $('#permalink').val(slug);
    });

    $('.eventdate').on('apply',function(ev,picker){
        console.log(moment( picker.endDate ,'MM/DD/YYYY'));
        $('#expires').val( picker.endDate.add('weeks',2).format('MM/DD/YYYY') );
    });

});

</script>

@stop