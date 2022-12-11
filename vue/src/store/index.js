import { createStore } from "vuex";
import axiosClient from "../axios.js";

const tmpSurveys = [
  {
    id: 100,
    title: "Survey 1",
    slug: "survey-1",
    status: "draft",
    image: "https://picsum.photos/200/300",
    description: "This is a survey description",
    created_at: "2021-05-01 12:00:00",
    updated_at: "2021-05-01 12:00:00",
    expire_date: "2022-05-01 12:00:00",
    questions: [
      {
        id: 1,
        type: "select",
        question: "What is your favorite color?",
        description: null,
        data: {
          options: [
            { uuid: "1", text: "Red" },
            { uuid: "2", text: "Blue" },
            { uuid: "3", text: "Yellow" },
          ],
        },
      },
    ],
  },
];

const store = createStore({
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem("TOKEN"),
    },
    surveys: [...tmpSurveys],
    questionTypes: ["text", "select", "radio", "checkbox", "textarea"],
  },
  mutations: {
    logout: (state) => {
      state.user.data = {};
      state.user.token = null;
    },
    setUser: (state, user) => {
      //set user data and token to state
      state.user.data = user.user;
      state.user.token = user.token;
      //set token to local storage
      sessionStorage.setItem("TOKEN", user.token);
    },
  },
  actions: {
    register({ commit }, user) {
      return axiosClient.post("/register", user).then(({ data }) => {
        commit("setUser", data);
        return data;
      });
    },
    login({ commit }, user) {
      return axiosClient.post("/login", user).then(({ data }) => {
        commit("setUser", data);
        return data;
      });
    },
    logout({ commit }) {
      return axiosClient.post("/logout").then((res) => {
        commit("logout");
        return res;
      });
    },
  },
  getters: {},
  modules: {},
});

export default store;
