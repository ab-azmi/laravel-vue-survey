<template>
  <div class="py-5 px-8">
    <div v-if="loading" class="flex justify-center">Loading...</div>
    <form v-else
          class="container mx-auto"
          @submit.prevent="submitSurvey">
      <div class="grid grid-cols-6 items-center">
        <div class="mr-4">
          <img :src="survey.image_url"
               alt="Survey image">
        </div>
        <div class="col-span-5">
          <h1 class="text-3xl mb-3">{{ survey.title }}</h1>
          <p class="text-gray-500 text-sm" v-html="survey.description"></p>
        </div>
      </div>

      <div v-if="surveyFinished"
           class="mt-10 py-8 px-6 bg-emerald-400 text-white w-[600px] mx-auto">
        <div class="text-xl mb-3 font-semibold">
          Thank you for your participation!
        </div>
        <button
          class="inline-flex justify-center py-2 px-4 border border-transparent
               shadow text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          @click="submitAnotherResponse">
          Submit another response
        </button>
      </div>

      <div v-else>
        <hr class="my-3">
        <div v-for="(question, ind) of survey.questions"
             :key="question.id">
          <QuestionViewer
            v-model="answers[question.id]"
            :index="ind"
            :question="question"/>
        </div>

        <button class="inline-flex justify-center py-2 px-4 border border-transparent
          shadow text-sm font-medium rounded-md text-white bg-indigo-600 hoverLbg-indigo-600
          hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2
          focus:ring-indigo-500"
                type="submit">
          Submit
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import QuestionViewer from "../components/viewer/QuestionViewer.vue";
import {computed, ref} from 'vue';
import {useRoute} from 'vue-router';
import {useStore} from 'vuex';

const route = useRoute();
const store = useStore();

const loading = computed(() => store.state.currentSurvey.loading)
const survey = computed(() => store.state.currentSurvey.data)

const surveyFinished = ref(false)
const answers = ref({})

store.dispatch('getSurveyBySlug', route.params.slug)

function submitSurvey() {
  console.log(JSON.stringify(answers.value, undefined, 2))
  store.dispatch('saveSurveyAnswer', {
    surveyId: survey.value.id,
    answers: answers.value
  })
    .then(() => {
      surveyFinished.value = true
    })
}

function submitAnotherResponse() {
  surveyFinished.value = false
  answers.value = {}
}
</script>

<style scoped>

</style>
