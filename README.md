onx.browserquestion
===================

Creates a callback via the browser to power user interaction for Microsoft Onx (www.onx.ms)

I tried to hack together a method to abuse the browser to ask the user a question.
It works, even though there's no settimeout to create a polling connection.
(I delay the response by a second on the serverside as soon as a client starts polling)
 
I setup a simple PHP backend with a memcached server in a VM, so please don't kill my bandwidth or
think you can use this to run your company on.

 This demo just asks a custom question via the browser, and shows a notification based on the result, 
 ut the possibilities are endless ofcourse
 
 
