/* Taken ports(taken from local xampp server)
Apache server: 80, 443
MySql server: 3306
*/

const https = require('https');
const axios = require('axios');
const FormData = require('form-data');
//stuff from MS graph
/*const readline = require('readline-sync');
const graphHelper = require('./graphHelper');*/
const dontenv = require('dotenv').config()


const clientId = process.env.CLIENT_ID;
const tenantId = process.env.TENANT_ID;
const clientSecret = process.env.CLIENT_SECRET;
const scopes = process.env.GRAPH_USER_SCOPES;

const request = require('request');
const qs = require('qs');

// Express rest api stuff
const fs = require("fs/promises");
const ld = require("lodash");
const {v4: uuid, stringify} = require("uuid");
const cors = require("cors");
const express = require("express");
const mysql = require("mysql");
const {response} = require("express");
const {value} = require("lodash/seq");

const app = express();
app.use(express.json());

app.listen(3000, () => {
    console.log("Server running on port 3000");
    console.log("Please head to /msAuth to get a token")
});

//what an auth req should look like
/*https://login.microsoftonline.com/common/oauth2/v2.0/authorize
?client_id=6731de76-14a6-49ae-97bc-6eba6914391e
&response_type=code
&redirect_uri=http%3A%2F%2Flocalhost%2Fmyapp%2F
&response_mode=query
&scope=offline_access%20user.read%20mail.read
&state=12345*/
app.get("/msAuth", async(req, res) => {
    res.redirect("https://login.microsoftonline.com/common/oauth2/v2.0/authorize?" +
        "client_id=" + clientId
        +
        "&response_type=code"
        +
        "&redirect_uri=http%3A%2F%2Flocalhost%3A3000%2Ftoken"
        +
        "&response_mode=query"
        +
        "&scope=offline_access%20user.read%20mail.send%20Calendars.ReadWrite"
        +
        "&state=12345"
    )

})

//successfull response
/*GET https://localhost/myapp/?
code=M0ab92efe-b6fd-df08-87dc-2c6500a7f84d
&state=12345*/

//Error response
/*http://localhost:3000/token1?error=invalid_request
&error_description=Proof%20Key%20for%20Code%20Exchange%20is%20required%20for%20cross-origin%20authorization%20code%20redemption.
&state=12345*/


app.get("/token", async (req, res) =>
{
    //error handeling
    let error = req.query.error;
    let errdesc = req.query.error_description;
    if(error == undefined){

    }else{
       return res.send(errdesc + "\n" + clientId)
    }

    let authCode = req.query.code;
    let state = req.query.state;
    //extra check
    if(authCode == undefined){
        return res.send("Something went wrong. Authcode undefined")
    }else{
        //Successfully got AuthCode
        //res.send(authCode);
        res.redirect("/reqToken/"+authCode)
    }
})

let tokenData = ''
let tokenSet = false;
function setTokenData(data){
    tokenData = data;
    tokenSet = true
    //start repeated loop for getting new access token from refresh token
    setInterval(async function (){
        console.log("Refreshing token")
        console.log("refresh token: " + tokenData.refresh_token)
        console.log("access token expires in: " + tokenData.expires_in)
            const postData = {
                client_id: clientId,
                refresh_token: tokenData.refresh_token,
                client_secret: clientSecret,
                grant_type: 'refresh_token',
            }

            axios.defaults.headers.post['Content-Type'] =
                'application/x-www-form-urlencoded';

            axios.post('https://login.microsoftonline.com/common/oauth2/v2.0/token', qs.stringify(postData))
                .then( response => {
                    setTokenData(response.data);
                })
                .catch(function (error) {
                    console.log(error);
                })

    },
        (tokenData.expires_in - 100)*1000)
}

app.get("/reqToken/:authCode", async (req, res) =>{
    //Set to form data cuz it needs to be form data for some reason
    console.log(req.params.authCode)
    const postData = {
        client_id: clientId,
        //scope: 'https://graph.microsoft.com/.default',
        scope: 'https://graph.microsoft.com/user.read https://graph.microsoft.com/mail.send https://graph.microsoft.com/calendars.readwrite',
        code: req.params.authCode,
        client_secret: clientSecret,
        grant_type: 'authorization_code',
        redirect_uri: 'http://localhost:3000/token' //http%3A%2F%2Flocalhost%3A3000%2FgetToken
    }

    axios.defaults.headers.post['Content-Type'] =
        'application/x-www-form-urlencoded';

    axios.post('https://login.microsoftonline.com/common/oauth2/v2.0/token', qs.stringify(postData))
        .then( response => {
            setTokenData(response.data);
            res.send(response.data.access_token + " \n" + "<a href='/'>Link to hoemepage</a>");
            })
        .catch(function (error) {
            console.log(error);
            res.send(error.response.data)
        })
})

