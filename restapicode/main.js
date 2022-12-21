/* Taken ports(taken from local xampp server)
Apache server: 80, 443
MySql server: 3306
*/

var express = require("express");
var app = express();
app.listen(3000, () => {
    console.log("Server running on port 3000");
});