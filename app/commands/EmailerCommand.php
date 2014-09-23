<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EmailerCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'emailer:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Background runner to send batch email.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $todaystart = date('Y-m-d 00:00:00',time());
        $todayend = date('Y-m-d 23:59:59',time());

        $recipients = Mailqueue::where('sendDate', '>=', new Carbon($todaystart) )
                            ->where('sendDate', '<=', new Carbon($todayend) )
                            ->where('status','unsent')
                            ->take(10)
                            ->get()->toArray();

        $template = null;
        $templateId = '';

        //Mail::pretend();

        foreach($recipients as $rec ){
            if($rec['template'] != $templateId || is_null($template) ){
                $template = Template::find($rec['template']);
                $props = $template->properties;

                $props = explode(',',$props);
                if(count($props) > 0){
                    $property = Property::whereIn('propertyId',$props)->get()->toArray();
                }else{
                    $property = array();
                }
                $templateId = $rec['template'];
            }


            $recinfo = Buyer::where('email',$rec['email'])->first()->toArray();

            $content = DbView::make($template)->field('body')->with('rec', $recinfo)->with('prop',$property)->render();

            //print $content;

            Mail::send('emails.blank',array('body'=>$content), function($message) use ($recinfo, $rec){
                $to = $recinfo['email'];

                $fullname = $recinfo['firstname'].' '.$recinfo['lastname'];

                $message->to($to, $fullname);

                $message->subject('Investors Alliance - E newsletter');

                //print_r($rec);

                if($rec['sentFromName'] == ''){
                    $message->from('info@propinvestorsalliance.com','Djonny Suwanto');
                    $message->replyTo('info@propinvestorsalliance.com','Djonny Suwanto');
                }else{
                    $message->from($rec['sentFromEmail'],$rec['sentFromName']);
                    $message->replyTo($rec['sentFromEmail'],$rec['sentFromName']);
                }



                $message->cc('support@propinvestorsalliance.com');

                Mailqueue::where('_id',$rec['_id'])->update(array('status'=>'sent'));

                //$message->attach(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf');
            });


        }
		//
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
