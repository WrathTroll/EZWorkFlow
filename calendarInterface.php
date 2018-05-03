
<?php 
require 'calendarClass.php';

// function to show the calendar 
// required php js
function showCalendarPhp(){?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<link href="js/fullcalendar-3.8.0/fullcalendar.min.css" rel="stylesheet" />
<link href="js/fullcalendar-3.8.0/fullcalendar.print.min.css" rel="stylesheet" media="print" />
<script src="js/fullcalendar-3.8.0/lib/moment.min.js"></script>
<script src="js/fullcalendar-3.8.0/lib/jquery.min.js"></script>
<script src="js/fullcalendar-3.8.0/fullcalendar.min.js"></script>
<script src="js/fullcalendar-3.8.0/locale-all.js"></script>
<script src="js/myScripts.js"></script>
<script>

        
  $(document).ready(function() {
    var initialLocaleCode = 'en';

    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,listMonth'
      },
      defaultDate: getTodayDateYYYYMMDD(),//'2017-12-12',//myscript.js
      locale: initialLocaleCode,      
      buttonIcons: false, // show the prev/next text      
      weekNumbers: true,
      businessHours: true,
      navLinks: true, // can click day/week names to navigate views
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      events: [        
    <?php
    $el = new eventList();
    $el->getDataEvents();
   /* $el = new eventList();
    $e1 = new event('Foo Sick','2018-01-29');
    $el->addEvent($e1);
    $e2 = new event('Bar Leave','2018-02-01');
    $el->addEvent($e2);    
    $e3 = new event('FooBar Leave','2018-01-31');
    $el->addEvent($e3);        
    $e4 = new event('Bar Leave','2018-01-25');
    $el->addEvent($e4);       
    $el->getEvents();
    */?>        
    /*    
        {
          title: 'Foon Leave',
          start: '2018-01-31T14:00:00',
          end: '2018-01-31T16:00:00',
          color: 'green'
        },        
        {
          title: 'Long Event',
          start: '2017-12-07',
          end: '2017-12-10'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2017-12-09T16:00:00'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2017-12-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2017-12-11',
          end: '2017-12-13'
        },
        {
          title: 'Lunch',
          start: '2017-12-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2017-12-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2017-12-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2017-12-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2017-12-13T07:00:00'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2017-12-28'
        }*/
      ]
    });

    // build the locale selector's options
    $.each($.fullCalendar.locales, function(localeCode) {
      $('#locale-selector').append(
        $('<option/>')
          .attr('value', localeCode)
          .prop('selected', localeCode == initialLocaleCode)
          .text(localeCode)
      );
    });

    // when the selected option changes, dynamically change the calendar option
    $('#locale-selector').on('change', function() {
      if (this.value) {
        $('#calendar').fullCalendar('option', 'locale', this.value);
      }
    });
  });

</script>
<style>

  body {
    margin: 0;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
  }

  #top {
    background: #eee;
    border-bottom: 1px solid #ddd;
    padding: 0 10px;
    line-height: 40px;
    font-size: 12px;
  }

  #calendar {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 10px;
  }

</style>
</head>
<body>

  <div id='top'>

    Locales:
    <select id='locale-selector'></select>

  </div>

  <div id='calendar'></div>

</body>
</html>

<?php }