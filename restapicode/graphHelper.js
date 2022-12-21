require('isomorphic-fetch');
const azure = require('@azure/identity');
const graph = require('@microsoft/microsoft-graph-client');
const authProviders =
    require('@microsoft/microsoft-graph-client/authProviders/azureTokenCredentials');

let _settings = undefined;
let _deviceCodeCredential = undefined;
let _userClient = undefined;

function initializeGraphForUserAuth(settings, deviceCodePrompt) {
    // Ensure settings isn't null
    if (!settings) {
        throw new Error('Settings cannot be undefined');
    }

    _settings = settings;

    _deviceCodeCredential = new azure.DeviceCodeCredential({
        clientId: settings.clientId,
        tenantId: settings.tenantId,
        userPromptCallback: deviceCodePrompt
    });

    const authProvider = new authProviders.TokenCredentialAuthenticationProvider(
        _deviceCodeCredential, {
            scopes: settings.graphUserScopes
        });

    _userClient = graph.Client.initWithMiddleware({
        authProvider: authProvider
    });
}
module.exports.initializeGraphForUserAuth = initializeGraphForUserAuth;