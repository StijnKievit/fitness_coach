/**
 * Created by s.kievit on 23-10-2015.
 */

$(document).ready(function(){

    init();
    $(document).change(function(){
       update();
    });
});


function init(){
    var object = Object.create(Timeline);
    object.init();
    console.log(object.getTimelineData());
    object.createTimeline(object.getTimelineData());
    object.events();

}

function update(){

}