app.get('/checkInit', (req, res) => {
    if(tokenData == ''){
        //if there is no token data
            //redirect to msAuth
        res.redirect("/msAuth")
    }else {
        res.json({
            auth: 'true'
        })
    }
})

app.get('/getToken/', (req, res) => {
    console.log(req.params)
})

app.get('/curToken', async (req, res) => {
    res.send(tokenData);
})

app.post("/add", async (req, res) => {
    const arraycont = req.body;
    console.log(arraycont);
    res.send("Check console");
});

app.get("/", async (req, res) => {
    return res.send("UUUUUUUUU UO HOME PAGE AAAAGG")
});

//Always send auth headers: 'Authorization': `Basic ${token}`
//more info here https://flaviocopes.com/axios-send-authorization-header/
//test link: /geteventsondate/2023-01-09.json or /geteventsondate/2023-01-04
app.get("/geteventsondate/:date", async  (req, res) => {
    if (tokenData == ''){
        return res.send('Error: Token has not been set yet')
    }
    console.log(req.params.date);
    let date = req.params.date;
    //send get request for events on date
    axios.get('https://graph.microsoft.com/v1.0/me/calendarview?startdatetime='
        + date + 'T00:00:00.000Z&'
        + 'enddatetime=' + date + 'T23:59:59.000Z', {
        headers: {
            'Authorization': `Bearer ${tokenData.access_token}`,
        }
    }).then( response => {
        let index = 0;
        var responseArray = []
        const ResDat = response.data.value;
        ResDat.forEach(obj => {
            var curDate = {
                index: index,
                state: obj.showAs,
                startTime: obj.start.dateTime.split("T"),
                endTime: obj.end.dateTime.split("T")
            }
            responseArray.push(curDate);
            /*console.log(curDate)
            console.log(`-----------------------------`)*/
            index += 1;
        });
        res.send(responseArray)
    }).catch(function (error) {
        console.log(error);
        res.send(error.response)
    })
        //convert to usable data
        //send data back - rendering will be handled with php or sumn'

});

/*app.get("/getUser", async (req, res) => {
    if (tokenData == ''){
        return res.send('Error: Token has not been set yet')
    }
    axios.get('https://graph.microsoft.com/v1.0/me/', {
        headers: {
            Authorization: `Bearer ${tokenData.access_token}`,
        }
    }).then( response => {
        res.send(response.data)
    }).catch(function (error) {
        console.log(error);
        res.send(error.response)
    })
})*/

app.get("/getSchedOn/:day", async (req, res) => {
    if (tokenData == ''){
        return res.send('Error: Token has not been set yet')
    }

    let date = req.params.day;
})

app.post("/syncToLook", async (req, res) => {
    if (tokenData == ''){
        return res.send('Error: Token has not been set yet')
    }

    let subject = req.body.subject;
    let name = req.body.name;
    let email = req.body.email
    let date = req.body.date;
    let starttime = req.body.starttime + ":00";
    let endtime = req.body.endtime + ":00";

    const postData = {
        "subject": "Reservation Confirmation",
        "body": {
            "contentType": "HTML",
            "content": "Subject: " + subject + ". Date: " + date + " at " + starttime + ". Name: " + name
        },
        "start": {
            "dateTime": date+"T"+starttime,
            "timeZone": "W. Europe Standard Time"
        },
        "end": {
            "dateTime": date+"T"+endtime,
            "timeZone": "W. Europe Standard Time"
        },
        "attendees": [
            {
                "emailAddress": {
                    "address":email,
                    "name": name
                },
                "type": "required"
            }
        ],
        "allowNewTimeProposals": true,
        "transactionId":subject + name + date + starttime + endtime
    }
    console.log(req.body)

    axios.post('https://graph.microsoft.com/v1.0/me/events', postData, {
        headers: {
            'Authorization': `Bearer ${tokenData.access_token}`,
            'Content-type': 'application/json'
        }
    })
        .then( response => {
            res.send(response.data.id);
            console.log(response.data.id);
        })
        .catch(function (error) {
            console.log(error.response);
            res.send(error.response.data)
        })

})

app.get("/getEnv", async (req, res) => {
    res.json(
        {
            env: process.env,
            tokendata: tokenData
        }
    );
});

app.get("/manualRenew", async (req, res) => {

})

app.post("/updateEnv", async (req, res) => {
    const arraycont = req.body;
})

//DELETE https://graph.microsoft.com/v1.0/me/events/{id}

app.get("/delEv/:id", async (req, res) => {
    let id = req.params.id;

    axios.get('DELETE https://graph.microsoft.com/v1.0/me/events/{id}', {
        headers: {
            'Authorization': `Bearer ${tokenData.access_token}`,
        }
    })
        .then( response => {
            res.send(response.data.id);
            console.log(response.data.id);
        })
        .catch(function (error) {
            console.log(error.response);
            res.send(error.response.data)
        })

})