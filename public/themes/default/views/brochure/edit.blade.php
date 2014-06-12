@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files_vertical($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

{{ Former::hidden('id')->value($formdata['_id']) }}

{{ Former::hidden('template')->value($formdata['template']) }}

<div class="row-fluid">
    <div class="span9">
        {{ Former::textarea('body','Body')->name('body')->class('code')->id('body')->style('min-height:600px;') }}
    </div>
    <div class="span3">
        <a href="{{ URL::to('brochure/preview/'.$formdata['template'].'/pdf')}}" class="btn" target="blank">PDF Preview</a>
        {{ Former::select('status')->options(array('inactive'=>'Inactive','active'=>'Active'))->label('Status') }}
        {{ Former::text('title','Title') }}
        {{ Former::text('slug','Permalink')->id('permalink') }}
        {{ Former::select('category')->options(Prefs::getCategory()->catToSelection('title','title'))->label('Category') }}
        {{ Former::text('tags','Tags')->class('tag_keyword') }}

        <h3>PDF Generator</h3>
        {{ Former::text('paper-size','Page Size') }}
        {{ Former::text('dpi','Resolution (DPI)') }}
        {{ Former::text('margin-top','Margin Top') }}
        {{ Former::text('margin-bottom','Margin Bottom') }}
        {{ Former::text('margin-left','Margin Left') }}
        {{ Former::text('margin-right','Margin Right') }}

        <h3>Images</h3>

        <?php
            $fupload = new Fupload();
        ?>

        {{ $fupload->id('imageupload')->title('Select Images')->label('Upload Images')->make() }}
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        {{ Form::submit('Save',array('class'=>'btn primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}
    </div>
</div>


{{Former::close()}}

<style type="text/css">
#lyric{
    min-height: 350px;
    height: 400px;
}
</style>

{{ HTML::script('js/ace/ace.js') }}
{{ HTML::script('js/ace/theme-twilight.js') }}
{{ HTML::script('js/ace/mode-php.js') }}
{{ HTML::script('js/jquery-ace.min.js') }}


{{-- HTML::script('js/codemirror/lib/codemirror.js') --}}
{{-- HTML::script('js/codemirror/mode/php/php.js') --}}
{{-- HTML::script('js/codemirror/mode/xml/xml.js') --}}


{{-- HTML::style('css/summernote-bs2.css') --}}
{{-- HTML::style('css/summernote.css')--}}
{{-- HTML::style('css/summernote-bp.css')--}}
{{-- HTML::script('js/summernote.min.js') --}}

{{-- HTML::style('js/codemirror/lib/codemirror.css') --}}
{{-- HTML::style('js/codemirror/theme/twilight.css') --}}

<script type="text/javascript">


$(document).ready(function() {


    $('#title').keyup(function(){
        var title = $('#title').val();
        var slug = string_to_slug(title);
        $('#permalink').val(slug);
    });

    $('.code').ace({ theme: 'twilight', lang: 'php' });
    /*
    $('#body').summernote({
        height:'600px',
        codemirror: {
            'theme':'twilight',
            'mode':'php'
        }
    });
    */

});

</script>

@stop