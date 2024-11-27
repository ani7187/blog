import Vue from "vue";
import Router from "vue-router";
import AppHeader from "./layout/AppHeader";
import AppFooter from "./layout/AppFooter";
import Landing from "./views/Landing.vue";
import MyPosts from "@/views/post/MyPosts.vue";
import Login from "@/views/auth/Login.vue";
import Register from "@/views/auth/Register.vue";
import Posts from "@/views/post/Posts.vue";

Vue.use(Router);

const router = new Router({
  // linkExactActiveClass: "active",
  mode: 'history',
  routes: [
    {
      path: "/",
      name: "landing",
      components: {
        header: AppHeader,
        default: Landing,
        // footer: AppFooter
      }
    },
    {
      path: "/login",
      name: "login",
      components: {
        header: AppHeader,
        default: Login,
        footer: AppFooter
      }
    },
    {
      path: "/register",
      name: "register",
      components: {
        header: AppHeader,
        default: Register,
        footer: AppFooter
      }
    },
    {
      path: "/posts",
      name: "posts",
      components: {
        header: AppHeader,
        default: Posts,
        footer: AppFooter
      }
    },
    {
      path: "/my_posts",
      name: "my_posts",
      components: {
        header: AppHeader,
        default: MyPosts,
        footer: AppFooter
      }
    },

  ],
  scrollBehavior: to => {
    if (to.hash) {
      return { selector: to.hash };
    } else {
      return { x: 0, y: 0 };
    }
  },
});

router.beforeEach((to, from, next) => {
  const isLoggedIn = !!localStorage.getItem('token'); // Example check for a token

  if ((to.path === '/' || to.path === '/login' || to.path === '/register') && isLoggedIn) {
    next('/posts'); // Redirect to template if logged in
  } else {
    next(); // Continue to the requested route
  }
});

export default router;

