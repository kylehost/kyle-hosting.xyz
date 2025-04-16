
const client = new Appwrite.Client()
.setEndpoint('https://fra.cloud.appwrite.io/v1')
.setProject('67d613d60033b085b740');

const account = new Appwrite.Account(client);

document.getElementById('login-button').addEventListener('click', async () => {
try {
    const redirectURI = window.location.href;
    const response = await account.createOAuth2Session('google', redirectURI);
    window.location.href = response;
} catch (error) {
    console.error('Login error:', error);
}
});

document.getElementById('logout-button').addEventListener('click', async () => {
try {
    await account.deleteSession('current'); // Logout
    document.getElementById('user-info').style.display = 'none';
    document.getElementById('login-button').style.display = 'block';
} catch (error) {
    console.error('Logout error:', error);
}
});
async function checkUser () {
try {
    const user = await account.get();
    document.getElementById('user-data').textContent = JSON.stringify(user, null, 2);
    document.getElementById('user-info').style.display = 'block';
    document.getElementById('login-button').style.display = 'none';
} catch (error) {
    console.log('User  not logged in');
}
}

checkUser ();