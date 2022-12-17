<template>
  <div>
    <div>
      <img
        alt="Your Company"
        class="mx-auto h-12 w-auto"
        src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
      >
      <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
        Register for free
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Or
        {{ ' ' }}
        <router-link
          :to="{name: 'Login'}"
          class="font-medium text-indigo-600 hover:text-indigo-500"
          href="#"
        >
          login
        </router-link>
      </p>
    </div>
    <form
      class="mt-8 space-y-6"
      method="POST"
      @submit.prevent="register"
    >
      <Alert v-if="Object.keys(errors).length" class="flex-col items-stretch text-sm">
        <div v-for="(field, i) of Object.keys(errors)" :key="i">
          <div v-for="(error, ind) of errors[field] || []" :key="ind">
            {{ error }}
          </div>
        </div>
      </Alert>
      <div class="-space-y-px rounded-md shadow-sm">
        <div>
          <label
            class="sr-only"
            for="name"
          >Full Name</label>
          <input
            id="name"
            v-model="user.name"
            autocomplete="name"
            class="relative block w-full appearance-none rounded-none rounded-t-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
            name="name"
            placeholder="Full Name"
            required=""
            type="text"
          >
        </div>
        <div class="-space-y-px rounded-md shadow-sm">
          <div>
            <label
              class="sr-only"
              for="email-address"
            >Email address</label>
            <input
              id="email-address"
              v-model="user.email"
              autocomplete="email"
              class="relative block w-full appearance-none rounded-none  border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
              name="email"
              placeholder="Email address"
              required=""
              type="email"
            >
          </div>
          <div>
            <label
              class="sr-only"
              for="password"
            >Password</label>
            <input
              id="password"
              v-model="user.password"
              autocomplete="current-password"
              class="relative block w-full appearance-none rounded-none border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
              name="password"
              placeholder="Password"
              required=""
              type="password"
            >
          </div>
          <div>
            <label
              class="sr-only"
              for="password_confirmation"
            >Password Confirm</label>
            <input
              id="password_confirmation"
              v-model="user.password_confirmation"
              autocomplete="current-password_confirmation"
              class="relative block w-full appearance-none rounded-none rounded-b-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
              name="password_confirmation"
              placeholder="Password Confirm"
              required=""
              type="password"
            >
          </div>
        </div>


        <div>
          <button
            :class="{'hover:bg-indigo-500 cursor-not-allowed': loading}"
            :disabled="loading"
            class="group mt-6 relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            type="submit"
          >
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
              <LockClosedIcon
                aria-hidden="true"
                class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
              />
            </span>
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    fill="currentColor"></path>
            </svg>
            Register
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import {LockClosedIcon} from '@heroicons/vue/20/solid'
import store from '../store/index.js';
import {useRouter} from 'vue-router';
import {ref} from 'vue';
import Alert from "../components/Alert.vue";

const router = useRouter();

const errors = ref({})
const loading = ref(false)

const user = {
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
}

function register() {
  loading.value = true
  //dispatch register action
  store.dispatch('register', user)
    .then((res) => {
      loading.value = false
      //redirect to dashboard
      router.push({name: 'Dashboard'})
    })
    .catch(err => {
      loading.value = false
      errors.value = err.response.data.errors
    })
}
</script>
