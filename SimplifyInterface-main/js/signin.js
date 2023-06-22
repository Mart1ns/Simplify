
const DataBaseManagement = {
    createUser(username, password){
        //Tenta criar um usuario com as credenciais passadas. Se conseguir, retorna true, se nao, retorna false.
        return true; //valor temporario.
    }
};

const Signin = {
    validateFields(){
        username = document.getElementById('signin-username').value;
        password = document.getElementById('signin-password').value;
        if(username == '' || password == ''){
            console.log(username, password);
            return false;
        }
        return true;
    },

    validateUser(){
        username = document.getElementById('signin-username').value;
        password = document.getElementById('signin-password').value;
        if(Signin.validateFields() && DataBaseManagement.createUser(username, password)){
            Signin.makeLogin();
        }
    },

    // Esta funcao ainda é chamada de makeLogin, pois teoricamente ao criar a conta voce ja loga.
    makeLogin(){
        window.open("post-login.html", "_self");
    },
}

const EventListeners = {
    //Deve ser chamdo apenas uma vez no começo do programa, para evitar duplicidade de event listeners.
    subscribeFixedEventListeners(){
        document.getElementById('signin-btn').addEventListener('click', Signin.validateUser)
    },
}

EventListeners.subscribeFixedEventListeners();