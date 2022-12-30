const dontenv = require('dotenv').config()

const settings = {
    'clientId': process.env.CLIENT_ID,
    'clientSecret': process.env.CLIENT_SECRET,
    'sub': process.env.SUB,
    'dataCenter': process.env.DATA_CENTER,
    'accessToken': process.env.ACCESS_TOKEN
};

module.exports = settings;

function updateEnv(){
    const settings = {
        'clientId': process.env.CLIENT_ID,
        'clientSecret': process.env.CLIENT_SECRET,
        'sub': process.env.SUB,
        'dataCenter': process.env.DATA_CENTER,
        'accessToken': process.env.ACCESS_TOKEN
    };
    module.exports = settings;
}

console.log(settings);
