<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::controller('document', 'DocumentController');
Route::controller('property', 'PropertyController');
Route::controller('user', 'UserController');
Route::controller('agent', 'AgentController');
Route::controller('buyer', 'BuyerController');
Route::controller('principal', 'PrincipalController');
Route::controller('potential', 'PotentialController');
Route::controller('report', 'ReportController');
Route::controller('pages', 'PagesController');
Route::controller('posts', 'PostsController');
Route::controller('category', 'CategoryController');
Route::controller('menu', 'MenuController');

Route::controller('picture','PictureController');

Route::controller('enquiry', 'EnquiryController');

Route::controller('order', 'OrderController');

Route::controller('template', 'TemplateController');
Route::controller('edition', 'EditionController');
Route::controller('newsletter', 'NewsletterController');
Route::controller('campaign', 'CampaignController');
Route::controller('contactgroup', 'ContactgroupController');

Route::controller('brochure', 'BrochureController');
Route::controller('option', 'OptionController');


Route::controller('propmanager', 'PropmanagerController');
Route::controller('promocode', 'PromocodeController');
Route::controller('transaction', 'TransactionController');
Route::controller('financial', 'FinancialController');

Route::controller('faq', 'FaqController');
Route::controller('faqcat', 'FaqcatController');

Route::controller('glossary', 'GlossaryController');

Route::controller('activity', 'ActivityController');
Route::controller('access', 'AccessController');

Route::controller('inprop', 'InpropController');
Route::controller('homeslide', 'HomeslideController');


Route::controller('music', 'MusicController');
Route::controller('event', 'EventController');


Route::controller('upload', 'UploadController');
Route::controller('ajax', 'AjaxController');

Route::controller('home', 'HomeController');

Route::get('/', 'PropertyController@getIndex');


Route::get('content/pages', 'PagesController@getIndex');
Route::get('content/posts', 'PostsController@getIndex');
Route::get('content/category', 'CategoryController@getIndex');
Route::get('content/menu', 'MenuController@getIndex');



Route::get('regenerate',function(){
    $property = new Property();

    $props = $property->get()->toArray();

    $seq = new Sequence();

    foreach($props as $p){

        $_id = new MongoId($p['_id']);

        if($p['propertyId'] == ''){
            $nseq = $seq->getNewId('property');

            $sdata = array(
                'sequence'=>$nseq,
                'propertyId' => Config::get('ia.property_id_prefix').$nseq
                );

            if( $property->where('_id','=', $_id )->update( $sdata ) ){
                print $p['_id'].'->'.$sdata['propertyId'].'<br />';
            }
        }

    }

});

Route::get('ccheck',function(){
    $count = Faq::count();

    print_r($count);

});

Route::get('etemplate',function(){
    return View::make('emails.newsletter');
});

Route::get('epreview/{id}',function($id){
    $template = Template::find($id);

    $props = $template->properties;

    $props = explode(',',$props);
    if(count($props) > 0){
        $property = Property::whereIn('propertyId',$props)->get()->toArray();
    }

    return DbView::make($template)->field('body')->with('prop',$property);
});

Route::get('addseq/{c?}',function($c = 'faq'){

    if($c == 'faq'){
        $faqs = Faq::orderBy('createdDate','desc')->get();
    }else if($c == 'glossary'){
        $faqs = Glossary::orderBy('createdDate','desc')->get();
    }
    $seq = 1;
    foreach($faqs as $f){
        $f->sequence = $seq;
        $f->save();
        $seq++;
    }
});

Route::get('proi',function(){
    $price = 100000;
    $pct = 3;
    $year = 5;
    $roi = 0;
    $initprice = $price;
    $rental = 1000;

    $counter = $year;

    $result = 0;

    $projected = px($price, $pct, $year,$initprice,$rental ,$roi, $counter, $result);

    print 'projected ROI : '.$result;


});

function fv($initprice, $pct, $year, $counter ,&$result){
    if($counter == 0){
        return $initprice;
    }else{
        $initprice = $initprice * ( (100 + $pct) / 100 );

        //print $initprice."\r\n";

        $result = $initprice;
        $counter--;
        fv($initprice, $pct, $year ,$counter, $result);
    }
}


