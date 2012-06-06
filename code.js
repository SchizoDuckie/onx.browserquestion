/**
 *
 *  I tried to hack together a method to abuse the browser to ask the user a question.
 *  It works, even though there's no settimeout to create a polling connection.
 *  (I delay the response by a second on the serverside as soon as a client starts polling)
 * 
 *  I setup a simple PHP backend with a memcached server in a VM, so please don't kill my bandwidth or
 *  think you can use this to run your whole company, be courteous and setup your own :-).
 *
 * This demo just asks a custom question via the browser, and shows a notification based on the result, 
 * but the possibilities are endless ofcourse
 * 
 *  Contact: schizoduckie@gmail.com 
 */



device.screen.on("unlock", function(){
    
    askAQuestion("Hello... Is it me you're looking for?", function() {
        console.log("Yippey! In success YES callback!");
         var notification = device.notifications.createNotification('Helloooo');
         notification.content = 'You found me!!';
         notification.on('click', function() { console.log('User clicked YES'); });
         notification.show();
    }, function () {
        console.log("Yippey! In success NO callback!");
        var notification = device.notifications.createNotification('Helloooo');
        notification.content = 'Y U NO LOOK FOR ME?!!';
        notification.on('click', function() { console.log('User clicked NO'); });
        notification.show();
    } );
    console.log("Question test initiated.");
});




function askAQuestion(Q, yesCallback, noCallback) {
   device.browser.showUrl('http://schizoduckie.mine.nu/?q='+encodeURIComponent(Q));
   getAnswer(Q, yesCallback,noCallback);
}

var maxInterval = 10;
var currInterval = 1;

function getAnswer(Q, yesCallback, noCallback) {
    console.log("PollInterval executing, current: "+currInterval);
    
     device.ajax({
      url: 'http://schizoduckie.mine.nu/?action=get',
      type: 'POST',
      data: 'question='+encodeURIComponent(Q),
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
      }
    },
    function onSuccess(body, textStatus, response){
        if(body.length === 0 || body.length > 3) {
            console.log("getAnswer:  notfoundyet, next.");
            console.log(body);
            if(currInterval <= maxInterval) {
                currInterval += 1;
                getAnswer(Q, yesCallback, noCallback);
            } else {
                console.log("Max "+maxInterval+" tries done without a response. stopping.");
            } 
        }
        else {
            if(body == "no") { noCallback(); }
            if(body == "yes") { yesCallback(); }
            console.log("got answer! ", body);
        }
    },
    function onError(textStatus, response){
      var error = {};
      error.message = textStatus;
      error.statusCode = response.status;
      console.error('error: ',error);
    });
    
   
}