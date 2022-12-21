/* Taken ports(taken from local xampp server)
Apache server: 80, 443
MySql server: 3306
*/

const fs = require("fs/promises");
const ld = require("lodash");
const {v4 : uuid} = require("uuid");
const cors = require("cors");
const express = require("express");

const app = express();

app.listen(3000, () => {
    console.log("Server running on port 3000");
});

app.get("/url", (req, res, next) => {
    res.json(["Tony","Lisa","Michael","Ginger","Food"]);
});