function px($price, $pct, $year, $initprice,$rental ,$roi, $counter, $netroi ,&$result){
    if($counter == 0){
        return $roi;
    }else{
        $price = $price + ($price * ( $pct / 100));
        $mult = ($year - $counter) + 1;
        //$rental = $rental * $mult;

        $roi = (($price - $initprice) + ( $rental * $mult * $netroi ) )/ $initprice;
        $result = $roi;
        /*
        print_r(
            array(
                'price'=>$price,
                'rental'=>$rental,
                'netroi'=>$netroi,
                'roi'=>$roi,
                'result'=>$result,
                'counter'=>$counter,
                'year'=>$year,
                'mult'=>$mult
                )
            );
        */

        $counter--;
        px($price, $pct, $year, $initprice, $rental, $roi ,$counter, $netroi, $result);
    }
}

Route::get('fillprin/{prid}',function($prid){
    //$principal = '5344ea11ccae5b6f13000004';
    $principal = $prid;
    $pr = Principal::find($principal);

    if($pr){
        $props = Property::get();
        foreach($props as $p){
            $p->principal = $pr->_id;
            $p->principalName = $pr->company;
            $p->save();
        }
    }

});

Route::get('propman',function(){
    $property = new Property();

    $props = $property->distinct('propertyManager')->get();

    $propManArr = array();

    foreach($props as $p){
        $p = $p->toArray();
        //print $p[0];

        $propMan = $p[0];
        $propCount =    Property::where('propertyManager','=',$propMan)->count();
        $propLeaseMax = Property::where('propertyManager','=',$propMan)->max('leaseTerms');
        $propLeaseMin = Property::where('propertyManager','=',$propMan)->min('leaseTerms');
        $propLeaseAvg = Property::where('propertyManager','=',$propMan)->avg('leaseTerms');
        $propLeaseSum = Property::where('propertyManager','=',$propMan)->sum('leaseTerms');

        $propMonthlyMax = Property::where('propertyManager','=',$propMan)->max('monthlyRental');
        $propMonthlyMin = Property::where('propertyManager','=',$propMan)->min('monthlyRental');
        $propMonthlyAvg = Property::where('propertyManager','=',$propMan)->avg('monthlyRental');
        $propMonthlySum = Property::where('propertyManager','=',$propMan)->sum('monthlyRental');

        $pobj = Propman::where('name','=',$propMan)->first();

        if($pobj){
            $propManager = $pobj;
        }else{
            $propManager = new Propman();
            $propManager->createdDate = new MongoDate();
        }

        $propManager->name = $propMan;
        $propManager->count = $propCount;
        $propManager->max = $propLeaseMax;
        $propManager->min = $propLeaseMin;
        $propManager->avg = $propLeaseAvg;
        $propManager->sum = $propLeaseSum;

        $propManager->monthlyRental = $propMonthlySum;
        $propManager->monthlyMax = $propMonthlyMax;
        $propManager->monthlyMin = $propMonthlyMin;
        $propManager->monthlyAvg = $propMonthlyAvg;

        $propManager->annualRental = ($propMonthlySum * 12);

        $propManager->lastUpdate = new MongoDate();

        $propManager->save();

    }

    //print_r($propManArr);
    return Response::json(array('result'=>'OK:GENERATED'));

});

Route::get('tofin',function(){
    $property = new Property();

    $props = $property->get();

    foreach($props as $p){
        $p->monthlyRental = (double) $p->monthlyRental;

        $p->annualRental = 12 * $p->monthlyRental;

        $p->insurance = ($p->insurance == 0 || $p->insurance == '')?800:$p->insurance;

        $p->tax = $p->tax;
        $p->insurance = $p->insurance;
        $p->maintenanceAllowance = $p->annualRental * 0.1;
        $p->vacancyAllowance = $p->annualRental * 0.05;
        $p->propManagement = $p->annualRental * 0.1;

        if($p->FMV > 0){
            $p->equity = (($p->FMV - $p->listingPrice ) / $p->FMV ) * 100;
        }else{
            $p->equity = 0;
        }
        $p->dpsqft = $p->listingPrice / $p->houseSize;

        if($p->annualRental > 0){
            $p->ROI = ($p->annualRental - ( $p->tax + $p->propManagement + $p->insurance)) / $p->listingPrice * 100;
            $p->ROIstar = ($p->annualRental - ( $p->tax + $p->propManagement + $p->insurance + $p->vacancyAllowance + $p->maintenanceAllowance )) / $p->listingPrice * 100;
            $p->OPR = ($p->monthlyRental / $p->listingPrice )*100;
            $p->RentalYield = ($p->annualRental / $p->listingPrice) * 100 ;

        }else{
            $p->ROI = 0;
            $p->ROIstar = 0;
            $p->OPR = 0;
            $p->RentalYield = 0 ;
        }

        $p->tax = new MongoInt32( $p->tax);
        $p->insurance = new MongoInt32($p->insurance);
        $p->houseSize = new MongoInt32($p->houseSize);

        if( $p->lotSize < 100){
            $p->lotSize = (double) $p->lotSize * 43560;
        }else{
            $p->lotSize = (double) $p->lotSize;
        }

        $p->leaseTerms = (double) $p->leaseTerms;

        $p->yearBuilt = new MongoInt32($p->yearBuilt);

        //print_r($p->toArray());

        $p->save();

    }

    return Response::json(array('result'=>'OK:GENERATED'));

});


