@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files_vertical($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

{{ Former::hidden('id')->value($formdata['_id']) }}

<div class="row-fluid">
    <div class="span9">
        {{ Former::textarea('body','Body')->name('body')->class('code')->id('body')->style('min-height:600px;') }}
    </div>
    <div class="span3">
        <a href="{{ URL::to('epreview/'.$formdata['_id'])}}" class="btn btn-info" target="blank">Web Preview</a>
        <div class="form-inline">
            {{ Former::text('preview_mail','Preview Email')->class('span10')->id('preview-to') }}<span id="bt-send-preview" data-templateid="{{ $formdata['_id'] }}" class="btn btn-info btn-sm">Send</span>
        </div>

        {{ Former::select('status')->options(array('inactive'=>'Inactive','active'=>'Active'))->label('Status') }}
        {{ Former::text('title','Title') }}
        {{ Former::text('slug','Permalink')->id('permalink') }}
        {{ Former::select('category')->options(Prefs::getCategory()->catToSelection('title','title'))->label('Category') }}
        {{ Former::text('tags','Tags')->class('tag_keyword') }}
        {{ Former::text('properties','Properties to be featured')->class('tag_property') }}

        <?php
            $fupload = new Fupload();
        ?>

        {{ $fupload->id('imageupload')->title('Select Images')->label('Upload Images')->make($formdata) }}
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

    $('#bt-send-preview').on('click',function(){
        $.post(
                '{{ URL::to('newsletter/mailpreview') }}',
                {
                    'tid' : $('#bt-send-preview').data('templateid'),
                    'body' : $('#body').val(),
                    'to' : $('#preview-to').val()
                },
                function(data){
                    if(data.result == 'OK'){
                        alert('preview sent');
                    }else{
                        alert('faild to send preview');
                    }
                },'json'
            );
    });

});

</script>

@stop