<?php

class FaqController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        $this->model = new Faq();
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

        $categories = Prefs::getFAQCategory()->FAQcatToSelection('title','title');

        $this->heads = array(
            array('Seq',array('search'=>false,'sort'=>true)),
            array('Id',array('search'=>false,'sort'=>true)),
            array('Title',array('search'=>true,'sort'=>false)),
            array('Creator',array('search'=>true,'sort'=>false)),
            array('Category',array('search'=>true,'select'=>$categories,'sort'=>false)),
            array('Tags',array('search'=>true,'sort'=>false)),
            array('Created',array('search'=>true,'sort'=>false,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>false,'date'=>true)),
        );

        $this->title = 'FAQ Entries';

        $this->def_order_by = 'sequence';
        $this->def_order_dir = 'asc';

        $this->table_dnd = true;
        $this->table_dnd_url = URL::to(strtolower($this->controller_name).'/reorder');
        $this->table_dnd_idx = 3;
        //print $this->model->where('docFormat','picture')->get()->toJSON();

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('sequence',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('_id',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true )),
            array('title',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'callback'=>'reorderIcon' )),
            array('creatorName',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'attr'=>array('class'=>'expander'))),
            array('category',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
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

        $count = Faq::count();

        $data['sequence'] = $count+1;

        return $data;
    }

    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="icon-trash"></i>Delete</span>';
        $edit = '<a href="'.URL::to('faq/edit/'.$data['_id']).'"><i class="icon-edit"></i>Update</a>';

        $actions = $edit.'<br />'.$delete;
        return $actions;
    }

    public function getReorder()
    {
        $in = Input::get();

        $id = $in['id'];
        $fromPosition = (int) $in['fromPosition'];
        $toPosition = (int) $in['toPosition'];
        $direction  = $in['direction'];

        if($direction == 'back'){
            /*
            If back:
            UPDATE table_name SET order_field = (`order_field` - 1) WHERE order_field < '{$to}' AND order_field > '{$from}';
            */
            Faq::where('_id',new MongoId($id))->update( array('sequence'=>$toPosition) );

            Faq::where('sequence','>=',$toPosition)
                ->where('sequence','<',$fromPosition)
                ->where('_id','<>', new MongoId($id))
                ->increment('sequence');

        }

        if($direction == 'forward'){
            /*
            If forward:
            UPDATE table_name SET order_field = (`order_field` + 1) WHERE order_field > '{$from}' AND order_field <= '{$to}';
            */
            Faq::where('_id',new MongoId($id))->update( array('sequence'=>$toPosition) );

            Faq::where('sequence','>',$fromPosition)
                ->where('sequence','<=',$toPosition)
                ->where('_id','<>', new MongoId($id))
                ->decrement('sequence');
        }
            /*
            and the row we move:
            UPDATE table_name SET order_field = '{$to}' WHERE id = '{$id}';
            */

    }

    public function getOldReorder()
    {
        $in = Input::get();

        $id = $in['id'];
        $fromPosition = $in['fromPosition'];
        $toPosition = $in['toPosition'];
        $direction  = $in['direction'];


        $aPosition    = ($direction === "back") ? $toPosition+1 : $toPosition-1;

            //mysql_query("UPDATE site_slideshow SET slideshow_ordem = 0          WHERE slideshow_ordem = '".$toPosition."'");
        Faq::where('sequence',$toPosition)->update(array('sequence'=>(int)0));
            //mysql_query("UPDATE site_slideshow SET slideshow_ordem = $toPosition WHERE slideshow_id = '".$id."'");
        Faq::where('_id',new MongoId($id))->update(array('sequence'=>(int)$toPosition));

        if($direction === "back") {

            /*mysql_query("UPDATE site_slideshow SET slideshow_ordem = slideshow_ordem + 1
                WHERE ($toPosition <= slideshow_ordem AND slideshow_ordem <= $fromPosition)
                and slideshow_id != $id and slideshow_ordem != 0 ORDER BY slideshow_ordem DESC;");*/
                $q = array('$and'=>array(
                                array('sequence'=>array('$gte'=>$toPosition),
                                array('sequence'=>array('$lte'=>$fromPosition),
                            ),
                        array(
                                'id'=>array('$ne'=>new MongoId($id)),
                                'sequence'=>array('$ne'=>0)
                            )
                        )
                    ));
            $moved = Faq::whereRaw($q)
                //where('sequence' ,'>=', $toPosition )
                //->where('sequence' ,'<=', $fromPosition )
                //->where('_id','<>',new MongoId($id))
                //->where('sequence','<>',0)
                ->orderBy('sequence','desc')
                ->increment('sequence');

            print($moved);
        } // backward direction

        if($direction === "forward") {
            /*mysql_query("UPDATE site_slideshow SET slideshow_ordem = slideshow_ordem - 1
                WHERE ($fromPosition <= slideshow_ordem AND slideshow_ordem <= $toPosition)
                and slideshow_id != $id and slideshow_ordem != 0 ORDER BY slideshow_ordem ASC;");*/
            Faq::where('sequence' ,'<=', $toPosition )
                ->where('sequence' ,'>=', $fromPosition )
                ->where('_id','<>',new MongoId($id))
                ->where('sequence','<>',0)
                ->orderBy('sequence','asc')
                ->decrement('sequence');
        } // Forward Direction


        //mysql_query("UPDATE site_slideshow SET slideshow_ordem = $aPosition WHERE slideshow_ordem = 0;");

        Faq::where('sequence',0)->update(array('sequence'=>$aPosition));

    }

    public function reorderIcon($data){
        return '<span class="reorder" ><i class="icon-move"></i> '.$data['title'].'</span>';
    }

    public function splitTag($data){
        $tags = explode(',',$data['tags']);
        if(is_array($tags) && count($tags) > 0 && $data['tags'] != ''){
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
