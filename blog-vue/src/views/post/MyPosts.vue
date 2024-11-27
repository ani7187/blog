<template>
  <div>
    <div class="position-relative">
      <!-- shape Hero -->
      <section class="section-shaped my-0">
        <div class="shape shape-style-1 shape-default shape-skew">
          <span></span><span></span><span></span><span></span>
        </div>
        <div class="container shape-container d-flex">
          <span></span><span></span><span></span><span></span>
          <span></span><span></span><span></span><span></span>
          <div class="col px-0">
            <!-- Add Post Button -->
            <div v-if="isLoggedIn" class="text-left">
              <button class="btn btn-success" @click="openAddPostModal">Add New Post</button>
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
                  <!-- Edit and Delete Buttons -->
                  <div v-if="isLoggedIn && post.user_id === 15">
                    <button class="btn btn-primary" @click="openEditPostModal(post)">Edit</button>
                    <button class="btn btn-danger" @click="deletePost(post.id)">Delete</button>
                  </div>
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

    <!-- Modal for Add/Edit Post -->
    <div v-if="showModal" class="modal" tabindex="-1" style="display: block;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditMode ? 'Edit Post' : 'Add New Post' }}</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitPostForm">
              <div class="mb-3">
                <label for="postTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="postTitle" v-model="postForm.title" required>
              </div>
              <div class="mb-3">
                <label for="postContent" class="form-label">Content</label>
                <textarea class="form-control" id="postContent" v-model="postForm.content" required></textarea>
              </div>
              <div class="upload-image mt-3">
                <label for="imageUpload" class="btn btn-secondary">Upload Image</label>
                <input
                    type="file"
                    id="imageUpload"
                    ref="imageUpload"
                    accept="image/*"
                    @change="handleFileUpload"
                    style="display: none;"
                />
              </div>
              <button type="submit" class="btn btn-primary">{{ isEditMode ? 'Update' : 'Add' }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
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
      showModal: false, // Toggle the modal visibility
      isEditMode: false, // Check if the user is editing a post
      postForm: { title: "", content: "", image_url: "" }, // Data binding for post form
      selectedPostId: null, // Store the ID of the post being edited
      userId: localStorage.getItem('userId') // Assuming userId is saved in localStorage
    };
  },
  mounted() {
    this.fetchPosts(); // Fetch posts when the component is loaded
  },
  methods: {
    async fetchPosts(page = 1) {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/posts?page=${page}`, { withCredentials: true });
        this.posts = response.data;
        this.currentPage = page;
      } catch (error) {
        console.error("Error fetching posts:", error);
      }
    },
    openAddPostModal() {
      this.isEditMode = false;
      this.postForm = { title: "", content: "", image_url: "" };
      this.showModal = true;
    },
    handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          // Once the file is loaded, set the image URL in the form
          this.postForm.image_url = e.target.result; // Base64 encoded image
        };
        reader.readAsDataURL(file); // Read the image as a base64 string
      }
    },
    openEditPostModal(post) {
      this.isEditMode = true;
      this.selectedPostId = post.id;
      this.postForm = { ...post };
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.selectedPostId = null;
    },
    async submitPostForm() {
      try {
        if (this.isEditMode) {
          await axios.put(`http://127.0.0.1:8000/api/posts/${this.selectedPostId}`, this.postForm, { withCredentials: true });
          this.fetchPosts(this.currentPage); // Refresh the posts list
        } else {
          await axios.post('http://127.0.0.1:8000/api/posts', this.postForm, { withCredentials: true });
          this.fetchPosts(this.currentPage); // Refresh the posts list
        }
        this.closeModal();
      } catch (error) {
        console.error("Error submitting post:", error);
      }
    },
    async deletePost(postId) {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/posts/${postId}`, { withCredentials: true });
        this.fetchPosts(this.currentPage); // Refresh the posts list
      } catch (error) {
        console.error("Error deleting post:", error);
      }
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
