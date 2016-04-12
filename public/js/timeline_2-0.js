/**
 * Created by s.kievit on 27-11-2015.
 */

var custom_timeline = {

    init: function(story_href, timeline_stories_href, canvasClass){

        custom_timeline.canvas_class = $("." + canvasClass);
        custom_timeline.canvas_class.append(
            custom_timeline.maandlijst = $('<div>').addClass('month_timeline'),
            custom_timeline.daglijst = $('<div>').addClass('day_timeline'),
            custom_timeline.itemlijst =$('<div>').addClass('item_timeline')
        );
        custom_timeline.story_href = story_href;
        custom_timeline.stories_per_day_href = timeline_stories_href;
        custom_timeline.createTimeline();
    },
    dates : {
        0 : {
            'year' : 2015,
            'name' : 'September',
            'val'  : 9
        },
        1 : {
            'year' : 2015,
            'name' : 'Oktober',
            'val'  : 10
        },
        2 : {
            'year' : 2015,
            'name' : 'November',
            'val'  : 11
        },
        3 : {
            'year' : 2015,
            'name' : 'December',
            'val'  : 12
        },
        4 : {
            'year' : 2016,
            'name' : 'Januari',
            'val'  : 1
        }
    },
    maandlijst : $('.month_timeline'),
    daglijst   : $('.day_timeline'),
    itemlijst  : $('.item_timeline'),
    items_per_day : ["item 1", "item 2", "item 3","item 4", "item 5", "item 6","item 7", "item 8", "item 9"],
    story_href : '',
    stories_per_day_href : '',
    canvas_class : '',
    createTimeline: function ()
    {
        var storyContainer = $('.selected-story');

        for (var key in custom_timeline.dates) {

            custom_timeline.canvas_class.css('margin-bottom', '100px');

            custom_timeline.maandlijst.append($('<div>').addClass('month').text(custom_timeline.dates[key]['name']).prop('maand-data', key).click(function () {

                //clear day and item line
                custom_timeline.daglijst.empty();
                custom_timeline.itemlijst.empty();
                (storyContainer.attr('class').toLowerCase().indexOf('is-active') >= 0) ? storyContainer.removeClass('is-active') : '';



                //deactivate all items and set clicked item active
                custom_timeline.maandlijst.find('div').removeClass('active');
                $(this).addClass('active');

                //get value of month
                var monthValue =  $(this).prop('maand-data');

                //get dates
                var amountOfDays = custom_timeline.daysInMonth(custom_timeline.dates[monthValue]['val'], custom_timeline.dates[monthValue]['year']);

                custom_timeline.canvas_class.css('margin-bottom', '140px');

                //fill day timeline with the right amount of days
                for (b = 1; b <= amountOfDays; b++) {

                    custom_timeline.daglijst.append($('<div>').addClass('day').text(b).css('width',     'calc('  +  (( 100 / amountOfDays ) + '%' )   +   ' - 1px )').prop('dag-data', b).click(function () {

                        //leeg itemlijst
                        custom_timeline.itemlijst.empty();
                        (storyContainer.attr('class').toLowerCase().indexOf('is-active') >= 0) ? storyContainer.removeClass('is-active') : '';

                        //deactivate all items and set clicked item active
                        custom_timeline.daglijst.find('div').removeClass('active');
                        $(this).addClass('active');

                        //get value
                        var dayValue = $(this).prop('dag-data');

                        //now get projects and append them
                        console.log('get projects - ' + 'day: ' + dayValue + ' month: ' + custom_timeline.dates[monthValue]['name'] );

                        custom_timeline.canvas_class.css('margin-bottom', '280px');

                        var cur_month = custom_timeline.maandlijst.find('.active').prop('maand-data');
                        var cur_day = custom_timeline.daglijst.find('.active').prop('dag-data');
                        custom_timeline.getStories(custom_timeline.dates[cur_month]['val'], cur_day);

                    }));
                }



            }));
        }
    },
    daysInMonth : function (month, year)
    {
        //retuns the dates for this specific month
        return new Date(year, month, 0).getDate();
    },
    getStories : function (month, day) {
        //ajax call too backend
        var year = '';

        if(month <= 12 )
        {
            year = 2015;
        }
        else {
            year = 2016;
        }

        $.ajax({
            url: custom_timeline.stories_per_day_href,
            type: 'get',
            data: {day: day, month: month, year: year},
            success: function(response)
            {
                custom_timeline.addStoriesToTimeline($.parseJSON(response));
            },
            error: function(xhr)
            {
                console.log(xhr);
            }
        });

    },
    getStory : function(id){

        console.log('getting data')

        $.ajax({
            url: custom_timeline.story_href,
            type: 'get',
            data: {id: id, ajax:true},
            success: function(response){
                custom_timeline.updateStoryView(response);
            },
            error: function(error)
            {
                console.log(error);
            }
        });





    },
    addStoriesToTimeline : function(ajaxResponse)
    {
        custom_timeline.itemlijst.empty();

        if(ajaxResponse.length > 0) {

            for (c = 0; c < ajaxResponse.length; c++) {

                custom_timeline.itemlijst.append($('<div>').addClass('day_item').prop('story-id', ajaxResponse[c].id).text(ajaxResponse[c].title.substring(0, 25) + "...").click(function () {
                    var story_id = $(this).prop('story-id');
                    window.location.href = window.location.origin + '/story/index/id/' + story_id;
                }));
            }
        }
        else {
            custom_timeline.itemlijst.append($('<div>').addClass('day_item day_item_message').text('Geen data gevonden.'));
        }
    },
    updateStoryView : function(data)
    {
        $story = ($.parseJSON(data));

        var storyContainer = $('.selected-story');
        var title = $('.story_title');
        var time_stamp = $('.story_time_stamp');
        var intro = $('.story_introduction');
        var chall = $('.story_challenge');
        var conlc = $('.story_conclusie');
        var compList = $('.story_competenties');
        var skillList = $('.story_skills');


        intro.text($story.story[0]['introduction']);
        chall.text($story.story[0]['challenges']);
        conlc.text($story.story[0]['conclusion']);
        time_stamp.text($story.story[0]['time_stamp']);
        title.text($story.story[0]['title']);

        skillList.empty();
        compList.empty();

        for(i = 0; i < $story.skill.length; i++)
        {
            skillList.append($('<li>').text($story.skill[i]['name']));
        }

        for(a = 0; a < $story.comp.length; a++)
        {
            compList.append($('<li>').text($story.comp[a]['name']).append($('<span>').text($story.comp[a]['type']).addClass('blue type') )  );

        }

        (storyContainer.attr('class').toLowerCase().indexOf('is-active') >= 0) ? storyContainer.removeClass('is-active') : storyContainer.addClass('is-active');

    }


};