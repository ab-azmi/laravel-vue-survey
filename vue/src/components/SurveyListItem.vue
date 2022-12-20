<template>
  <!--  <div-->
  <!--    class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[470px]"-->
  <!--  >-->
  <!--    <img-->
  <!--      :src="survey.image_url"-->
  <!--      alt=""-->
  <!--      class="w-full h-48 object-cover"-->
  <!--    >-->
  <!--    <h4 class="mt-4 text-lg font-bold">-->
  <!--      {{ survey.title }}-->
  <!--    </h4>-->
  <!--    <div-->
  <!--      class="overflow-hidden flex-1"-->
  <!--      v-html="survey.description"-->
  <!--    />-->
  <!--    <div class="flex justify-between gap-x-3 items-center mt-3">-->
  <!--      <router-link-->
  <!--        :to="{name: 'SurveyView', params: {id: survey.id}}"-->
  <!--        class="flex py-2 px-4 border border-transparent text-sm text-white rounded-md bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"-->
  <!--      >-->
  <!--        Edit-->
  <!--        <svg-->
  <!--          class="w-4 h-4 ml-2"-->
  <!--          fill="none"-->
  <!--          stroke="currentColor"-->
  <!--          stroke-width="1.5"-->
  <!--          viewBox="0 0 24 24"-->
  <!--          xmlns="http://www.w3.org/2000/svg"-->
  <!--        >-->
  <!--          <path-->
  <!--            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"-->
  <!--            stroke-linecap="round"-->
  <!--            stroke-linejoin="round"-->
  <!--          />-->
  <!--        </svg>-->
  <!--      </router-link>-->
  <!--      <a :href="`/view/survey/${survey.slug}`"-->
  <!--         class="h-8 w-8 flex items-center justify-center rounded-full-->
  <!--            border border-transparent text-sm text-indigo-500 focus:ring-2-->
  <!--             focus:ring-offset-2 focus:ring-indigo-500"-->
  <!--         target="_blank">-->
  <!--        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"-->
  <!--             xmlns="http://www.w3.org/2000/svg">-->
  <!--          <path-->
  <!--            d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"-->
  <!--            stroke-linecap="round"-->
  <!--            stroke-linejoin="round"/>-->
  <!--        </svg>-->
  <!--      </a>-->
  <!--      <button-->
  <!--        v-if="survey.id"-->
  <!--        class="flex h-8 w-8 border border-transparent text-sm text-red-500 hover:text-red-700"-->
  <!--        type="button"-->
  <!--        @click="emit('delete', survey)"-->
  <!--      >-->
  <!--        <svg-->
  <!--          class="w-6 h-6"-->
  <!--          fill="none"-->
  <!--          stroke="currentColor"-->
  <!--          stroke-width="1.5"-->
  <!--          viewBox="0 0 24 24"-->
  <!--          xmlns="http://www.w3.org/2000/svg"-->
  <!--        >-->
  <!--          <path-->
  <!--            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"-->
  <!--            stroke-linecap="round"-->
  <!--            stroke-linejoin="round"-->
  <!--          />-->
  <!--        </svg>-->
  <!--      </button>-->
  <!--    </div>-->
  <!--  </div>-->

  <div class="animate-fade-in-down h-fit row-span-2"
       style="animation-delay: .3s">
    <h3 class="text-lg w-fit font-semibold mb-3">Latest Surveys</h3>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <div class="absolute w-full flex justify-end">
        <span :class="survey.status ? 'bg-green-500' : 'bg-yellow-600'"
              class="px-3 py-2 text-sm text-white font-semibold right-0 rounded-tr-lg rounded-bl-lg">
            {{ survey.status ? 'Active' : 'Draft' }}
            </span>
      </div>
      <img v-if="survey.image_url" :src="survey.image_url"
           alt=""
           class="w-full mx-auto">
      <div v-else class="h-60 flex justify-center items-center text-slate-300">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5"
             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
            stroke-linecap="round"
            stroke-linejoin="round"/>
        </svg>
      </div>
      <div class="bg-white rounded-t-2xl -mt-5 relative px-5 py-5">
        <h3 class="font-semibold text-xl ">{{ survey.title }}</h3>
        <p class="text-slate-400 italic  text-sm">
          Created at {{ survey.created_at }}
        </p>
        <p class="mt-3 text-sm text-slate-400" v-html="survey.description"></p>
        <div class="flex justify-between mt-6">
          <router-link
            :to="{name: 'SurveyView', params: {id: survey.id}}"
            class="flex py-2 px-4 border border-transparent text-sm text-white rounded-md bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Edit
            <svg
              class="w-4 h-4 ml-2"
              fill="none"
              stroke="currentColor"
              stroke-width="1.5"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </router-link>
          <a :href="`/view/survey/${survey.slug}`"
             class="h-8 w-8 flex items-center justify-center rounded-full
                        border border-transparent text-sm text-indigo-500 focus:ring-2
                         focus:ring-offset-2 focus:ring-indigo-500"
             target="_blank">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
              <path
                d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"
                stroke-linecap="round"
                stroke-linejoin="round"/>
            </svg>
          </a>
          <button
            v-if="survey.id"
            class="flex h-8 w-8 border border-transparent text-sm text-red-500 hover:text-red-700"
            type="button"
            @click="emit('delete', survey)"
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
                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const {survey} = defineProps({
  survey: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['delete'])
</script>

<style scoped>

</style>
