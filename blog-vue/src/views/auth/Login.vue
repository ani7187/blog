<template>
  <section class="section section-shaped section-lg my-0">
    <div class="shape shape-style-1 bg-gradient-default">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>
    <div class="container pt-lg-md">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <card type="secondary" shadow
                header-classes="bg-white pb-5"
                body-classes="px-lg-5 py-lg-5"
                class="border-0">
            <template>
              <div class="text-center text-muted mb-4">
                <small>Sign in with credentials</small>
              </div>
              <div v-if="error" class="alert alert-danger text-center">
                {{ error }}
              </div>
              <form role="form" @submit.prevent="login">
                <base-input alternative
                            v-model="email"
                            class="mb-3"
                            placeholder="Email"
                            addon-left-icon="ni ni-email-83">
                </base-input>
                <base-input alternative
                            v-model="password"
                            type="password"
                            placeholder="Password"
                            addon-left-icon="ni ni-lock-circle-open">
                </base-input>
                <!-- Replace base-button with a regular button -->
                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4">Sign In</button>
                </div>
              </form>
            </template>
          </card>
          <div class="row mt-3">
            <div class="col-12 text-right">
              <router-link to="/register" class="text-light">Create new account</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios';
/*axios.defaults.baseURL = 'http://127.0.0.1:8000'; // Laravel backend URL
axios.defaults.withCredentials = true;*/
export default {
  data() {
    return {
      email: '',
      password: '',
      error: ''
    };
  },

  methods: {
    async login() {
      this.error = this.validateForm();

      if (this.error) {
        // If validation fails, show the error message
        return;
      }

      try {
        await axios.get('http://127.0.0.1:8000/api/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post(
            'http://127.0.0.1:8000/api/login',
            {
              email: this.email,
              password: this.password,
            },
          {
            headers: {
              accept: 'application/json',
              'X-XSRF-TOKEN': this.getCookie("XSRF-TOKEN")
            },
            withCredentials: true
            },
        );

        localStorage.setItem('token', response.data.token);
        // await this.$router.push("/posts");
        // window.location.reload()
      } catch (error) {
        // Handle error response
        // this.error = error
        this.error = (error.response && error.response.data && error.response.data.message) || 'An error occurred.';
        console.error('Login failed', this.error);
      }
    },

    getCookie(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) {
        return parts.pop().split(';').shift();
      }
      return null; // Return null if the cookie is not found
    },

    validateForm() {
      if (!this.isValidEmail(this.email)) {
        return 'Please enter a valid email.';
      }
      if (!this.password.length) {
        return 'Password is empty.';
      }
      return '';
    },

    // Regular expression for validating email
    isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    },
  }
};
</script>

<style>
/* Add your styles here */
</style>
