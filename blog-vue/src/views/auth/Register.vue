<template>
  <div>
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
            <card type="secondary" shadow header-classes="bg-white pb-5" body-classes="px-lg-5 py-lg-5" class="border-0">
              <template>
                <div class="text-center text-muted mb-4">
                  <small>Sign up with credentials</small>
                </div>
                <div v-if="error" class="alert alert-danger text-center">
                  {{ error }}
                </div>
                <div v-if="successMessage" class="alert alert-success text-center">
                  {{ successMessage }}
                </div>
                <div v-if="loading" class="text-center">
                  <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                  <p class="mt-2 warning">Registering...</p>
                </div>
                <form role="form" @submit.prevent="register">
                  <base-input v-model="name" class="mb-3" placeholder="Name" addon-left-icon="ni ni-hat-3"></base-input>
                  <base-input v-model="email" class="mb-3" placeholder="Email" addon-left-icon="ni ni-email-83"></base-input>
                  <base-input v-model="password" type="password" class="mb-3" placeholder="Password" addon-left-icon="ni ni-lock-circle-open"></base-input>
                  <base-input v-model="password_confirmation" type="password" class="mb-3" placeholder="Confirm Password" addon-left-icon="ni ni-lock-circle-open"></base-input>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary my-4">Create Account</button>
                  </div>
                </form>
              </template>
            </card>
          </div>
        </div>
      </div>
  </section>
</div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      error: '',
      loading: false,
      successMessage: ''
    };
  },
  methods: {
    async register() {
      this.error = this.validateForm();

      if (this.error) {
        // If validation fails, show the error message
        return;
      }
      this.loading = true;
      try {
        await axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie');
        const response = await axios.post('http://127.0.0.1:8000/api/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        });

        console.log('Registration successful', response.data);
        this.successMessage = response.data.message;
        this.name = '';
        this.email = '';
        this.password = '';
        this.password_confirmation = '';
        // this.$router.push('/login'); // Redirect to login page
      } catch (error) {
        this.error = error.response.data.message || 'An error occurred during registration.';
        console.error('Registration failed', this.error);
      }
      this.loading = false;
    },

    validateForm() {
      if (!this.name || !this.email || !this.password || !this.password_confirmation) {
        return 'All fields are required.';
      }
      if (!this.isValidEmail(this.email)) {
        return 'Please enter a valid email.';
      }
      if (this.password !== this.password_confirmation) {
        return 'Passwords do not match.';
      }
      if (this.password.length < 8) {
        return 'Password must be at least 8 characters long.';
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

<style scoped>
/* Add your styles here */
</style>
