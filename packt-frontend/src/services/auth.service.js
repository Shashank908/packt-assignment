import axios from "axios";
import authHeader from "./auth-header";

const API_URL = "http://localhost:8000/api/";

class AuthService {
  login(user) {
    return axios
      .post(API_URL + "login", {
        email: user.username,
        password: user.password,
        device_name: "manual",
      })
      .then((response) => {
        if (response.data.accessToken) {
          localStorage.setItem("user", JSON.stringify(response.data));
        }
        return response.data;
      });
  }

  logout() {
    axios
      .get(API_URL + "logout", { headers: authHeader() })
      .then((response) => {
        if (response.data.status === 200) {
          localStorage.removeItem("user");
        }
      });
  }

  register(user) {
    return axios.post(API_URL + "register", {
      name: user.name,
      email: user.email,
      password: user.password,
    }).then((response) => {
      if (response.data.accessToken) {
        localStorage.setItem("user", JSON.stringify(response.data));
      }
      return response.data;
    });
  }
}

export default new AuthService();