Route::get('tonumber',function(){
    $property = new Property();

    $props = $property->get()->toArray();

    $seq = new Sequence();

    foreach($props as $p){

        $_id = new MongoId($p['_id']);

        $price = new MongoInt32( $p['listingPrice'] );
        $fmv = new MongoInt32( $p['FMV'] );

        $sdata = array(
            'listingPrice'=>$price,
            'FMV'=>$fmv
            );

        if( $property->where('_id','=', $_id )->update( $sdata ) ){
            print $p['_id'].'->'.$sdata['listingPrice'].'<br />';
        }

    }

});



Route::get('defpic',function(){
    $property = new Property();

    $props = $property->get();

    $seq = new Sequence();

    foreach($props as $p){

        $defaultpic = $p->defaultpic;

        $defaultpictures = $p->defaultpictures;


        if( !empty($defaultpictures) ){

            if(!isset($p->files[$defaultpic]['full_url'])){
                $df = $p->files;
                $df[$defaultpic]['full_url'] = str_replace('lrg_', 'full_', $df[$defaultpic]['large_url']);
                $p->files = $df;
            }

            if(!isset($defaultpictures['full_url'])){

                $defaultpictures['full_url'] = $p->files[$defaultpic]['full_url'];

                print_r($defaultpictures);

                $p->defaultpictures = $defaultpictures;

                $p->save();
            }
        }


    }

});

Route::get('leasedate',function(){
    set_time_limit(0);

    $property = new Property();

    $props = $property->get();

    foreach($props as $p){
        //print $p[0]. "\r\n" ;

        if( $p->leaseStartDate instanceOf MongoDate ){
            print "already a date\r\n";
        }else{

            if(strripos( strtolower($p->leaseStartDate) , 'marketing') > 0){
                print $p->leaseStartDate."-> -\r\n";
                $p->leaseStartDate = '';
            }else{
                if(strtotime($p->leaseStartDate)){
                    print $p->leaseStartDate."-> valid date\r\n";
                    $p->leaseStartDate = new MongoDate( strtotime($p->leaseStartDate) );
                    print_r($p->leaseStartDate);
                }else{
                    if($p->leaseStartDate < 40000){
                        print $p->leaseStartDate."-> too old\r\n";
                        $p->leaseStartDate = '';
                    }else{
                        print $p->leaseStartDate."-> integer";

                        print PHPExcel_Shared_Date::ExcelToPHP($p->leaseStartDate)."->";

                        print date('Y-m-d H:i:s',PHPExcel_Shared_Date::ExcelToPHP($p->leaseStartDate))."\r\n";

                        $p->leaseStartDate = new MongoDate( PHPExcel_Shared_Date::ExcelToPHP($p->leaseStartDate) );
                        print_r($p->leaseStartDate);

                    }
                }
            }

            $p->save();

        }


        /*
        if(strripos( strtolower($p[0]) , '/') > 0 || strripos( strtolower($p[0]) , '-') > 0){
            print $p[0]."->". str ."\r\n";
        }
        */
    }


});

Route::get('regeneratepic',function(){

    set_time_limit(0);

    $property = new Property();

    $props = $property->get();

    $seq = new Sequence();

    $sizes = Config::get('picture.sizes');

    foreach($props as $p){
        $files = $p->files;

        foreach($files as $folder=>$files){

            $dir = public_path().'/storage/media/'.$folder;

            if (is_dir($dir) && file_exists($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if($file != '.' && $file != '..'){
                            if(!preg_match('/^lrg_|med_|th_|full_/', $file)){
                                echo $dir.'/'.$file."\n";

                                $destinationPath = $dir;
                                $filename = $file;

                                $thumbnail = Image::make($destinationPath.'/'.$filename)
                                    ->fit( $sizes['thumbnail']['width'] ,$sizes['thumbnail']['height'])
                                    ->save($destinationPath.'/th_'.$filename);

                                $medium = Image::make($destinationPath.'/'.$filename)
                                    ->fit( $sizes['medium']['width'] ,$sizes['medium']['height'])
                                    ->save($destinationPath.'/med_'.$filename);

                                $large = Image::make($destinationPath.'/'.$filename)
                                    ->fit( $sizes['large']['width'] ,$sizes['large']['height'])
                                    ->save($destinationPath.'/lrg_'.$filename);

                                $full = Image::make($destinationPath.'/'.$filename)
                                    ->save($destinationPath.'/full_'.$filename);

                            }
                        }
                    }
                    closedir($dh);
                }
            }
        }


    }

});

