<template>
  <div class="card">
    <h1 class="card_title">Gestions des utilisateurs</h1>
    <p>Les informations</p>
    <table>
      <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Age</th>
        <th>Email</th>
      </tr>
      <tr v-for="user in userInfos" v-bind:key="user.id">
        <td>{{user.lastname}}</td>
        <td>{{user.firstname}}</td>
        <td>{{user.age}}</td>
        <td>{{user.email}}</td>
        <td></td>
      </tr>
    </table>
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
  name: 'Gestion',
  mounted: function () {
    //console.log(this.$store.state);
    if(this.$store.state.user.userId == -1) {
      this.$router.push('/');
      return ;
    }
    this.$store.dispatch('getAllUserInfos');
  },
  computed: {
    ...mapState({
      userInfos: 'userInfos'
    })
  },
  methods: {
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
