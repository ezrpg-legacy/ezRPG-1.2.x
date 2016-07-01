/**
 * Created by Tim on 6/30/2016.
 */
function updateClock() {
    var weekday = new Array(7);
    weekday[0]=  "Sunday";
    weekday[1] = "Monday";
    weekday[2] = "Tuesday";
    weekday[3] = "Wednesday";
    weekday[4] = "Thursday";
    weekday[5] = "Friday";
    weekday[6] = "Saturday";

    var now = new Date(); // Get the date
    hrs = ('0'+now.getHours()).slice(-2);
    mins = ('0'+now.getMinutes()).slice(-2);
    secs = ('0'+now.getSeconds()).slice(-2);
    time = hrs + ':' + mins + ':' + secs; // Get the time

    var dayOfWeek = weekday[now.getDay()];
    // set the content of the element with the ID time to the formatted string
    document.getElementById('time').innerHTML = [dayOfWeek, time].join(' ');

    // call this function again in 1000ms
    setTimeout(updateClock, 1000);
}
updateClock();