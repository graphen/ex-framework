
<!-- 
// Today is...
// By Peter Stańczyk <idsoft@boss.staszic.waw.pl>
// For Object Programming Center

Months = new Array(12)
Months[0] = "January "
Months[1] = "February "
Months[2] = "March "
Months[3] = "April "
Months[4] = "May "
Months[5] = "June "
Months[6] = "July "
Months[7] = "August "
Months[8] = "September "
Months[9] = "October "
Months[10] = "November "
Months[11] = "December "

Days = new Array(7)
Days[0] = "Sunday "
Days[1] = "Monday "
Days[2] = "Tuesday "
Days[3] = "Wednesday "
Days[4] = "THusday "
Days[5] = "Friday "
Days[6] = "Saturday "

function getDateStr(){
    var Today = new Date()
    var WeekDay = Today.getDay()
    var Month = Today.getMonth()
    var Day = Today.getDate()
    var Year = Today.getYear()

    //if(Year <= 99)
        Year += 1900

    return Days[WeekDay] + "," + " " + Day + " " + Months[Month] + ", " + Year
}

//-->


