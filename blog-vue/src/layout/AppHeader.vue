<template>
  <header class="header-global">
    <base-nav class="navbar-main" transparent type="" effect="light" expand>
      <!-- Brand link -->
      <router-link slot="brand" class="navbar-brand mr-lg-5" to="/">
        <h3 class="font-weight-700 text-white">Blog</h3>
      </router-link>

      <!-- Login/Register or Logout button -->
      <div class="ml-auto">
        <div v-if="loggedIn">
          <router-link to="/my_posts" class="btn btn-primary">My posts</router-link>
          <button @click="logout" class="btn btn-danger">Logout</button>
        </div>

        <div v-else>
          <router-link to="/login" class="btn btn-primary mr-3">Login</router-link>
          <router-link to="/register" class="btn btn-secondary">Register</router-link>
        </div>
      </div>
    </base-nav>
  </header>
</template>


<script>
import BaseNav from "@/components/BaseNav";
import axios from "axios";

export default {
  components: {
    BaseNav,
  },
  data() {
    return {
      loggedIn: false, // Tracks the login status
      isVerified: false, // Determines if the account is verified
    };
  },
  created() {
    // Check login status when the component is created
    this.loggedIn = !!localStorage.getItem("token"); // If token exists, user is logged in
  },
  methods: {
    logout() {
      // Clear the login token and update state
      localStorage.removeItem("token");
      this.loggedIn = false;
      const response = axios.post(
          'http://127.0.0.1:8000/api/logout',
          {
            email: this.email,
            password: this.password,
          },
          {
            /*headers: {
              accept: 'application/json',
              'X-XSRF-TOKEN': this.getCookie("XSRF-TOKEN")
            },*/
            withCredentials: true
          },
      );
      // Optionally redirect to the homepage or login
      this.$router.push("/");
    },
  },
};
</script>

<style scoped>
/* Add any additional styling for the buttons if necessary */
.ml-auto {
  display: flex;
  justify-content: flex-end;
}
</style>
