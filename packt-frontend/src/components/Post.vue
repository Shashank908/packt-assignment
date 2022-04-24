<script>
import Modal from "../components/Modal.vue";
import UserService from "../services/user.service";

export default {
  props: {
    data: Array,
    columns: Array,
    filterKey: String,
    flag: String,
  },
  components: {
    Modal,
  },
  data() {
    return {
      ptitle: "",
      postBody: "",
      postUser:"",
      id: "",
      showModal: false,
      sortKey: "",
      sortOrders: this.columns.reduce((o, key) => ((o[key] = 1), o), {}),
      userData: [],
    };
  },
  computed: {
    filteredData() {
      const sortKey = this.sortKey;
      const filterKey = this.filterKey && this.filterKey.toLowerCase();
      const order = this.sortOrders[sortKey] || 1;
      let data = this.data;
      if (filterKey) {
        data = data.filter((row) => {
          return Object.keys(row).some((key) => {
            return String(row[key]).toLowerCase().indexOf(filterKey) > -1;
          });
        });
      }
      if (sortKey) {
        data = data.slice().sort((a, b) => {
          a = a[sortKey];
          b = b[sortKey];
          return (a === b ? 0 : a > b ? 1 : -1) * order;
        });
      }
      return data;
    },
  },
  methods: {
    sortBy(key) {
      this.sortKey = key;
      this.sortOrders[key] = this.sortOrders[key] * -1;
    },
    capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    },
    deletePost(data) {
      const deleteData = JSON.parse(JSON.stringify(data));
      UserService.deletePostOrUser(deleteData, this.flag);
    },
    editPost(entry) {
      (this.id = entry.id), (this.ptitle = entry.post_title);
      this.postBody = entry.post_body;
      this.postUser = entry.user;
      this.showModal = true;
      this.userData = entry;
    },
  },
};
</script>

<template>
  <table v-if="filteredData.length">
    <thead>
      <tr>
        <th
          v-for="key in columns"
          @click="sortBy(key)"
          v-bind:key="key"
          :class="{ active: sortKey == key }"
        >
          {{ capitalize(key) }}
          <span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
          </span>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="entry in filteredData" v-bind:key="entry">
        <td v-for="key in columns" v-bind:key="key">
          {{ entry[key] }}
          <button v-if="key === 'edit'" @click="editPost(entry)">
            {{ key }}
          </button>
          <button v-if="key === 'delete'" @click="deletePost(entry)">
            {{ key }}
          </button>
        </td>
      </tr>
      <Teleport to="body">
        <!-- use the modal component, pass in the prop -->
        <modal
          :show="showModal"
          @close="showModal = false"
          id="myId"
          :postId="id"
          :postTitle="ptitle"
          :postBody="postBody"
          :postUser="postUser"
          :flag="this.flag"
          :userData="this.userData"
        >
          <template #header>
            <h3 v-if="this.flag === 'post'">Create Post</h3>
            <h3 v-else>Create User</h3>
          </template>
        </modal>
      </Teleport>
    </tbody>
  </table>
  <p v-else>No matches found.</p>
</template>

<style>
table {
  border: 2px solid #42b983;
  border-radius: 3px;
  background-color: #fff;
}

th {
  background-color: #42b983;
  color: rgba(255, 255, 255, 0.66);
  cursor: pointer;
  user-select: none;
}

td {
  background-color: #f9f9f9;
}

th,
td {
  min-width: 120px;
  padding: 10px 20px;
}

th.active {
  color: #fff;
}

th.active .arrow {
  opacity: 1;
}

.arrow {
  display: inline-block;
  vertical-align: middle;
  width: 0;
  height: 0;
  margin-left: 5px;
  opacity: 0.66;
}

.arrow.asc {
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-bottom: 4px solid #fff;
}

.arrow.dsc {
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-top: 4px solid #fff;
}
</style>