<template>
<router-view/>
</template>

<style lang="scss">

html {
  height: 100VH;
  background: #2c3e50;
}

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #98b8d8;
}

.card {
  border: 1px solid #98b8d8;
  border-radius: 8px;
  width: 500px;
  padding: 30px;
  margin: auto;
    h1{
      border-bottom: 1px solid#fff;
    }
}

button{
  padding: 8px;
  border: none;
  border-radius: 8px;
  background-color: #98b8d8;
  font-weight: 500;
  margin: 0 auto;
  font-size: 16px;
}
</style>
