<?php

class Prefs {

    public static $category;
    public static $faqcategory;
    public static $principal;
    public static $agent;
    public static $contactgroup;
    public static $newsletter;

    public function __construct()
    {

    }

    public static function getChildPage($parent){
        return Page::where('menu',$parent)->orderBy('menuSeq','asc')->get();
    }

    public static function getCategory(){
        $c = Contactgroup::get();

        self::$category = $c;
        return new self;
    }

    public function catToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'All');
        }else{
            $ret = array();
        }

        foreach (self::$category as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function catToArray()
    {
        return self::$category;
    }

    //contact group
    public static function getContactGroup(){
        $c = Contactgroup::get();

        self::$category = $c;
        return new self;
    }

    public function contactGroupToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'All');
        }else{
            $ret = array();
        }

        foreach (self::$category as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function contactGroupToArray()
    {
        return self::$category;
    }


    //newsletter
    public static function getNewsletter(){
        $c = Template::where('type','newsletter')->get();

        self::$newsletter = $c;
        return new self;
    }

    public function newsletterToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'All');
        }else{
            $ret = array();
        }

        foreach (self::$newsletter as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function newsletterToArray()
    {
        return self::$newsletter;
    }

    public static function getPrincipal(){
        $c = Principal::get();

        self::$principal = $c;

        return new self;
    }

    public function principalToSelection($value, $label, $all = true)
    {
        $nodup = false;

        if($all){
            if(is_bool($all)){
                $ret = array('internal'=>'Internal');
            }else{

                $ic = Principal::where('company', $all)->first();

                if($ic){
                    $ret = array($ic->_id=>$ic->company);
                    $nodup = true;
                    $def_id = $ic->_id;
                }else{
                    $ret = array('internal'=>'Internal');
                }
            }
        }else{
            $ret = array();
        }

        foreach (self::$principal as $c) {
            if($nodup == true){
                if($def_id != $c->_id){
                    $ret[$c->{$value}] = $c->{$label};
                }
            }else{
                $ret[$c->{$value}] = $c->{$label};
            }
        }


        return $ret;
    }

    public function principalToArray()
    {
        return self::$principal;
    }


    public static function getAgent(){
        $c = Agent::get();

        self::$agent = $c;
        return new self;
    }

    public function agentToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'All');
        }else{
            $ret = array();
        }

        foreach (self::$agent as $c) {
            if($label == 'fullname'){
                $ret[$c->{$value}] = $c->firstname.' '.$c->lastname;
            }else{
                $ret[$c->{$value}] = $c->{$label};
            }
        }

        return $ret;
    }

    public function agentToArray()
    {
        return self::$agent;
    }


    public static function getFAQCategory(){
        $c = Faqcat::get();

        self::$faqcategory = $c;
        return new self;
    }

    public function FAQcatToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'All');
        }else{
            $ret = array();
        }

        foreach (self::$faqcategory as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function FAQcatToArray()
    {
        return self::$faqcategory;
    }

    public static function makeQueueId()
    {
        return Str::random(10);
    }


    public static function themeAssetsUrl()
    {
        return URL::to('/').'/'.Theme::getCurrentTheme();
    }

    public static function themeAssetsPath()
    {
        return 'themes/'.Theme::getCurrentTheme().'/assets/';
    }

    public static function getActiveTheme()
    {
        return Config::get('kickstart.default_theme');
    }

}
