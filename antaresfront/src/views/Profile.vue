<template>
  <div class="card">
    <h1 class="card_title">Espace Perso</h1>
    <p>Mes informations</p>
    <ul>
      <li>Nom : {{ user.lastname }}</li>
      <li>Prénom : {{ user.firstname }}</li>
      <li>Age : {{ user.age }}</li>
      <li>E-mail: {{ user.email }}</li>
    </ul>
    <div class="form-row">
      <button @click="logout()">
      <span >Déconnexion</span> 
      </button>
    </div>
  </div>
</template>


<script>
import { mapState } from 'vuex'

export default {
  name: 'Profile',
  mounted: function () {
    //console.log(this.$store.state.user.data);;
    //Condition si userId = -1, redirection sur la page de connexion
    if(this.$store.state.user.userId == -1) {
      this.$router.push('/');
      return ;
    }
    this.$store.dispatch('getUserInfos');
  },
  computed: {
    ...mapState({
      user: 'userInfos'
    })
  },
  methods: {
    //Method for logout
    logout: function (){
      this.$store.commit('logout');
      this.$router.push('/');
    }
  }
}
</script>

<style scoped>
  
  li{
    list-style-type: none;
    color: #f0f;
    font-weight: bold;
  }
</style>
