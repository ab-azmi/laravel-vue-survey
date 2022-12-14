import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import DefaultLayout from "../components/DefaultLayout.vue";
import Survey from "../views/Survey.vue";
import SurveyView from "../views/SurveyView.vue";
import AuthLayout from "../components/AuthLayout.vue";
import SurveyPublicView from "../views/SurveyPublicView.vue";
import store from "../store";

const routes = [
  {
    path: "/",
    redirect: "/dashboard",
    component: DefaultLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: "/dashboard",
        name: "Dashboard",
        component: Dashboard,
      },
      {
        path: "/surveys",
        name: "Surveys",
        component: Survey,
      },
      {
        path: "/surveys/create",
        name: "SurveyCreate",
        component: SurveyView,
      },
      {
        path: "surveys/:id",
        name: "SurveyView",
        component: SurveyView,
      },
    ],
  },
  {
    path: "/auth",
    redirect: "/login",
    name: "Auth",
    component: AuthLayout,
    meta: { isGuest: true },
    children: [
      {
        path: "/login",
        name: "Login",
        component: Login,
      },
      {
        path: "/register",
        name: "Register",
        component: Register,
      },
    ],
  },
  {
    path: "/view/survey/:slug",
    name: "SurveyPublicView",
    component: SurveyPublicView,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.state.user.token) {
    next("/login");
  } else if (store.state.user.token && to.meta.isGuest) {
    next("/dashboard");
  } else {
    next();
  }
});

export default router;
