/**
 * Created by s.kievit on 23-10-2015.
 */
var Timeline = {

    storyURL: '',
    init: function(){
        $('.timeline_wrapper').append($('<div>').addClass('eventDisplay'), $('<div>').addClass('line'));
    },
    getTimelineData: function (url)
    {
        return  [["test1", 120], ["test2", 340], ["test3", 600],["test4", 920], ["test5", 1340], ["test6", 2100],["test7", 4000], ["test8", 6540], ["test9", 8600]];
    },
    createTimeline: function(data)
    {
        var data = data;

        var lineWidth = $('.line').width();

        var maxLineValue = Math.max.apply(Math, data.map(function(i) {
            return i.time_stamp;
        }));

        console.log(maxLineValue);

        console.log('this is a test' + this.GetStoryURL);

        var calcValue = lineWidth / ( maxLineValue + 20);

        data.forEach(function(item){

            $('.line').append($('<div>').addClass('item').css('position', 'absolute').css('left', (item.time_stamp * calcValue)).append($('<span>').text(item.title)).attr('value-id', item.id ).click(function(e){

                console.log($(this).attr('value-id'));

                var clickedItemClasses = ($(this).attr("class"));
                var container = $('.item_desc');

                if(clickedItemClasses.indexOf("active_item") != -1)
                {
                    $(this).removeClass('active_item');
                    container.slideToggle();
                }
                else{

                    console.log($('.line').find('.active_item').length);
                    if($('.line').find('.active_item').length != 0 )
                    {
                        console.log('replace content');
                        console.log()
                    }
                    else{
                        container.slideToggle();
                    }

                    $('.line>div').removeClass('active_item');
                    $(this).addClass('active_item');

                }

                Timeline.loadStory($(this).attr('value-id'));
            }));
        });

        $('.line').draggable({axis: "x",
            stop: function(event, ui){

                console.log(ui.position.left);
                if(ui.position.left>0)
                {
                    $('.line').animate({"left":"0px"}, 600);
                }
                else if(ui.position.left < ($(window).width() - $('.line').width()) ){

                    var value = ( $(window).width() - $('.line').width());
                    console.log(value);
                    console.log('this should be right!');
                    $('.line').animate({"left": value }, 600);
                }
            }
        });
    },
    events: function(){

        $(window).keydown(function(event){

            var timeline = $('.line');

            if(event.which == 37){
                console.log('left');
                console.log("left value of element" + timeline.position().left);
                if(timeline.position().left >= 0)
                {
                    console.log('go back bitch!');
                    timeline.animate({"left":"0px"}, 600);
                }
                else{
                    timeline.animate({
                        left: "+=20%"
                    }, 600, function(){

                    });
                }
            }
            else if(event.which == 39)
            {
                console.log('right');
                console.log(($(window).width() - timeline.width()));
                console.log("left value of element" + timeline.position().left);
                if(timeline.position().left <= ($(window).width() - timeline.width()))
                {
                    console.log('youve gone to far');
                    var value = ( $(window).width() - timeline.width());
                    timeline.animate({"left": value }, 600);
                }
                else{
                    timeline.animate({
                        left: "-=20%"
                    }, 600, function(){

                    });
                }
            }

            console.log(event.which);
        });
    },
    loadStory: function(id){
        if(this.GetStoryURL != '')
        {
            $.ajax({
                url: Timeline.storyURL,
                type: "get",
                data: {story_id: id},
                success: function(response)
                {
                    Timeline.updateStory(response);
                },
                error: function(xhr){

                }

            });
        }
    },
    setStoryURL: function(value){
        Timeline.storyURL = value;
    },
    updateStory: function(data){
        var json = (JSON.parse(data));

        var comp =  json['competenties'];
        var content = json['content'][0];
        var skills = json['skills'];

        console.log(comp);
        console.log(content);
        console.log(skills);

        var skillList = $('.skills');
        var compList = $('.competenties');
        skillList.empty();
        compList.empty();

        //change skills
        for(x = 0; x < skills.length; x++){
            skillList.append("<li>"+ skills[x].name + "</li>");
        }

        //change competenties
        for(y = 0; y < comp.length; y++){
            compList.append("<li>"+ comp[y].name + "<span>" + comp[y].type + "</span>"  +  "</li>");
        }

        if(comp.length < 1){
            compList.append("<li>Geen competenties</li>");
        }
        if(skills.length < 1)
        {
            skillList.append("<li>Geen skills</li>");
        }

        var story_container = $('.item_desc');

        //change content of container
        story_container.find('.title').text(content['title']);
        story_container.find('.time_stamp').text(content['time_stamp'].replace("00:00:00","") );
        story_container.find('.introduction').text(content['introduction']);
        story_container.find('.challenge').text(content['challenges']);
        story_container.find('.conclusie').text(content['conclusion']);


    }


};