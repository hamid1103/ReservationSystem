function valDate(date) {

    // Date format: YYYY-MM-DD
    var datePattern = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;

    // Check if the date string format is a match
    var matchArray = date.match(datePattern);
    if (matchArray == null) {
        return false;
    }

    // Remove any non digit characters
    var dateString = date.replace(/\D/g, '');

    // Parse integer values from the date string
    var year = parseInt(dateString.substr(0, 4));
    var month = parseInt(dateString.substr(4, 2));
    var day = parseInt(dateString.substr(6, 2));

    // Define the number of days per month
    var daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    // Leap years
    if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0)) {
        daysInMonth[1] = 29;
    }

    if (month < 1 || month > 12 || day < 1 || day > daysInMonth[month - 1]) {
        return false;
    }
    return true;
}

var baseurl = './getTimes.php?'
var urlads = ''

const frame = document.getElementById('phpFrameHolder')

document.onload = function () {
setplaceholder();
}

function setplaceholder(){
    var ifrm = document.createElement("iframe");
    ifrm.setAttribute("src", './placeholdertimes.php');
    ifrm.style.width = "640px";
    ifrm.style.height = "480px";
    frame.appendChild(ifrm)
}

function showtime(date){
    var datetext = date.toString()
    while (frame.firstChild){
        frame.removeChild(frame.firstChild);
    }

    if(valDate(datetext)){
        urlads = 'date=' + datetext;
    }else {
        urlads = 'error=' + datetext;
    }
    //TODO: add more safety checks, maybe a different pho for if date is invalid
    var ifrm = document.createElement("iframe");
    ifrm.setAttribute("src", baseurl + urlads);
    ifrm.style.width = "640px";
    ifrm.style.height = "480px";
    frame.appendChild(ifrm)
}