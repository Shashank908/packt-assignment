<template>
  <div class="container">
    <header class="jumbotron">
      <button id="show-modal" @click="showModal = true">Show Modal</button>

      <Teleport to="body">
        <!-- use the modal component, pass in the prop -->
        <modal
          :show="showModal"
          @close="showModal = false"
          :flag="flag"
          :userData="this.userData"
        >
          <template #header>
            <h3>Create Post</h3>
          </template>
        </modal>
      </Teleport>
      <form id="search">
        Search <input name="query" v-model="searchQuery" />
      </form>
      <Post
        :data="postData"
        :columns="gridColumns"
        :filter-key="searchQuery"
        :flag="flag"
      >
      </Post>
      <h3>{{ content }}</h3>
    </header>
  </div>
</template>
<script>
import UserService from "../services/user.service";
import Post from "../components/Post.vue";
import Modal from "../components/Modal.vue";

export default {
  name: "User",
  components: {
    Post,
    Modal,
  },
  data() {
    return {
      content: "",
      searchQuery: "",
      gridColumns: ["name", "email", "edit", "delete"],
      postData: [],
      flag: "user",
      showModal: false,
      userData: [],
    };
  },
  mounted() {
    UserService.getUsers().then(
      (response) => {
        if (response.data.status === 200) {
          this.postData = response.data.data;
        } else if (response.data.status === 401) {
          localStorage.removeItem("user");
          this.$router.push("/login");
        } else {
          this.content = response.data.message;
        }
      },
      (error) => {
        try {
          this.content =
            (error.response &&
              error.response.data &&
              error.response.data.message) ||
            error.message ||
            error.toString();
        } catch (error) {
          console.log(error);
        }
      }
    );
  },
};
</script>
