const dontenv = require('dotenv').config()

const settings = {
    'clientId': process.env.CLIENT_ID,
    'tenantId': process.env.TENANT_ID,
    'clientSecret': process.env.CLIENT_SECRET
};

module.exports = settings;

console.log(settings);
