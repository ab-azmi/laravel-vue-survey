<template>
  <PageComponent title="Dashboard">
    <div v-if="loading" class="flex justify-center">Loading...</div>
    <div v-else class="gap-5 text-gray-700 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
      <!--      Total Survey-->
      <div class="animate-fade-in-down lg:order-2  order-1"
           style="animation-delay:.1s">
        <h3 class="text-lg w-fit font-semibold mb-3">Total Surveys</h3>
        <div class="bg-white rounded-lg h-[80%] shadow-md p-3 flex flex-col">
          <div class="text-8xl font-semibold flex-1 flex items-center justify-center">
            {{ data.totalSurveys }}
          </div>
        </div>
      </div>
      <!--      End Total Survey-->
      <!--      Total Answers-->
      <div class="animate-fade-in-down  lg:order-4  order-2"
           style="animation-delay: .2s">
        <h3 class="text-lg w-fit font-semibold mb-3">Total Answers</h3>
        <div class="bg-white shadow-md h-[80%] p-3 text-center flex flex-col">
          <div class="text-8xl font-semibold flex-1 flex items-center justify-center">
            {{ data.totalAnswers }}
          </div>
        </div>

      </div>
      <!--      End total answers-->
      <!--      Latest Survey-->
      <div class="animate-fade-in-down lg:order-1 order-3 row-span-2"
           style="animation-delay: .3s">
        <h3 class="text-lg w-fit font-semibold mb-3">Latest Surveys</h3>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
          <div class="absolute w-full flex justify-between">
            <span class="px-3 py-2 text-sm bg-white font-semibold right-0 rounded-tl-lg rounded-br-lg">
              {{ data.latestSurvey.answers }} Answers
            </span>
            <span :class="data.latestSurvey.status ? 'bg-green-500' : 'bg-yellow-600'"
                  class="px-3 py-2 text-sm text-white font-semibold right-0 rounded-tr-lg rounded-bl-lg">
            {{ data.latestSurvey.status ? 'Active' : 'Draft' }}
            </span>
          </div>
          <img v-if="data.latestSurvey.image_url" :src="data.latestSurvey.image_url"
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
            <h3 class="font-semibold text-xl mb-3">{{ data.latestSurvey.title }}</h3>
            <p class="text-slate-400 italic font-base text-sm">
              Created at {{ data.latestSurvey.created_at }}
            </p>
            <p class="text-slate-400 italic font-base text-sm">
              Expire {{ data.latestSurvey.expire_date }}
            </p>
            <p class="text-slate-400 italic font-base text-sm">
              {{ data.latestSurvey.questions }} Questions
            </p>
            <div class="flex justify-between mt-6">
              <router-link :to="{name: 'SurveyView', params: {id:data.latestSurvey.id}}"
                           class="flex py-2 px-4 border bg-indigo-50 font-semibold border-transparent text-indigo-500 text-sm rounded-md
                        hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2
                        focus:ring-offset-2 focus:ring-indigo-500">Edit
              </router-link>
              <button class="flex py-2 px-4 border bg-indigo-50 font-semibold border-transparent text-sm rounded-md text-indigo-500
                        hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2
                        focus:ring-offset-2 focus:ring-indigo-500">
                Show Answers
              </button>
            </div>
          </div>
        </div>
      </div>
      <!--      End Latest Survey-->
      <!--      Latest Answers-->
      <div class="animate-fade-in-down lg:order-3 order-4 row-span-2"
           style="animation-delay: .4s">
        <div class="flex justify-between items-center mb-3 px-2">
          <h3 class="text-lg w-fit font-semibold mb-3">Latest Answers</h3>
          <a class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500
                        hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2
                        focus:ring-offset-2 focus:ring-indigo-500 bg-indigo-50 cursor-pointer">
            Show All
          </a>
        </div>
        <a v-for="(answer, index) of data.latestAnswers"
           :key="answer.id"
           class="block p-2 hover:bg-gray-100/90 bg-white shadow-md p-3 rounded-lg mb-3"
           href="#">
          <div class="font-semibold">{{ answer.survey.title }}</div>
          <p class="text-sm text-slate-400">
            Answered at {{ answer.end_date }}
          </p>
        </a>
      </div>
      <!--      End Latest Answers-->
    </div>
  </PageComponent>
</template>

<script setup>
import PageComponent from '../components/PageComponent.vue';
import {useStore} from 'vuex';
import {computed} from 'vue';

const store = useStore();

const loading = computed(() => store.state.dashboard.loading);
const data = computed(() => store.state.dashboard.data);

store.dispatch('getDashboardData');
</script>

