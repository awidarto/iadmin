@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

{{ Former::hidden('id')->value($formdata['_id']) }}
<div class="row-fluid">
    <div class="span6">
        {{ Former::select('slidetype')->options( Config::get('ia.slidetype') )->label('Type')->required() }}
        {{ Former::text('sequence','Sequence')->class('span2')->value(1)->help('ascending display sequence') }}
        {{ Former::select('publishing')->options(array('unpublished'=>'Unpublished','published'=>'Published'))->label('Status') }}

        <h6>Video</h6>
        {{ Former::select('videoType')->options(array('youtube'=>'Youtube','local'=>'Local'))->label('Video Type') }}
        {{ Former::text('videoTitle','Title') }}
        {{ Former::text('youtubeUrl','Youtube ID') }}

        <h6>Content</h6>
        {{ Former::textarea('content','HTML Content')->class('span10 editor')->rows(8)->help('Use HTML tags to format content') }}

    </div>
    <div class="span6">
        <h6>Image</h6>
        <?php
            $fupload = new Fupload();
        ?>

        {{ $fupload->id('imageupload')->title('Select Images')->label('Upload Images')->url('upload/slide')->make($formdata) }}

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

    $('.editor').ckeditor({
        height:'400px'
    });

});

</script>

@stop