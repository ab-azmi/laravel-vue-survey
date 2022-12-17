<template>
  <PageComponent title="Dashboard">
    <div v-if="loading" class="flex justify-center">Loading...</div>
    <div v-else class="gap-5 text-gray-700 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
      <!--      Total Survey-->
      <div class="animate-fade-in-down bg-white shadow-md p-3 lg:order-2 text-center flex flex-col order-1"
           style="animation-delay:.1s">
        <h3 class="text-2xl font-semibold">Total Surveys</h3>
        <div class="text-8xl font-semibold flex-1 flex items-center justify-center">
          {{ data.totalSurveys }}
        </div>
      </div>
      <!--      End Total Survey-->
      <!--      Total Answers-->
      <div class="animate-fade-in-down bg-white shadow-md p-3 lg:order-4 text-center flex flex-col order-2"
           style="animation-delay: .2s">
        <h3 class="text-2xl font-semibold">Total Answers</h3>
        <div class="text-8xl font-semibold flex-1 flex items-center justify-center">
          {{ data.totalAnswers }}
        </div>
      </div>
      <!--      End total answers-->
      <!--      Latest Survey-->
      <div class="animate-fade-in-down bg-white shadow-md p-4 lg:order-1 order-3 row-span-2"
           style="animation-delay: .3s">
        <h3 class="text-2xl mx-auto w-fit font-semibold">Latest Surveys</h3>
        <img :src="data.latestSurvey.image_url"
             alt=""
             class="w-[240px] mx-auto my-3">
        <h3 class="font-bold text-xl mb-3">{{ data.latestSurvey.title }}</h3>
        <div class="flex justify-between text-sm mb-2">
          <div>Create Date:</div>
          <div>{{ data.latestSurvey.created_at }}</div>
        </div>
        <div class="flex justify-between text-sm mb-2">
          <div>Expire Date:</div>
          <div>{{ data.latestSurvey.expire_date }}</div>
        </div>
        <div class="flex justify-between text-sm mb-2">
          <div>Status:</div>
          <div>{{ data.latestSurvey.status ? 'Active' : 'Draft' }}</div>
        </div>
        <div class="flex justify-between text-sm mb-2">
          <div>Questions:</div>
          <div>{{ data.latestSurvey.questions }}</div>
        </div>
        <div class="flex justify-between text-sm mb-2">
          <div>Answers:</div>
          <div>{{ data.latestSurvey.answers }}</div>
        </div>

        <div class="flex justify-between">
          <router-link :to="{name: 'SurveyView', params: {id:data.latestSurvey.id}}"
                       class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500
                        hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2
                        focus:ring-offset-2 focus:ring-indigo-500">Edit
          </router-link>
          <button class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500
                        hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2
                        focus:ring-offset-2 focus:ring-indigo-500">
            Show Answers
          </button>
        </div>
      </div>
      <!--      End Latest Survey-->
      <!--      Latest Answers-->
      <div class="animate-fade-in-down bg-white shadow-md p-3 lg:order-3 order-4 row-span-2"
           style="animation-delay: .4s">
        <div class="flex justify-between items-center mb-3 px-2">
          <h3 class="text-2xl font-semibold">Latest Answers</h3>
          <a class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500
                        hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2
                        focus:ring-offset-2 focus:ring-indigo-500">
            Show All
          </a>
        </div>
        <a v-for="answer of data.latestAnswers"
           :key="answer.id"
           class="block p-2 hover:bg-gray-100/90"
           href="#">
          <div class="font-semibold">{{ answer.survey.title }}</div>
          <small>
            Answer made at:
            <span class="font-semibold italic">{{ answer.end_date }}</span>
          </small>
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

