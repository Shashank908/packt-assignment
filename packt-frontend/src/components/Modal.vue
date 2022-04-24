<template>
  <Transition name="modal">
    <div v-if="show" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
            <slot name="header" v-if="this.flag === 'post'">Create Post</slot>
            <slot name="header" v-else>Create User</slot>
            <button class="modal-default-button" @click="$emit('close')">
              X
            </button>
          </div>

          <div class="modal-body">
            <slot name="body">
              <Form @submit="createPost" :validation-schema="schema">
                <div v-if="this.flag === 'post'">
                  <div class="form-group">
                    <label for="title">Post Title</label>
                    <Field
                      :value="postTitle"
                      name="title"
                      type="text"
                      class="form-control"
                    />
                    <ErrorMessage name="title" class="error-feedback" />
                  </div>
                  <div class="form-group">
                    <label for="postBody">Post Body</label>
                    <Field
                      :value="postBody"
                      name="postBody"
                      as="textarea"
                      rows="5"
                      class="form-control"
                    />
                    <ErrorMessage name="postBody" class="error-feedback" />
                  </div>
                  <div class="form-group">
                    <label for="postUser">Post Creator</label>
                    <Field
                      :value="!postUser ? '' : postUser.name"
                      name="name"
                      type="text"
                      class="form-control"
                      readonly
                    />
                  </div>
                  <div class="form-group">
                    <label for="postUser">Post Creator Email</label>
                    <Field
                      :value="!postUser ? '' : postUser.email"
                      name="email"
                      type="email"
                      class="form-control"
                      readonly
                    />
                  </div>
                  <div class="form-group">
                    <button
                      class="btn btn-primary btn-block"
                      :disabled="loading"
                    >
                      <span
                        v-show="loading"
                        class="spinner-border spinner-border-sm"
                      ></span>
                      <span>Post</span>
                    </button>
                  </div>
                  <div class="form-group">
                    <div v-if="message" class="alert alert-danger" role="alert">
                      {{ message }}
                    </div>
                  </div>
                </div>
                <div v-else>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <Field
                      :value="this.userData.email"
                      name="email"
                      type="text"
                      class="form-control"
                      :readonly="this.userData.email != null ? true : false"
                    />
                    <ErrorMessage name="email" class="error-feedback" />
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <Field
                      name="password"
                      type="password"
                      class="form-control"
                    />
                    <ErrorMessage name="password" class="error-feedback" />
                  </div>
                  <div class="form-group">
                    <label for="name">Name</label>
                    <Field
                      :value="this.userData.name"
                      name="name"
                      type="text"
                      class="form-control"
                    />
                    <ErrorMessage name="name" class="error-feedback" />
                  </div>
                  <div class="form-group">
                    <label for="gender">Gender</label>
                    <Field
                      :value="this.userData.gender"
                      name="gender"
                      type="text"
                      class="form-control"
                    />
                    <ErrorMessage name="gender" class="error-feedback" />
                  </div>
                  <div class="form-group">
                    <label for="status">Status</label>
                    <Field
                      :value="this.userData.status"
                      name="status"
                      type="text"
                      class="form-control"
                    />
                    <ErrorMessage name="status" class="error-feedback" />
                  </div>
                  <div class="form-group">
                    <label for="address">Address</label>
                    <Field
                      :value="this.userData.address"
                      name="address"
                      type="text"
                      class="form-control"
                    />
                    <ErrorMessage name="address" class="error-feedback" />
                  </div>
                  <div class="form-group">
                    <label for="city">City</label>
                    <Field
                      :value="this.userData.city"
                      name="city"
                      type="text"
                      class="form-control"
                    />
                    <ErrorMessage name="city" class="error-feedback" />
                  </div>
                  <div class="form-group">
                    <label for="country">Country</label>
                    <Field
                      :value="this.userData.country"
                      name="country"
                      type="text"
                      class="form-control"
                    />
                    <ErrorMessage name="country" class="error-feedback" />
                  </div>
                  <div class="form-group">
                    <button
                      class="btn btn-primary btn-block"
                      :disabled="loading"
                    >
                      <span
                        v-show="loading"
                        class="spinner-border spinner-border-sm"
                      ></span>
                      <span>Save</span>
                    </button>
                  </div>
                  <div class="form-group">
                    <div v-if="message" class="alert alert-danger" role="alert">
                      {{ message }}
                    </div>
                  </div>
                </div>
              </Form>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script>
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from "yup";
import axios from "axios";
import authHeader from "../services/auth-header.js";
import { object, string } from "yup/lib/locale";
import UserService from "../services/user.service";

export default {
  name: "Home",
  props: {
    show: Boolean,
    postData: object,
    postTitle: string,
    postBody: string,
    postUser: Array,
    postId: string,
    flag: string,
    userData: Array,
  },
  components: {
    Form,
    Field,
    ErrorMessage,
  },
  data() {
    const schema = yup.object().shape({
      title: yup.string().when("this.flag", {
        is: "post",
        then: yup.string().required("Post Title is required!"),
      }),
      postBody: yup.string().when("this.flag", {
        is: "post",
        then: yup.string().required("Post Body is required!"),
      }),
      email: yup.string().when("this.flag", {
        is: "user",
        then: yup.string().required("Email is required!"),
      }),
      gender: yup.string().when("this.flag", {
        is: "user",
        then: yup.string().required("Gender is required!"),
      }),
      status: yup.string().when("this.flag", {
        is: "user",
        then: yup.string().required("Status is required!"),
      }),
      address: yup.string().when("this.flag", {
        is: "user",
        then: yup.string().required("Address is required!"),
      }),
      city: yup.string().when("this.flag", {
        is: "user",
        then: yup.string().required("City is required!"),
      }),
      country: yup.string().when("this.flag", {
        is: "user",
        then: yup.string().required("Country is required!"),
      }),
    });
    return {
      message: "",
      schema,
    };
  },
  methods: {
    setLocalData(response) {
      if (response.data.status === 200) {
        const storageData = JSON.parse(localStorage.getItem("user"));
        storageData.user = response.data.user;
        localStorage.setItem("user", JSON.stringify(storageData));
        window.location.reload();
      }
    },
    createPost(data) {
      if (this.flag === "post") {
        if (!this.postTitle && !this.postBody) {
          UserService.createPost(JSON.stringify(data)).then((response) => {
            this.setLocalData(response);
          });
        } else {
          UserService.updatePost(JSON.stringify(data), this.postId).then(
            (response) => {
              this.setLocalData(response);
            }
          );
        }
      } else if (this.flag === "user") {
        if (!this.email) {
          UserService.getModeratorBoard(JSON.stringify(data)).then(
            (response) => {
              // this.postData = response.data;
              window.location.reload();
            },
            (error) => {
              this.content =
                (error.response &&
                  error.response.data &&
                  error.response.data.message) ||
                error.message ||
                error.toString();
            }
          );
        }
      }
    },
  },
};
</script>

<style>
.modal-mask {
  position: absolute;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
  overflow-y: scroll;
  /* max-width: auto !important; */
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 300px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter-from {
  opacity: 0;
}

.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>