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


// Express rest api stuff
const fs = require("fs/promises");
const ld = require("lodash");
const {v4: uuid} = require("uuid");
const cors = require("cors");
const express = require("express");
const mysql = require("mysql");

const app = express();
app.use(express.json());

app.listen(3000, () => {
    console.log("Server running on port 3000");
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
        "&scope=offline_access%20user.read%20mail.read"
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
        const AuthSettings = {
            'AuthCode': authCode,
        };

        module.exports = AuthSettings;
        //res.send(authCode);
        res.redirect("/reqToken")
    }
})

app.get("/reqToken", async (req, res) =>{
    //Set to form data cuz microsoft does dumb shit
    const bodyFormData = new FormData();
    bodyFormData.append('client_id', clientId);
    bodyFormData.append('grant_type', 'authorization_code');
    bodyFormData.append('scope', scopes);
    bodyFormData.append('code', auth);
    bodyFormData.append('redirect_uri', 'http://localhost:3000/getToken');


    axios.post('https://login.microsoftonline.com/common/oauth2/v2.0/token', bodyFormData)
        .then(function (response) {
            console.log(response);
            res.send(response);
        })
        .catch(function (error) {
            console.log(error);
        });

})

app.get

app.post("/add", async (req, res) => {
    const arraycont = req.body;
    console.log(arraycont);
    res.send("Check console");
});


//gotta learn how to do this shit first so hold on
//Learn get shit
app.get("/outfit", (req, res) => {
    const tops = ['gold', 'white', 'black']
    const jeans = ['grey', 'gold', 'black']
    const timbs = ['grey', 'gold', 'black']
    res.json({
        top: ld.sample(tops),
        jeans: ld.sample(jeans),
        timbs: ld.sample(timbs)
    });
});

app.get("/comments/:id", async (req, res) => {
    const id = req.params.id;
    let content;

    try {
        content = await fs.readFile(`data/comments/${id}.txt`, "utf-8");
    } catch (err) {
        return res.sendStatus(404);
    }
    res.json({
        content: content
    })
})

//Learn how to post shit
app.post("/comments", async (req, res) => {
    const id = uuid();
    const content = req.body.content;
    if (!content) {
        return res.sendStatus(400);
    }

    await fs.mkdir("data/comments", {recursive: true});
    await fs.writeFile(`data/comments/${id}.txt`, content);

    res.status(201).json({
        id: id
    });
});

app.get("/", async (req, res) => {
    return res.send("UUUUUUUUU UO HOME PAGE AAAAGG")
});

app.get("/getblockedDates", async  (req, res) => {

});

app.get("/getEnv", async (req, res) => {
    res.json(settings);
});

app.post("/updateEnv", async (req, res) => {
    const arraycont = req.body;

})