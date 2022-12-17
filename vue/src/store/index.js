import { createStore } from "vuex";
import axiosClient from "../axios.js";

const store = createStore({
  // STATE
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem("TOKEN"),
    },
    currentSurvey: {
      loading: false,
      data: {},
    },
    surveys: {
      loading: false,
      links: {},
      data: [],
    },
    questionTypes: ["text", "select", "radio", "checkbox", "textarea"],
    notification: {
      show: false,
      message: null,
      type: null,
    },
    dashboard: {
      loading: false,
      data: {},
    },
  },
  // MUTATIONS
  mutations: {
    logout: (state) => {
      state.user.data = {};
      state.user.token = null;
      sessionStorage.removeItem("TOKEN");
    },
    setUser: (state, user) => {
      //set user data and token to state
      state.user.data = user.user;
      state.user.token = user.token;
      //set token to local storage
      sessionStorage.setItem("TOKEN", user.token);
    },

    setCurrentSurvey: (state, survey) => {
      state.currentSurvey.data = survey.data;
    },
    setCurrentSurveyLoading: (state, loading) => {
      state.currentSurvey.loading = loading;
    },
    setSurveys: (state, surveys) => {
      state.surveys.links = surveys.meta.links;
      state.surveys.data = surveys.data;
    },
    setSurveysLoading: (state, loading) => {
      state.surveys.loading = loading;
    },
    notify: (state, { message, type }) => {
      state.notification.show = true;
      state.notification.message = message;
      state.notification.type = type;
      setTimeout(() => {
        state.notification.show = false;
      }, 3000);
    },
    setDashboardLoading: (state, loading) => {
      state.dashboard.loading = loading;
    },
    setDashboardData: (state, data) => {
      state.dashboard.data = data;
    },
  },
  // ACTIONS
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
    saveSurvey({ commit }, survey) {
      delete survey.image_url;
      let response;
      if (survey.id) {
        //updating survey
        response = axiosClient
          .put(`/survey/${survey.id}`, survey)
          .then((res) => {
            commit("setCurrentSurvey", res.data);
            return res;
          })
          .catch((err) => {
            console.log(err);
          });
      } else {
        //creating survey
        response = axiosClient
          .post("/survey", survey)
          .then((res) => {
            commit("setCurrentSurvey", res.data);
            return res;
          })
          .catch((err) => {
            console.log(err);
          });
      }

      return response;
    },
    getSurvey({ commit }, id) {
      commit("setCurrentSurveyLoading", true);
      return axiosClient
        .get(`/survey/${id}`)
        .then((res) => {
          commit("setCurrentSurvey", res.data);
          commit("setCurrentSurveyLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentSurveyLoading", false);
          throw err;
        });
    },
    deleteSurvey({ commit }, id) {
      return axiosClient.delete(`/survey/${id}`);
    },
    getSurveys({ commit }, { url = null } = {}) {
      url = url || "/survey";

      commit("setSurveysLoading", true);
      return axiosClient.get(url).then((res) => {
        commit("setSurveys", res.data);
        commit("setSurveysLoading", false);
        return res;
      });
    },
    getSurveyBySlug({ commit }, slug) {
      commit("setCurrentSurveyLoading", true);
      return axiosClient
        .get(`/survey-by-slug/${slug}`)
        .then((res) => {
          commit("setCurrentSurvey", res.data);
          commit("setCurrentSurveyLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentSurveyLoading", false);
          throw err;
        });
    },
    saveSurveyAnswer({ commit }, { surveyId, answers }) {
      return axiosClient.post(`/survey/${surveyId}/answer`, { answers });
    },
    getDashboardData({ commit }) {
      commit("setDashboardLoading", true);
      return axiosClient
        .get("/dashboard")
        .then((res) => {
          commit("setDashboardData", res.data);
          commit("setDashboardLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setDashboardLoading", false);
          return err;
        });
    },
  },
  getters: {},
  modules: {},
});

export default store;
