<template>
  <div class="container">
    <header class="jumbotron">
      <h3>
        <strong>{{ currentUser.user.name }}</strong> Profile
      </h3>
    </header>
    <Form @submit="updateProfile" :validation-schema="schema">
      <div class="form-group">
        <label for="email">Email</label>
        <Field
          name="email"
          type="text"
          readonly
          class="form-control"
          :value="currentUser.user.email"
        />
        <ErrorMessage name="email" class="error-feedback" />
      </div>
      <div class="form-group">
        <label for="gender">Gender</label>
        <Field
          name="gender"
          type="text"
          class="form-control"
          :value="currentUser.user.gender"
        />
        <ErrorMessage name="gender" class="error-feedback" />
      </div>
      <div class="form-group">
        <label for="status">Status</label>
        <Field
          name="status"
          type="text"
          class="form-control"
          :value="currentUser.user.status"
        />
        <ErrorMessage name="status" class="error-feedback" />
      </div>
      <div class="form-group">
        <label for="address">Address</label>
        <Field
          name="address"
          type="text"
          class="form-control"
          :value="currentUser.user.address"
        />
        <ErrorMessage name="address" class="error-feedback" />
      </div>
      <div class="form-group">
        <label for="city">City</label>
        <Field
          name="city"
          type="text"
          class="form-control"
          :value="currentUser.user.city"
        />
        <ErrorMessage name="city" class="error-feedback" />
      </div>
      <div class="form-group">
        <label for="country">Country</label>
        <Field
          name="country"
          type="text"
          class="form-control"
          :value="currentUser.user.country"
        />
        <ErrorMessage name="country" class="error-feedback" />
      </div>
      <div class="form-group">
        <button class="btn btn-primary btn-block" :disabled="loading">
          <span
            v-show="loading"
            class="spinner-border spinner-border-sm"
          ></span>
          <span>Update Profile</span>
        </button>
      </div>
      <div class="form-group">
        <div v-if="message" class="alert alert-danger" role="alert">
          {{ message }}
        </div>
      </div>
    </Form>
  </div>
</template>
<script>
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from "yup";
import UserService from "../services/user.service";

export default {
  name: "Profile",
  components: {
    Form,
    Field,
    ErrorMessage,
  },
  data() {
    const schema = yup.object().shape({
      email: yup.string().required("Email is required!"),
      gender: yup.string().required("Gender is required!"),
      status: yup.string().required("Status is required!"),
      address: yup.string().required("Address is required!"),
      city: yup.string().required("City is required!"),
      country: yup.string().required("Country is required!"),
    });
    return {
      timerCount: 3 * 60 * 1000,
      message: "",
      schema,
    };
  },
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    },
  },
  watch: {
    timerCount: {
      handler(value) {
        if (value > 0) {
          setTimeout(() => {
            this.timerCount--;
          }, 2 * 1300);
        }
        if (value == 0) {
          localStorage.removeItem("user");
          window.location = "/login";
        }
      },
      immediate: true,
    },
  },
  mounted() {
    if (!this.currentUser) {
      this.$router.push("/login");
    }
  },
  methods: {
    updateProfile(data) {
      UserService.updateProfile(JSON.stringify(data)).then((response) => {
        if (response.data.status === 200) {
          const storageData = JSON.parse(localStorage.getItem("user"));
          storageData.user = response.data.data;
          localStorage.setItem("user", JSON.stringify(storageData));
          window.location.reload();
        } else if (response.data.status === 401) {
          localStorage.removeItem("user");
          this.$router.push("/login");
        }
      });
    },
  },
};
</script>
