<?php

class NewsletterController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        $this->model = new Template();
        //$this->model = DB::collection('documents');
        $this->title = $this->controller_name;

    }

    public function getTest()
    {
        $raw = $this->model->where('docFormat','like','picture')->get();

        print $raw->toJSON();
    }


    public function getIndex()
    {

        $categories = Prefs::getCategory()->catToSelection('title','title');

        $this->heads = array(
            array('Title',array('search'=>true,'sort'=>true)),
            array('Status',array('search'=>true,'sort'=>true)),
            array('Creator',array('search'=>true,'sort'=>false)),
            array('Category',array('search'=>true,'select'=>$categories,'sort'=>true)),
            array('Featured Properties',array('search'=>true,'sort'=>true)),
            array('Tags',array('search'=>true,'sort'=>true)),
            array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->additional_query = array('type'=>'newsletter');

        $this->fields = array(
            array('title',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('status',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('creatorName',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'attr'=>array('class'=>'expander'))),
            array('category',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('properties',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'callback'=>'splitTag')),
            array('tags',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'callback'=>'splitTag')),
            array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
        );

        return parent::postIndex();
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'title' => 'required',
            'slug'=> 'required'
        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'title' => 'required',
            'slug'=> 'required'
        );

        return parent::postEdit($id,$data);
    }

    public function beforeSave($data)
    {
        $data['creatorName'] = Auth::user()->fullname;
        $data['type'] = 'newsletter';

        $data['status'] = 'inactive';

        $template = Str::random(8);


        if(file_put_contents(public_path().'/themes/default/views/newslettertmpl/'.$template.'.blade.php', $data['body'])){
            $data['template'] = $template;
        }


        $defaults = array();
        $files = array();

        if( isset($data['file_id']) && count($data['file_id'])){

            $data['defaultpic'] = (isset($data['defaultpic']))?$data['defaultpic']:$data['file_id'][0];
            $data['brchead'] = (isset($data['brchead']))?$data['brchead']:$data['file_id'][0];
            $data['brc1'] = (isset($data['brc1']))?$data['brc1']:$data['file_id'][0];
            $data['brc2'] = (isset($data['brc2']))?$data['brc2']:$data['file_id'][0];
            $data['brc3'] = (isset($data['brc3']))?$data['brc3']:$data['file_id'][0];

            for($i = 0 ; $i < count($data['thumbnail_url']);$i++ ){

                if($data['defaultpic'] == $data['file_id'][$i]){
                    $defaults['thumbnail_url'] = $data['thumbnail_url'][$i];
                    $defaults['large_url'] = $data['large_url'][$i];
                    $defaults['medium_url'] = $data['medium_url'][$i];
                    $defaults['full_url'] = $data['full_url'][$i];
                }

                $files[$data['file_id'][$i]]['thumbnail_url'] = $data['thumbnail_url'][$i];
                $files[$data['file_id'][$i]]['large_url'] = $data['large_url'][$i];
                $files[$data['file_id'][$i]]['medium_url'] = $data['medium_url'][$i];
                $files[$data['file_id'][$i]]['full_url'] = $data['full_url'][$i];

                $files[$data['file_id'][$i]]['delete_type'] = $data['delete_type'][$i];
                $files[$data['file_id'][$i]]['delete_url'] = $data['delete_url'][$i];
                $files[$data['file_id'][$i]]['filename'] = $data['filename'][$i];
                $files[$data['file_id'][$i]]['filesize'] = $data['filesize'][$i];
                $files[$data['file_id'][$i]]['temp_dir'] = $data['temp_dir'][$i];
                $files[$data['file_id'][$i]]['filetype'] = $data['filetype'][$i];
                $files[$data['file_id'][$i]]['fileurl'] = $data['fileurl'][$i];
                $files[$data['file_id'][$i]]['file_id'] = $data['file_id'][$i];
                $files[$data['file_id'][$i]]['caption'] = $data['caption'][$i];
            }
        }else{
            $data['thumbnail_url'] = array();
            $data['large_url'] = array();
            $data['medium_url'] = array();
            $data['full_url'] = array();
            $data['delete_type'] = array();
            $data['delete_url'] = array();
            $data['filename'] = array();
            $data['filesize'] = array();
            $data['temp_dir'] = array();
            $data['filetype'] = array();
            $data['fileurl'] = array();
            $data['file_id'] = array();
            $data['caption'] = array();

            $data['defaultpic'] = '';
            $data['brchead'] = '';
            $data['brc1'] = '';
            $data['brc2'] = '';
            $data['brc3'] = '';
        }

        $data['defaultpictures'] = $defaults;
        $data['files'] = $files;

        return $data;
    }

    public function beforeUpdate($id,$data)
    {
        $template = ($data['template'] == '')?Str::random(8):$data['template'];

        //file_put_contents(public_path().'/themes/default/views/newslettertmpl/'.$template.'.blade.php', $data['body']);

        $defaults = array();
        $files = array();

        if( isset($data['file_id']) && count($data['file_id'])){

            $data['defaultpic'] = (isset($data['defaultpic']))?$data['defaultpic']:$data['file_id'][0];
            $data['brchead'] = (isset($data['brchead']))?$data['brchead']:$data['file_id'][0];
            $data['brc1'] = (isset($data['brc1']))?$data['brc1']:$data['file_id'][0];
            $data['brc2'] = (isset($data['brc2']))?$data['brc2']:$data['file_id'][0];
            $data['brc3'] = (isset($data['brc3']))?$data['brc3']:$data['file_id'][0];


            for($i = 0 ; $i < count($data['file_id']); $i++ ){


                $files[$data['file_id'][$i]]['thumbnail_url'] = $data['thumbnail_url'][$i];
                $files[$data['file_id'][$i]]['large_url'] = $data['large_url'][$i];
                $files[$data['file_id'][$i]]['medium_url'] = $data['medium_url'][$i];
                $files[$data['file_id'][$i]]['full_url'] = $data['full_url'][$i];

                $files[$data['file_id'][$i]]['delete_type'] = $data['delete_type'][$i];
                $files[$data['file_id'][$i]]['delete_url'] = $data['delete_url'][$i];
                $files[$data['file_id'][$i]]['filename'] = $data['filename'][$i];
                $files[$data['file_id'][$i]]['filesize'] = $data['filesize'][$i];
                $files[$data['file_id'][$i]]['temp_dir'] = $data['temp_dir'][$i];
                $files[$data['file_id'][$i]]['filetype'] = $data['filetype'][$i];
                $files[$data['file_id'][$i]]['fileurl'] = $data['fileurl'][$i];
                $files[$data['file_id'][$i]]['file_id'] = $data['file_id'][$i];
                $files[$data['file_id'][$i]]['caption'] = $data['caption'][$i];

                if($data['defaultpic'] == $data['file_id'][$i]){
                    $defaults['thumbnail_url'] = $data['thumbnail_url'][$i];
                    $defaults['large_url'] = $data['large_url'][$i];
                    $defaults['medium_url'] = $data['medium_url'][$i];
                    $defaults['full_url'] = $data['full_url'][$i];
                }

                if($data['brchead'] == $data['file_id'][$i]){
                    $defaults['brchead'] = $data['large_url'][$i];
                }

                if($data['brc1'] == $data['file_id'][$i]){
                    $defaults['brc1'] = $data['large_url'][$i];
                }

                if($data['brc2'] == $data['file_id'][$i]){
                    $defaults['brc2'] = $data['large_url'][$i];
                }

                if($data['brc3'] == $data['file_id'][$i]){
                    $defaults['brc3'] = $data['large_url'][$i];
                }


            }
        }else{

            $data['thumbnail_url'] = array();
            $data['large_url'] = array();
            $data['medium_url'] = array();
            $data['full_url'] = array();
            $data['delete_type'] = array();
            $data['delete_url'] = array();
            $data['filename'] = array();
            $data['filesize'] = array();
            $data['temp_dir'] = array();
            $data['filetype'] = array();
            $data['fileurl'] = array();
            $data['file_id'] = array();
            $data['caption'] = array();

            $data['defaultpic'] = '';
            $data['brchead'] = '';
            $data['brc1'] = '';
            $data['brc2'] = '';
            $data['brc3'] = '';
        }

        $data['defaultpictures'] = $defaults;
        $data['files'] = $files;

        return $data;
    }


    public function getPreview($template,$type = null)
    {
        $prop = Property::where('brchead','exists', true)->where('brchead','!=', '')->first()->toArray();

        //print_r($prop);

        //die();

        //return View::make('print.brochure')->with('prop',$prop)->render();

        if(!is_null($type) && $type != 'pdf'){
            $content = View::make('brochuretmpl.'.$template)->with('prop',$prop)->render();
            return $content;
        }else{
            //return PDF::loadView('print.brochure',array('prop'=>$prop))
            //    ->stream('download.pdf');

            return PDF::loadView('brochuretmpl.'.$template, array('prop'=>$prop))
                        ->setOption('margin-top', '0mm')
                        ->setOption('margin-left', '0mm')
                        ->setOption('margin-right', '0mm')
                        ->setOption('margin-bottom', '0mm')
                        ->setOption('dpi',200)
                        ->setPaper('A4')
                        ->stream($prop['propertyId'].'.pdf');

            //return PDF::html('print.brochure',array('prop' => $prop), 'download.pdf');
        }

    }

    public function postMailpreview(){

            $templateId = Input::get('tid');
            $templateBody = Input::get('body');
            $recipient = Input::get('to');

            $template = Template::find($templateId);

            $template->body = $templateBody;

            $props = $template->properties;

            $props = explode(',',$props);
            if(count($props) > 0){
                $property = Property::whereIn('propertyId',$props)->get()->toArray();
            }else{
                $property = array();
            }


            $recinfo = Buyer::first()->toArray();

            $content = DbView::make($template)->field('body')->with('rec', $recinfo)->with('prop',$property)->render();

            Mail::send('emails.blank',array('body'=>$content), function($message) use ($recinfo){
                $to = $recinfo['email'];

                $fullname = $recinfo['firstname'].' '.$recinfo['lastname'];

                $message->to($to, $fullname);

                $message->subject('Investors Alliance - E newsletter');

                $message->from('support@propinvestorsalliance.com');

                $message->cc('support@propinvestorsalliance.com');

                //$message->attach(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf');
            });

            return Response::json(array('result'=>'OK'));
    }

    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="icon-trash"></i> Delete</span>';
        $dupe = '<span class="dupe" id="'.$data['_id'].'" ><i class="icon-copy"></i> Duplicate</span>';
        $active = '<span class="active" id="'.$data['_id'].'" ><i class="icon-trash"></i> Set Active</span>';
        $edit = '<a href="'.URL::to('newsletter/edit/'.$data['_id']).'"><i class="icon-edit"></i> Update</a>';

        $pdf = '<a href="'.URL::to('newsletter/preview/'.$data['template'].'/pdf').'" target="blank"
        ><i class="icon-edit"></i>PDF Preview</a>';
        $html = '<a href="'.URL::to('epreview/'.$data['_id']).'" target="blank"
        ><i class="icon-edit"></i>HTML Preview</a>';

        $actions = $edit.'<br />'.$active.'<br />'.$dupe.'<br />'.$delete.'<br />'.$html;
        return $actions;
    }

    public function splitTag($data,$field = 'tags'){
        $tags = explode(',',$data[$field]);
        if(is_array($tags) && count($tags) > 0 && $data[$field] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data[$field];
        }
    }

    public function splitShare($data){
        $tags = explode(',',$data['docShare']);
        if(is_array($tags) && count($tags) > 0 && $data['docShare'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['docShare'];
        }
    }

    public function namePic($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('id' => $data['_id']));
            return $display.'<br />'.$name;
        }else{
            return $name;
        }
    }

    public function pics($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('style'=>'min-width:100px;','id' => $data['_id']));
            return $display.'<br /><span class="img-more" id="'.$data['_id'].'">more images</span>';
        }else{
            return $name;
        }
    }

    public function getViewpics($id)
    {

    }


}
