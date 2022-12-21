/* Taken ports(taken from local xampp server)
Apache server: 80, 443
MySql server: 3306
*/

//stuff from MS graph
const readline = require('readline-sync');

const settings = require('./appSettings');
const graphHelper = require('./graphHelper');


// Express rest api stuff
const fs = require("fs/promises");
const ld = require("lodash");
const {v4 : uuid} = require("uuid");
const cors = require("cors");
const express = require("express");
const mysql = require("mysql");

const app = express();
app.use(express.json());

app.listen(3000, () => {
    console.log("Server running on port 3000");
});

app.post("/add", async (req, res) => {
   const arraycont = req.body;
   console.log(arraycont);
   res.send("Check console");
});


//gotta learn how to do this shit first so hold on
//Learn get shit
app.get("/outfit", (req, res) =>{
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
    } catch (err){
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
   if(!content){
       return res.sendStatus(400);
   }

   await fs.mkdir("data/comments", {recursive: true});
   await  fs.writeFile(`data/comments/${id}.txt`, content);

   res.status(201).json({
       id: id
   });
});

//Ms graph code
async function main() {
    console.log('JavaScript Graph Tutorial');

    let choice = 0;

    // Initialize Graph
    initializeGraph(settings);

    // Greet the user by name
    await greetUserAsync();

    const choices = [
        'Display access token',
        'List my inbox',
        'Send mail',
        'Make a Graph call'
    ];

    while (choice != -1) {
        choice = readline.keyInSelect(choices, 'Select an option', { cancel: 'Exit' });

        switch (choice) {
            case -1:
                // Exit
                console.log('Goodbye...');
                break;
            case 0:
                // Display access token
                await displayAccessTokenAsync();
                break;
            case 1:
                // List emails from user's inbox
                await listInboxAsync();
                break;
            case 2:
                // Send an email message
                await sendMailAsync();
                break;
            case 3:
                // Run any Graph code
                await makeGraphCallAsync();
                break;
            default:
                console.log('Invalid choice! Please try again.');
        }
    }
}

main();

function initializeGraph(settings) {
    // TODO
}

async function greetUserAsync() {
    // TODO
}

async function displayAccessTokenAsync() {
    // TODO
}

async function listInboxAsync() {
    // TODO
}

async function sendMailAsync() {
    // TODO
}

async function makeGraphCallAsync() {
    // TODO
}