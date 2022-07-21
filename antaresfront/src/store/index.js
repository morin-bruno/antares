import { createStore } from 'vuex'

// call axios
const axios = require('axios');

//Définition d'une constance pour adresse de BASE de api
const instance= axios.create({
  baseURL: 'http://localhost:8000/api/v1'
})

let user = localStorage.getItem('user');
//verif si User est null, si c'est le cas valeur par défaut prédéfini ici
if(!user) {
  user = {
   userId: -1,
   token: '',
  };
} else {
    try{
      user = JSON.parse(user);
      //Ajout du token dans l'en tete
      instance.defaults.headers.common['Authorization'] = user.token;
    } catch (ex) {
      user = {
        userId: -1,
        token: '',
      }
    }
}

export default createStore({
  //param valeur données du state
  state: {
    status:'',
    user: user,
    userInfos: {
      lastname:'',
      firstname:'',
      email:'',
      age:''
    }
  },
  getters: {
  },
  mutations: {
    setStatus: function (state, status) {
      state.status = status;
    },
    logUser: function(state, user) {
      instance.defaults.headers.common['Authorization'] = user.token;
      localStorage.setItem('user', JSON.stringify(user));
      state.user = user;
    },
    userInfos: function (state, userInfos) {
      state.userInfos = userInfos;
    },
    //valeur a affecté après deconnexion
    logout: function (state) {
      state.user = {
        userId: -1,
        token: '',
      }
      //Suppresion des éléments du localStorage user
      localStorage.removeItem('user');
    }
  },
  actions: {
    login: ({commit}, userInfos )=> {
      commit('setStatus', 'loading');
      return new Promise((resolve, reject) => {
        instance.post('/login', userInfos)
        .then(function(response) {
          commit('setStatus', '')
          commit('logUser', response.data)
          resolve(response);
        })
        .catch(function (error) {
          commit('setStatus', 'error_login')
          reject(error);
        });
      });
    },
    getUserInfos: ({commit}) => {
      instance.get('/users/'+ user.data.userID)
        .then(function(response) {
          //console.log(response.data)
          commit('userInfos', response.data)
        })
        .catch(function () {
        });
    },
    getAllUserInfos: ({commit}) => {
      instance.get('/users')
        .then(function(response) {
          //console.log(response.data.userList)
          commit('userInfos', response.data.userList)
        })
        .catch(function () {
        });
    }
  },
  modules: {
  }
})