/*
Route::get('brochure/dl/{id}/{d?}',function($id, $d = null){

    $prop = Property::find($id)->toArray();

    $tmpl = Template::where('type','brochure')->where('status','active')->first();

    print_r($tmpl->toArray());

    die();

    //return View::make('print.brochure')->with('prop',$prop)->render();

    if(!is_null($d)){
        $content = View::make('print.brochure')->with('prop',$prop)->render();

        return $content;
    }else{
        //return PDF::loadView('print.brochure',array('prop'=>$prop))
        //    ->stream('download.pdf');

        return PDF::loadView('print.brochure', array('prop'=>$prop))
                    ->setOption('margin-top', '0mm')
                    ->setOption('margin-left', '0mm')
                    ->setOption('margin-right', '0mm')
                    ->setOption('margin-bottom', '0mm')
                    ->setOption('dpi',200)
                    ->setPaper('A4')
                    ->stream($prop['propertyId'].'.pdf');

        //return PDF::html('print.brochure',array('prop' => $prop), 'download.pdf');
    }
});

Route::post('brochure/mail/{id}',function($id){

    $prop = Property::find($id)->toArray();

    //$content = View::make('print.brochure')->with('prop',$prop)->render();

    $brochurepdf = PDF::loadView('print.brochure', array('prop'=>$prop))
                    ->setOption('margin-top', '0mm')
                    ->setOption('margin-left', '0mm')
                    ->setOption('margin-right', '0mm')
                    ->setOption('margin-bottom', '0mm')
                    ->setOption('dpi',200)
                    ->setPaper('A4')
                    ->output();

    file_put_contents(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf', $brochurepdf);

    //$mailcontent = View::make('emails.brochure')->with('prop',$prop)->render();

    Mail::send('emails.brochure',$prop, function($message) use ($prop, &$prop){
        $to = Input::get('to');
        $tos = explode(',', $to);
        if(is_array($tos) && count($tos) > 1){
            foreach($tos as $to){
                $message->to($to, $to);
            }
        }else{
                $message->to($to, $to);
        }

        $message->subject('Investors Alliance - '.$prop['propertyId']);

        $message->cc('support@propinvestorsalliance.com');

        $message->attach(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf');
    });

    print json_encode(array('result'=>'OK'));

});
*/

Route::get('pr/print/{id}',function($id){

    $trx = Transaction::find($id)->toArray();

    $prop = Property::find($trx['propObjectId'])->toArray();

    $agent = Agent::find($trx['agentId'])->toArray();

    return View::make('print.pr')->with('prop',$prop)->with('trx',$trx)->with('agent',$agent);

    //$content = View::make('print.brochure')->with('prop',$prop)->render();

    //return $content;

    //return PDF::loadView('print.pr',array('prop'=>$prop, 'trx'=>$trx, 'agent'=>$agent))
        //->stream('download.pdf');
});


Route::get('pr/dl/{id}',function($id){

    $trx = Transaction::find($id)->toArray();

    $prop = Property::find($trx['propObjectId'])->toArray();

    $agent = Agent::find($trx['agentId'])->toArray();

    //return View::make('print.brochure')->with('prop',$prop)->render();

    //$content = View::make('print.brochure')->with('prop',$prop)->render();

    //return $content;

    //return PDF::loadView('print.pr',array('prop'=>$prop, 'trx'=>$trx, 'agent'=>$agent))
    //    ->stream('download.pdf');

    return PDF::loadView('print.pr', array('prop'=>$prop, 'trx'=>$trx, 'agent'=>$agent))
            ->setOption('margin-top', '0mm')
            ->setOption('margin-left', '0mm')
            ->setOption('margin-right', '0mm')
            ->setOption('margin-bottom', '0mm')
            ->setOption('dpi',200)
            ->setPaper('A4')
            ->stream($prop['propertyId'].'_pr.pdf');


});

