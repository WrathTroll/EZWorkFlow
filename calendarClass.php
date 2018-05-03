<?php
//required php / JS
class event{
    public function __construct($t,$s) {$this->title=$t;$this->start=$s;}
    //public $id; //String/Integer. Optional Uniquely identifies the given event. 
    //Different instances of repeating events should all have the same id.
    private $title;//String. Required. The text on an event's element
    //public $allDay;//true or false. Optional.
    private $start;//The date/time an event begins. Required.
    private $end;//The exclusive date/time an event ends. Optional.
    //public $url;//String. Optional.A URL that will be visited when this event 
    //is clicked by the user. 
    //public $className;//String/Array. Optional.A CSS class (or array of classes) 
    //that will be attached to this event's element.
    //public $constraint;//an event ID, "businessHours", object. Optional.
    //Overrides the master eventConstraint option for this single event.
    //public $source;//Event Source Object. Automatically populated.
    //A reference to the event source that this event came from.
    public $backgroundColor;//Sets an event's background color just like the 
    //calendar-wide eventBackgroundColor option.
    //public $borderColor;//Sets an event's border color just like the the 
    //calendar-wide eventBorderColor option.
    //public $textColor;//Sets an event's text color just like the 
    //calendar-wide eventTextColor option.
    //public function setAllDay($c){$this->allDay=$c;}
    //public function getAllDay(){return $this->allDay;}
    public function setBackGroundColor($bc){$this->backgroundColor=$bc;}
    public function getBackGroundColor($bc){return $this->backgroundColor;}
    public function setTitle($t){$this->title=$t;}
    public function getTitle(){return $this->title;}
    public function setStart($st){$this->start=$st;}
    public function getStart(){return $this->start;}    
    public function setEnd($e){$this->end=$e;}
    public function getEnd(){return $this->end;}
}

class eventList{
    private $eventList = array();
    public function addEvent($e){$this->eventList[]=$e;}
    public function getDataEvents(){
        global $connection;
        $query = "select * from calendar ORDER BY calendarStart";
        try{
            $sql = $connection->prepare($query);
            $sql->execute();
            $data = $sql->fetchAll();
        foreach($data as $row)
        {
            if(empty($row))
            {
                echo 'empty row';
                return null;
            }
            $tempEvent = new event(
            $row['calendarTitle'],$row['calendarStart']);
            if($row['calendarEnd']>''){$tempEvent->setEnd($row['calendarEnd']);}
            $this->addEvent($tempEvent);
        }
            }catch(PDOException $pde)
            {echo "unable to complete select :".$pde->getMessage();}
        $this->getEvents();
    }
    public function getEvents(){
        foreach($this->eventList as $event){            
        returnEvent($event->getTitle(),$event->getStart(),
                $event->getBackGroundColor());
    }}
}

function returnEvent($t,$s,$bc){
    if(preg_match('/\bsick\b/i',$t)){$color='#CC0A00';};
    if(preg_match('/\bleave\b/i',$t)){$color='green';};    
    if(preg_match('/\bgiven\b/i',$t)){$color='#00878e';};        
    $s0 = "{title:'".$t."',start:'".$s."',color:'".$color."'},";
    
    $s0.= "{start:'".$s."',end:'".$s."',rendering: 'background',color: 'lightgrey'},";
    echo htmlspecialchars($s0);
    //echo htmlspecialchars($s1);
}

/* notes on fullcalendar 
 * 
 * =============================================================================
 * eventClick
* ==============================================================================
 * 
Triggered when the user clicks an event.

function( event, jsEvent, view ) { }

event is an Event Object that holds the event's information 
(date, title, etc).

jsEvent holds the native JavaScript event with low-level information 
such as click coordinates.

view holds the current View Object.

Within the callback function, this is set to the event's <div> element.

Here is an example demonstrating all these variables:

$('#calendar').fullCalendar({
    eventClick: function(calEvent, jsEvent, view) {

        alert('Event: ' + calEvent.title);
        alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
        alert('View: ' + view.name);

        // change the border color just for fun
        $(this).css('border-color', 'red');

    }
});

 * =============================================================================
 * eventRender
 * =============================================================================
 * 

Triggered while an event is being rendered.

function( event, element, view ) { }

event is the Event Object that is attempting to be rendered.

element is a newly created jQuery element that will be used for rendering. 
It has already been populated with the correct time/title text.

The eventRender callback function can modify element. For example, it can change 
its appearance via jQuery's .css().

The function can also return a brand new element that will be used for rendering 
instead. For all-day background events, you must be sure to return a <td>.

The function can also return false to completely cancel the rendering of the event.

eventRender is a great way to attach other jQuery plugins to event elements, 
such as a qTip tooltip effect:

$('#calendar').fullCalendar({
    events: [
        {
            title: 'My Event',
            start: '2010-01-01',
            description: 'This is a cool event'
        }
        // more events here
    ],
    eventRender: function(event, element) {
        element.qtip({
            content: event.description
        });
    }
});

 */