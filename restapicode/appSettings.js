const dontenv = require('dotenv').config()

const settings = {
    'clientId': process.env.CLIENT_ID,
    'tenantId': process.env.TENANT_ID,
    'graphUserScopes': [
        'user.read',
        'mail.read',
        'mail.send'
    ]
};

module.exports = settings;
