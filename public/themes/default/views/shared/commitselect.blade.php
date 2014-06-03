@extends('layout.front')

@section('content')

<style type="text/css">
    .form-horizontal .controls {
        margin-left: 0px;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
        $('#select_all').on('click',function(){
            if($('#select_all').is(':checked')){
                $('.selector').attr('checked', true);
            }else{
                $('.selector').attr('checked', false);
            }
        });

        $('#edit_select_all').on('click',function(){
            if($('#edit_select_all').is(':checked')){
                $('.edit_selector').attr('checked', true);
            }else{
                $('.edit_selector').attr('checked', false);
            }
        });

    });

</script>

<h3>Import {{ $title }}</h3>
{{Former::open_for_files($submit,'POST',array('class'=>'custom addAttendeeForm'))}}
<div class="row-fluid">
    <div class="span12">
        {{ Former::select('edit_key')->label('Edit Key')->options($headselect)->id('importkey')->class('importkey')->help('select to set which field used for update id') }}
        {{ Former::submit('Commit Import')->id('execute')->class('btn') }}
    </div>
</div>
<div class="row-fluid">
    <div class="span12">


        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>
                        <label>Select All</label>
                        <input id="select_all" type="checkbox">
                    </th>
                    <th>
                        <label>Force Update</label>
                        <input id="edit_select_all" type="checkbox">
                    </th>
                    <?php $head_id = 0; ?>
                    @foreach($heads as $head)
                        <th>
                            {{ Former::select()->name('headers[]')->label('')->options($headselect)->id($head)->class('heads')->value($head) }}
                            <?php $head_id++; ?>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($imports->toArray() as $row)
                <tr>
                    <td>
                        <input class="selector" name="selector[]" value="{{ $row['_id'] }}" type="checkbox">
                    </td>
                    <td>
                        <input class="edit_selector" name="edit_selector[]" value="{{ $row['_id'] }}" type="checkbox">
                    </td>
                    @foreach($row as $d)
                        <td>
                            {{ $d }}
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
{{ Former::close() }}
@stop