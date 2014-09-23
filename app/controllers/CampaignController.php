<?php

class CampaignController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        $this->model = new Campaign();
        //$this->model = DB::collection('documents');

    }

    public function getTest()
    {
        $raw = $this->model->where('docFormat','like','picture')->get();

        print $raw->toJSON();
    }


    public function getIndex()
    {

        $this->heads = array(
            array('Title',array('search'=>false,'sort'=>false)),
            array('Status',array('search'=>true,'sort'=>true)),
            array('From',array('search'=>true,'sort'=>true)),
            array('To',array('search'=>true,'sort'=>true)),
            array('Trigger',array('search'=>true,'sort'=>true)),
            array('Tags',array('search'=>true,'sort'=>true)),
            array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Campaigns';

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('title',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('status',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('fromDate',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('toDate',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('sendOption',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('tags',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('createdDate',array('kind'=>'date','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
        );

        return parent::postIndex();
    }

    public function beforeSave($data)
    {
        //$defaultexpiry = Carbon::fromDate($data['toDate'])->addWeeks(2);
        /*
        if($data['expires'] == '') {
            $data['expires'] = new MongoDate($defaultexpiry);
        }
        */
        return $data;
    }

    public function beforeUpdate($id,$data)
    {

        return $data;
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'title' => 'required',
        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'title' => 'required',
        );

        return parent::postEdit($id,$data);
    }

    public function afterSave($data)
    {
        //print_r($data);
        //exit();
        $id = $data['_id'];

        if($data['sendOption'] == 'immediately'){
            $senddate = new MongoDate();
        }else{
            $senddate = new MongoDate( strtotime($data['sendDate']) );
        }

        $contacts = Buyer::where('assigned_group', $data['contactGroup'])->get()->toArray();

        foreach($contacts as $contact){
            $q = new Mailqueue();
            $q->campaignId = $id;
            $q->email = $contact['email'];
            $q->template = $data['newsletterTemplate'];
            $q->sendFromName = $data['sendFromName'];
            $q->sendFromEmail = $data['sendFromEmail'];
            $q->sendDate = $senddate;
            $q->status = 'unsent';
            $q->save();
        }

        return $data;
    }

    public function afterUpdate($id,$data = null)
    {
        if($data['sendOption'] == 'immediately'){
            $senddate = new MongoDate();
        }else{
            $senddate = new MongoDate( strtotime($data['sendDate']) );
        }

        $contacts = Buyer::where('assigned_group', $data['contactGroup'])->get()->toArray();

        foreach($contacts as $contact){
            $q = new Mailqueue();
            $q->campaignId = $id;
            $q->email = $contact['email'];
            $q->template = $data['newsletterTemplate'];
            $q->sendDate = $senddate;
            $q->status = 'unsent';
            $q->save();
        }


        return $id;
    }


    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="icon-trash"></i>Delete</span>';
        $edit = '<a href="'.URL::to('campaign/edit/'.$data['_id']).'"><i class="icon-edit"></i>Update</a>';

        $actions = $edit.'<br />'.$delete;
        return $actions;
    }

    public function splitTag($data){
        $tags = explode(',',$data['docTag']);
        if(is_array($tags) && count($tags) > 0 && $data['docTag'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['docTag'];
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
        $name = HTML::link('property/view/'.$data['_id'],$data['address']);

        $thumbnail_url = '';

        if(isset($data['files']) && count($data['files'])){
            $glinks = '';

            $gdata = $data['files'][$data['defaultpic']];

            $thumbnail_url = $gdata['thumbnail_url'];
            foreach($data['files'] as $g){
                $glinks .= '<input type="hidden" class="g_'.$data['_id'].'" data-caption="'.$g['caption'].'" value="'.$g['fileurl'].'" >';
            }

            $display = HTML::image($thumbnail_url.'?'.time(), $thumbnail_url, array('class'=>'thumbnail img-polaroid','id' => $data['_id'])).$glinks;
            return $display;
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
