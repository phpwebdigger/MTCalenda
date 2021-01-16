<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Http\Request;

use App\Models\MTfullcalendar;
use Validator,Redirect,Response;
use DB;
//phpwebdigger
class MTCalendarController extends Controller
{
    protected $client;

    public function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig('client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);

        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);
        $this->client = $client;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';

            $results = $service->events->listEvents($calendarId);
            $result = $results->getItems();
                  
                  

            return $result;




        } else {
            return redirect()->route('oauthCallback');
        }

    }

    public function oauth()
    {
        session_start();

        $rurl = action('MTCalendarController@oauth');
        $this->client->setRedirectUri($rurl);
        if (!isset($_GET['code'])) {
            $auth_url = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
            return redirect($filtered_url);
        } else {
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            return redirect()->route('cal.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('calendar.createEvent');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        session_start();
        $startDateTime = $request->start_date;
        $endDateTime = $request->end_date;


        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';
            // $event = new Google_Service_Calendar_Event([
            //     'summary' => $request->title,
            //     'description' => $request->description,
            //     'start' => ['dateTime' => $startDateTime],
            //     'end' => ['dateTime' => $endDateTime],
            //     'reminders' => ['useDefault' => true],
            // ]);

           $event = new Google_Service_Calendar_Event(array(
  'summary' => $request->title,
  'description' => $request->description,
  'start' => array(
    'dateTime' => Carbon::parse($startDateTime),
     'timeZone'=> 'UTC'
   ),
  'end' => array(
    'dateTime' => Carbon::parse($endDateTime),
        'timeZone'=> 'UTC'

  ),
  'recurrence' => array(
    
  ),
  'attendees' => array(
    array('email' => 'abck5752@gmail.com')
   
  ),
  'reminders' => array(
    'useDefault' => FALSE,
    'overrides' => array(
      array('method' => 'email', 'minutes' => 24 * 60),
      array('method' => 'popup', 'minutes' => 10),
    ),
  ),
));

             $results = $service->events->insert($calendarId, $event);


            if (!$results) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }
            return response()->json(['status' => 'success', 'message' => 'Event Created']);
        } else {
            return redirect()->route('oauthCallback');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $eventId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show($eventId)
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);

            $service = new Google_Service_Calendar($this->client);
            $event = $service->events->get('primary', $eventId);

            if (!$event) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }
            return response()->json(['status' => 'success', 'data' => $event]);

        } else {
            return redirect()->route('oauthCallback');
        }
    }


    public function get_cal($result){









    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $eventId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, $eventId)
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $startDateTime = Carbon::parse($request->start_date)->toRfc3339String();

            $eventDuration = 30; //minutes

            if ($request->has('end_date')) {
                $endDateTime = Carbon::parse($request->end_date)->toRfc3339String();

            } else {
                $endDateTime = Carbon::parse($request->start_date)->addMinutes($eventDuration)->toRfc3339String();
            }

            // retrieve the event from the API.
            $event = $service->events->get('primary', $eventId);

            $event->setSummary($request->title);

            $event->setDescription($request->description);

            //start time
            $start = new Google_Service_Calendar_EventDateTime();
            $start->setDateTime($startDateTime);
            $event->setStart($start);

            //end time
            $end = new Google_Service_Calendar_EventDateTime();
            $end->setDateTime($endDateTime);
            $event->setEnd($end);

            $updatedEvent = $service->events->update('primary', $event->getId(), $event);


            if (!$updatedEvent) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }
            return response()->json(['status' => 'success', 'data' => $updatedEvent]);

        } else {
            return redirect()->route('oauthCallback');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $eventId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy($eventId)
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $service->events->delete('primary', $eventId);

        } else {
            return redirect()->route('oauthCallback');
        }
    }


    public function getapihoursdata(Request $request){



            
            $data = MTfullcalendar::orderBy('start','DESC')->orderBy(DB::raw('HOUR(start)'))->paginate(3);
            
             $datas = '';
              // $datas.='<li><strong>Hours wise<strong></li>';
        if ($request->ajax()) {

            foreach ($data as $post) {
                $datas.='<li> <strong> Hour= </strong>'. date("H",strtotime($post->start)).'|  Summary = '.$post->summary.' <strong> | Start date = '.$post->start.'</strong> | End date =  '.$post->end.'</li>';
            }
            return $datas;
        }
        return view('calendar.indedata');
            
             // return view('calendar.indedata')->withPosts($data);
        

    }


        public function getapidaysdata(Request $request){

            
            $data = MTfullcalendar::orderBy('start','DESC')->orderBy(DB::raw('DATE(start)'))->paginate(3);
            
             $datas = '';
        if ($request->ajax()) {
         
            foreach ($data as $post) {
                $datas.='<li> <strong> Days= </strong>'. date("D",strtotime($post->start)).'|  Summary = '.$post->summary.' <strong> | Start date = '.$post->start.'</strong> | End date =  '.$post->end.'</li>';
            }
            return $datas;
        }
        return view('calendar.indedata');
            
             // return view('calendar.indedata')->withPosts($data);
        

    }
    


}
