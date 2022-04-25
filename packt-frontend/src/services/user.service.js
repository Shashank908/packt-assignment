import axios from "axios";
import authHeader from "./auth-header";

const API_URL = import.meta.env.VITE_API_URL;

class UserService {
  getAllPosts() {
    return axios.get(API_URL + "posts/all", { headers: authHeader() });
  }

  getUsers() {
    return axios.get(API_URL + "users/all", { headers: authHeader() });
  }

  updateUser(data) {
    const newData = JSON.parse(data);
    delete newData['dataFlag'];
    return axios.put(API_URL + "users", newData, { headers: authHeader() });
  }

  deletePostOrUser(deleteData, flag) {
    var url = "";
    if (flag === "post") {
      url = "delete/posts/";
    }
    if (flag === "user") {
      url = "users/";
    }
    axios
      .delete(API_URL + url + deleteData.id, {
        headers: authHeader(),
      })
      .then((response) => {
        if (response.data.status === 200) {
          window.location.reload();
        } else if (response.data.status === 401) {
          localStorage.removeItem("user");
          this.$router.push("/login");
        }
      });
  }

  createPost(data) {
    return axios.post(API_URL + "posts", data, { headers: authHeader() });
  }

  updatePost(data, id) {
    return axios.put(API_URL + "posts/" + id, data, { headers: authHeader() });
  }

  updateProfile(data) {
    return axios.post(API_URL + "profile", data, { headers: authHeader() });
  }
}

export default new UserService();
