/* Taken ports(taken from local xampp server)
Apache server: 80, 443
MySql server: 3306
*/

//stuff from MS graph
/*const readline = require('readline-sync');
const graphHelper = require('./graphHelper');*/
const settings = require('./appSettings');

const request = require('request');


// Express rest api stuff
const fs = require("fs/promises");
const ld = require("lodash");
const {v4: uuid} = require("uuid");
const cors = require("cors");
const express = require("express");
const mysql = require("mysql");

const Cronofy = require("cronofy")

const cronofyClient = new Cronofy({
    client_id: process.env.CLIENT_ID,
    client_secret: process.env.CLIENT_SECRET,
    data_center: process.env.DATA_CENTER
});

const app = express();
app.use(express.json());

app.listen(3000, () => {
    console.log("Server running on port 3000");
});

app.get("/", async (req, res) => {
    // Extract the "code" from the page's query string:
    const codeFromQuery = req.query.code;

    if (codeFromQuery) {
        const codeResponse = await cronofyClient.requestAccessToken({
            client_id: process.env.CLIENT_ID,
            client_secret: process.env.CLIENT_SECRET,
            grant_type: "authorization_code",
            code: codeFromQuery,
            redirect_uri: "http://localhost/ReservationSystem/adminSettings.php"
        }).catch((err) => {
            console.error(err);
        });

        console.log(codeResponse);
    }

    // ...template rendering
});''
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
    return res.render("home", {
        client_id: process.env.CLIENT_ID,
        data_center: process.env.DATA_CENTER
    });
});

app.get("/getblockedDates", async  (req, res) => {

});

app.get("/getEnv", async (req, res) => {
    res.json(settings);
});

app.post("/updateEnv", async (req, res) => {
    const arraycont = req.body;

})