Route::post('pr/mail/{id}',function($id){

    $prop = Property::find($id)->toArray();

    //$content = View::make('print.brochure')->with('prop',$prop)->render();

    //$brochurepdf =  PDF::loadView('print.brochure',array('prop'=>$prop))->output();

    $brochurepdf = PDF::loadView('print.pr', array('prop'=>$prop, 'trx'=>$trx, 'agent'=>$agent))
        ->setOption('margin-top', '0mm')
        ->setOption('margin-left', '0mm')
        ->setOption('margin-right', '0mm')
        ->setOption('margin-bottom', '0mm')
        ->setOption('dpi',200)
        ->setPaper('A4')
        ->output();

    file_put_contents(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf', $brochurepdf);

    //$mailcontent = View::make('emails.brochure')->with('prop',$prop)->render();

    Mail::send('emails.brochure',$prop, function($message) use ($prop, &$prop){
        $to = Input::get('to');
        $tos = explode(',', $to);
        if(is_array($tos) && count($tos) > 1){
            foreach($tos as $to){
                $message->to($to, $to);
            }
        }else{
                $message->to($to, $to);
        }

        $message->subject('Investors Alliance - '.$prop['propertyId']);

        $message->cc('support@propinvestorsalliance.com');

        $message->attach(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf');
    });

    print json_encode(array('result'=>'OK'));

});


Route::get('pdf',function(){
    $content = "
    <page>
        <h1>Exemple d'utilisation</h1>
        <br>
        Ceci est un <b>exemple d'utilisation</b>
        de <a href='http://html2pdf.fr/'>HTML2PDF</a>.<br>
    </page>";

    $html2pdf = new HTML2PDF();
    $html2pdf->WriteHTML($content);
    $html2pdf->Output('exemple.pdf','D');
});

/*
Route::get('brochure/dl/{id}',function($id){

    $prop = Property::find($id)->toArray();

    //return View::make('print.brochure')->with('prop',$prop)->render();

    $content = View::make('print.brochure')->with('prop',$prop)->render();

    //return $content;

    return PDF::loadView('print.brochure',array('prop'=>$prop))
        ->stream('download.pdf');
});

Route::get('brochure',function(){
    View::make('print.brochure');
});
*/

Route::get('inc/{entity}',function($entity){

    $seq = new Sequence();
    print_r($seq->getNewId($entity));

});

Route::get('last/{entity}',function($entity){

    $seq = new Sequence();
    print( $seq->getLastId($entity) );

});

Route::get('init/{entity}/{initial}',function($entity,$initial){

    $seq = new Sequence();
    print_r( $seq->setInitialValue($entity,$initial));

});

Route::get('hashme/{mypass}',function($mypass){

    print Hash::make($mypass);
});

Route::get('xtest',function(){
    Excel::load('WEBSITE_INVESTORS_ALLIANCE.xlsx')->calculate()->dump();
});

Route::get('xcat',function(){
    print_r(Prefs::getCategory());
});

Route::get('media',function(){
    $media = Product::all();

    print $media->toJson();

});

Route::get('login',function(){
    return View::make('login');
});

Route::post('login',function(){

    // validate the info, create rules for the inputs
    $rules = array(
        'email'    => 'required|email',
        'password' => 'required|alphaNum|min:3'
    );

    // run the validation rules on the inputs from the form
    $validator = Validator::make(Input::all(), $rules);

    // if the validator fails, redirect back to the form
    if ($validator->fails()) {
        return Redirect::to('login')->withErrors($validator);
    } else {

        $userfield = Config::get('kickstart.user_field');
        $passwordfield = Config::get('kickstart.password_field');

        // find the user
        $user = User::where($userfield, '=', Input::get('email'))->first();


        // check if user exists
        if ($user) {
            // check if password is correct
            if (Hash::check(Input::get('password'), $user->{$passwordfield} )) {

                //print $user->{$passwordfield};
                //exit();
                // login the user
                Auth::login($user);

                return Redirect::to('/');

            } else {
                // validation not successful
                // send back to form with errors
                // send back to form with old input, but not the password
                Session::flash('loginError', 'Email and password mismatch');
                return Redirect::to('login')
                    ->withErrors($validator)
                    ->withInput(Input::except('password'));
            }

        } else {
            // user does not exist in database
            // return them to login with message
            Session::flash('loginError', 'This user does not exist.');
            return Redirect::to('login');
        }

    }

});

Route::get('logout',function(){
    Auth::logout();
    return Redirect::to('/');
});

/* Filters */

Route::filter('auth', function()
{

    if (Auth::guest()){
        Session::put('redirect',URL::full());
        return Redirect::to('login');
    }

    if($redirect = Session::get('redirect')){
        Session::forget('redirect');
        return Redirect::to($redirect);
    }

    //if (Auth::guest()) return Redirect::to('login');
});
