<template>
  <div>
    <div class="container">
      <h1 class="my-4">Create a New Post</h1>
      <form @submit.prevent="submitPost" enctype="multipart/form-data">
        <!-- Post Title -->
        <div class="form-group">
          <label for="title">Title</label>
          <input
              v-model="form.title"
              type="text"
              class="form-control"
              id="title"
              placeholder="Enter post title"
              required
          />
        </div>

        <!-- Post Content -->
        <div class="form-group">
          <label for="content">Content</label>
          <textarea
              v-model="form.content"
              class="form-control"
              id="content"
              rows="4"
              placeholder="Enter post content"
              required
          ></textarea>
        </div>

        <!-- Post Image -->
        <div class="form-group">
          <label for="image">Image (Optional)</label>
          <input
              v-model="form.image"
              type="file"
              class="form-control"
              id="image"
              @change="handleImageChange"
          />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">
          Create Post
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "PostForm",
  data() {
    return {
      form: {
        title: "",
        content: "",
        image: null,
      },
    };
  },
  methods: {
    handleImageChange(event) {
      this.form.image = event.target.files[0];
    },
    async submitPost() {
      const formData = new FormData();
      formData.append("title", this.form.title);
      formData.append("content", this.form.content);
      if (this.form.image) {
        formData.append("image", this.form.image);
      }

      try {
        const response = await axios.post("http://127.0.0.1:8000/api/posts", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
        this.$router.push({ name: "home" }); // Redirect to home or wherever you want after post creation
        console.log("Post created:", response.data);
      } catch (error) {
        console.error("Error creating post:", error);
      }
    },
  },
};
</script>

<style scoped>
/* Add any styles specific to this component */
</style>
