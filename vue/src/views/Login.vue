<template>
  <div>
    <div>
      <img
        alt="Your Company"
        class="mx-auto h-12 w-auto"
        src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
      >
      <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
        Sign in to your account
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Or
        {{ ' ' }}
        <router-link
          :to="{name: 'Register'}"
          class="font-medium text-indigo-600 hover:text-indigo-500"
          href="#"
        >
          create new account
        </router-link>
      </p>
    </div>
    <form
      class="mt-8 space-y-6"
      method="POST"
      @submit.prevent="login"
    >
      <Alert v-if="errMsg">
        {{ errMsg }}
        <span
          class="cursor-pointer flex h-8 w-8 justify-center
          items-center transition-colors hover:bg-red-600 rounded-full"
          @click.prevent="errMsg = ''"
        >
          <svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M6 18L18 6M6 6l12 12"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
        </span>
      </Alert>
      <input
        name="remember"
        type="hidden"
        value="true"
      >
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
            class="relative block w-full appearance-none rounded-none rounded-t-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
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
            class="relative block w-full appearance-none rounded-none rounded-b-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
            name="password"
            placeholder="Password"
            required=""
            type="password"
          >
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input
            id="remember-me"
            v-model="user.remember"
            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
            name="remember-me"
            type="checkbox"
          >
          <label
            class="ml-2 block text-sm text-gray-900"
            for="remember-me"
          >Remember me</label>
        </div>
      </div>

      <div>
        <button
          :class="{'hover:bg-indigo-500 cursor-not-allowed': loading}"
          :disabled="loading"
          class="group relative mt-6 flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
          Sign in
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import {LockClosedIcon} from '@heroicons/vue/20/solid'
import store from '../store'
import {useRouter} from 'vue-router'
import {ref} from 'vue'
import Alert from "../components/Alert.vue";

const router = useRouter()

const user = {
  email: '',
  password: '',
  remember: false
}

const errMsg = ref('')
const loading = ref(false)

function login() {
  loading.value = true
  store.dispatch('login', user)
    .then((res) => {
      loading.value = false
      router.push({name: 'Dashboard'})
    })
    .catch((err) => {
      loading.value = false
      errMsg.value = err.response.data.message
    })
}
</script>
