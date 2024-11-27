<template>
  <div>
    <div class="position-relative">
      <!-- shape Hero -->
      <section class="section-shaped my-0">
        <div class="shape shape-style-1 shape-default shape-skew">
          <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
        </div>
        <div class="container shape-container d-flex">
          <div class="col px-0">
            <div class="row">
              <div v-if="!isLoggedIn" class="col-lg-6">
                <h1 class="display-3 text-white">Welcome to Our Blog</h1>
                <p class="lead text-white">
                  Explore fresh ideas, inspiring stories, and insightful content across a variety of topics. Whether you're here to learn, discover, or share, you'll find something that sparks your curiosity. Join us and be part of our growing community!
                </p>
              </div>
              <div v-else class="col-lg-6">
                <h1 class="display-3 text-white">Fresh posts</h1>
                <p class="lead text-white">
                  Dive into our latest articles, covering a range of topics that inspire, educate, and entertain. Stay updated with the freshest content from our community of contributors!
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- 1st Hero Variation -->
    </div>

    <section class="section section-lg pt-lg-0 mt--200">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-12">
            <div class="row row-grid">
              <!-- Loop through posts array -->
              <div class="col-lg-9 my-3" v-for="(post, index) in posts.data" :key="index">
                <card class="border-0" hover shadow body-classes="py-6">
                  <img :src="post.image_url" alt="Post image" class="img-fluid mb-4 rounded">
                  <h6 class="text-primary text-uppercase">{{ post.title }}</h6>
                  <p class="description mt-3">{{ post.content }}</p>
                  <small class="text-muted">Author: {{ post.user.name }}</small>
                </card>
              </div>
            </div>
            <!-- Pagination Controls -->
            <div v-if="posts.last_page > 1" class="d-flex justify-content-center mt-4">
              <button
                  :disabled="currentPage === 1"
                  @click="changePage(currentPage - 1)"
                  class="btn btn-primary mx-1"
              >
                Previous
              </button>
              <span class="mx-2">{{ currentPage }} of {{ posts.last_page }}</span>
              <button
                  :disabled="currentPage === posts.last_page"
                  @click="changePage(currentPage + 1)"
                  class="btn btn-primary mx-1"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "home",
  data() {
    return {
      posts: { data: [] }, // Posts array to store post data
      isLoggedIn: !!localStorage.getItem('token'), // Example check for a token
      currentPage: 1, // Keep track of the current page
    };
  },
  mounted() {
    this.fetchPosts(); // Fetch posts when the component is loaded
  },
  methods: {
    async fetchPosts(page = 1) {
      try {
        // const response = axios.get(`http://127.0.0.1:8000/api/posts?page=${page}`)
        await axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie', {withCredentials: true});

        const response = await axios.get(
            `http://127.0.0.1:8000/api/posts?page=${page}`,
            {
              headers: {
                accept: 'application/json',
                'X-XSRF-TOKEN': this.getCookie("XSRF-TOKEN")
              },
              withCredentials: true
            },
        );


        this.posts = response.data;
        this.currentPage = page;
      } catch (error) {
        console.error("Error fetching posts:", error);
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

    changePage(page) {
      if (page >= 1 && page <= this.posts.last_page) {
        this.fetchPosts(page);
      }
    }
  }
};
</script>

<style scoped>
/* Add custom styles here if necessary */
</style>
