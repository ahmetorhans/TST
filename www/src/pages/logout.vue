<template>

</template>
<script>

export default {
  data() {
    return {
    
    };
  },
  created() {
    this.logout();
  },
 methods : {
    logout: function () {
      localStorage.removeItem('vue-authenticate.vueauth_token');
      //window.location.href='/login';
         this.$router.push('/login');
    },
 }
};
</script>