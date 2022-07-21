<template>
  <div class="card">
    <h1>Connexion</h1>
    <div class="form-row">
      <input  v-model="username" class="form-row__input" type="text" placeholder="Adresse mail">
    </div>
    <div class="form-row">
      <input v-model="password" class="form-row__input" type="password" placeholder="Mot de passe" name="" id="">
    </div>
    <div class="form-row" v-if="mode == 'login' && status== 'error_login'">
      Adresse mail et/ou mot de passe invalide
    </div>
    <div class="form-row">
      <button @click="login()" class="button">
      <span  v-if="status == 'loading'">Connexion en cours ...</span>
      <span v-else>Connexion</span> 
      </button>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
// @ is an alias to /src

export default {
  name: 'Login',
  data: function() {
    return {
      mode: 'login',
      username: '',
      password:''
    }
  },
  mounted: function (){
    //Si userId est different de -1 alors redirection /profile
    //TODO Gérer les deux cas different USER(/profile) ET ADMIN (/gestion)
    if(this.$store.state.user.userId != -1){
      this.$router.push('/profile');
      return;
    }

  }
 ,

  computed: {
    ...mapState(['status'])
  },
  methods: {
    login: function(){
      const self = this;
      this.$store.dispatch('login', {
        username: this.username,
        password: this.password,
      }).then(function(response){
        //redirection si auth ok
        //SI User redirection page profile
        if(response.data.data.userRoles == 'ROLE_USER'){
          self.$router.push('/profile');
        }else {
          //si ADMIN redirection page gestion
          self.$router.push('/gestion')
        }     
      }, function (error) {
        console.log(error);
      })     
    }
  },


}
</script>

<style scoped>
  .form-row { 
    display: flex;
    margin: 16px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .form-row__input {
    padding: 8px;
    border: none;
    border-radius: 8px;
    background-color: #f2f2f2;
    font-weight: 500;
    margin: 0 auto;
    font-size: 16px;
  }
</style>
