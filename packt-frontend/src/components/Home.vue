<template>
  <div class="container">
    <header class="jumbotron">
      <button id="show-modal" @click="showModal = true">Show Modal</button>

      <Teleport to="body">
        <!-- use the modal component, pass in the prop -->
        <modal :show="showModal" @close="showModal = false" :flag="flag" :postUser="postUser">
          <template #header>
            <h3>Create Post</h3>
          </template>
        </modal>
      </Teleport>
      <form id="search">
        Search <input name="query" v-model="searchQuery">
      </form>
      <Post
        :data="postData"
        :columns="gridColumns"
        :filter-key="searchQuery"
        :flag="flag">
      </Post>
      <h3>{{ content }}</h3>
    </header>
  </div>
</template>
<script>
import UserService from "../services/user.service";
import Modal from '../components/Modal.vue';
import Post from '../components/Post.vue'

export default {
  name: "Home",
  components: {
    Modal,
    Post
  },
  data() {
    var postUser = localStorage.getItem('user');
    postUser = JSON.parse(postUser);
    return {
      content: "",
      showModal: false,
      searchQuery: '',
      gridColumns: ['post_title', 'post_body', 'edit', 'delete'],
      postData: [],
      flag: "post",
      postUser: postUser.user
    };
  },
  mounted() {
    UserService.getAllPosts().then(
      (response) => {
        if(response.data.status === 401)
        {
          localStorage.removeItem('user');
          window.location = '/login';
        } else {
          this.postData = response.data.data;
        }
      },
      (error) => {
        if(error.response.status === 401)
        {
          localStorage.removeItem('user');
          window.location = '/login';
        }
        this.content =
          (error.response &&
            error.response.data &&
            error.response.data.message) ||
          error.message ||
          error.toString();
      }
    );
  },
};
</script>