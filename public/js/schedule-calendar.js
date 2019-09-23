!function ($) {

    var CalendarApp = function () {
        this.$body = $("body")
        this.$calendar = $('#calendar'),
            this.$event = ('#calendar-events div.calendar-events'),
            this.$categoryForm = $('#add-new-event form'),
            this.$extEvents = $('#calendar-events'),
            this.$modal = $('#my-event'),
            this.$saveCategoryBtn = $('.save-category'),
            this.$calendarObj = null
    };


    /* on drop */
    CalendarApp.prototype.onDrop = function (eventObj, date) {
    var $this = this;
    // retrieve the dropped element's stored Event Object
    var originalEventObject = eventObj.data('eventObject');
    var $categoryClass = eventObj.attr('data-class');
    // we need to copy it, so that multiple events don't have a reference to the same object
    var copiedEventObject = $.extend({}, originalEventObject);
    // assign it the date that was reported
    copiedEventObject.start = date;
    if ($categoryClass)
        copiedEventObject['className'] = [$categoryClass];
    // render the event on the calendar
    $this.$calendar.fullCalendar('renderEvent', copiedEventObject, true);
    // is the "remove after drop" checkbox checked?
    if ($('#drop-remove').is(':checked')) {
        // if so, removgetEventDetaile the element from the "Draggable Events" list
        eventObj.remove();
    }
},

    /* on click on event */
    CalendarApp.prototype.onEventClick = function (calEvent, jsEvent, view) {
        getScheduleDetail(event, calEvent.id);
    },

    /* on select */
    CalendarApp.prototype.onSelect = function (start, end, allDay) {
        var $this = this;

        if(userCanAdd){
            addScheduleModal(start, end, allDay);
        }


        $this.$calendarObj.fullCalendar('unselect');
    },
    CalendarApp.prototype.enableDrag = function () {
        //init events
        $(this.$event).each(function () {
            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };
            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
        });
    }
    /* Initializing */
    CalendarApp.prototype.init = function () {
    //this.enableDrag();
    /*  Initialize the calendar  */
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var form = '';
    var today = new Date($.now());

    var defaultEvents = taskEvents;

    var $this = this;
    $this.$calendarObj = $this.$calendar.fullCalendar({
        slotDuration: '00:15:00', /* If we want to split day time each 15minutes */
        minTime: '08:00:00',
        maxTime: '19:00:00',
        defaultView: 'month',
        handleWindowResize: true,

        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        events: defaultEvents,
        editable: true,
        droppable: false, // this allows things to be dropped onto the calendar !!!
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        eventStartEditable: false,
        locale: 'en',
        displayEventTime: false,
        drop: function (date) {
            $this.onDrop($(this), date);
        },
        select: function (start, end, allDay) {
            $this.onSelect(start, end, allDay);
        },
        eventClick: function (calEvent, jsEvent, view) {
            $this.onEventClick(calEvent, jsEvent, view);
        },
        eventRender: function (event, element) {
            element.find('.fc-title').html(event.title);
        }


    });

    //on new event
    this.$saveCategoryBtn.on('click', function () {
        var categoryName = $this.$categoryForm.find("input[name='category-name']").val();
        var categoryColor = $this.$categoryForm.find("select[name='category-color']").val();
        if (categoryName !== null && categoryName.length != 0) {
            $this.$extEvents.append('<div class="calendar-events bg-' + categoryColor + '" data-class="bg-' + categoryColor + '" style="position: relative;"><i class="fa fa-move"></i>' + categoryName + '</div>')
            $this.enableDrag();
        }

    });
},
    /* Re Initializing */
    CalendarApp.prototype.reInit = function () {
        //this.enableDrag();
        /*  Initialize the calendar  */
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var form = '';
        var today = new Date($.now());

        var defaultEvents = taskEvents;

        var $this = this;
        $this.$calendarObj = null;
        $this.$calendar.fullCalendar('destroy');
        $this.$calendarObj = $this.$calendar.fullCalendar({
            slotDuration: '00:15:00', /* If we want to split day time each 15minutes */
            minTime: '08:00:00',
            maxTime: '19:00:00',
            defaultView: 'month',
            handleWindowResize: true,

            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: defaultEvents,
            editable: true,
            droppable: false, // this allows things to be dropped onto the calendar !!!
            eventLimit: true, // allow "more" link when too many events
            selectable: true,
            eventStartEditable: false,
            locale: 'en',
            displayEventTime: false,
            drop: function (date) {
                $this.onDrop($(this), date);
            },
            select: function (start, end, allDay) {
                $this.onSelect(start, end, allDay);
            },
            eventClick: function (calEvent, jsEvent, view) {
                $this.onEventClick(calEvent, jsEvent, view);
            },
            eventRender: function (event, element) {
                element.find('.fc-title').html(event.title);
            }


        });

        //on new event
        this.$saveCategoryBtn.on('click', function () {
            var categoryName = $this.$categoryForm.find("input[name='category-name']").val();
            var categoryColor = $this.$categoryForm.find("select[name='category-color']").val();
            if (categoryName !== null && categoryName.length != 0) {
                $this.$extEvents.append('<div class="calendar-events bg-' + categoryColor + '" data-class="bg-' + categoryColor + '" style="position: relative;"><i class="fa fa-move"></i>' + categoryName + '</div>')
                $this.enableDrag();
            }

        });
    },

    //init CalendarApp
    $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp

}(window.jQuery),

//initializing CalendarApp
    function ($) {
        "use strict";
        $.CalendarApp.init()
    }(window.jQuery);