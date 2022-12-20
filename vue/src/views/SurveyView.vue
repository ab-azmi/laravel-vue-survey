<template>
  <PageComponent>
    <template #header>
      <div class="flex justify-between">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">
          {{ route.params.id ? model.title : 'Create a survey' }}
        </h1>
        <button v-if="route.params.id"
                class="py-2 px-3 text-white bg-red-500 rounded-md
                    hover:bg-red-600"
                type="button"
                @click="deleteSurvey()">
          Delete Survey
        </button>
      </div>
    </template>
    <div v-if="surveyLoading"
         class="flex justify-center">
      Loading...
    </div>
    <form class="animate-fade-in-down" @submit.prevent="saveSurvey">
      <div class=" sm:overflow-hidden">
        <!-- Survey Fields -->
        <div class="px-4 sm:rounded-md py-5 shadow bg-white space-y-6 sm:p-6">
          <!-- Image -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700"
              for=""
            >
              Image
            </label>
            <div class="mt-1 flex items-center">
              <img
                v-if="model.image_url"
                :alt="model.title"
                :src="model.image_url"
                class="w-64 h-48 object-cover"
              >
              <span
                v-else
                class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100"
              >
                <svg
                  class="w-[70%] h-[70%] text-gray-300"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.5"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </span>
              <button
                class="relative overflow-hidden ml-5 bg-white py-2 px-3 border h-fit
                border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700
                hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                type="button"
              >
                <input
                  id=""
                  class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer"
                  name=""
                  type="file"
                  @change="onImageChoose"
                >
                Change
              </button>
            </div>
          </div>
          <!-- End Image -->
          <!-- Title -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700"
              for="title"
            >
              Title
            </label>
            <input
              id="title"
              v-model="model.title"
              autocomplete="survey_title"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              name="title"
              type="text"
            >
          </div>
          <!-- End Title -->
          <!-- Description -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700"
              for="description"
            >
              Description
            </label>
            <textarea
              id="description"
              v-model="model.description"
              autocomplete="model_description"
              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md"
              cols="30"
              name="description"
              placeholder="Description"
              rows="5"
            />
          </div>
          <!-- End Description -->
          <!-- Expire Date -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700"
              for="expire_date"
            >
              Expire Date
            </label>
            <input
              id="expire_date"
              v-model="model.expire_date"
              autocomplete="survey_expire_date"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              name="expire_date"
              type="date"
            >
          </div>
          <!-- End Expire Date -->
          <!-- Status -->
          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input
                id="status"
                v-model="model.status"
                autocomplete="survey_status"
                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                name="status"
                type="checkbox"
              >
            </div>
            <div class="ml-3 text-sm">
              <label
                class="block text-sm font-medium text-gray-700"
                for="expire_date"
              >
                Active
              </label>
            </div>
          </div>
          <!-- End Status -->
          <!-- Submit button -->
          <div class="flex justify-end">
            <button
              class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              type="submit"
            >
              Save
            </button>
          </div>
          <!-- End Submit button -->
        </div>
        <!-- End Survey Fields -->

        <!-- Survey questions -->
        <div class="px-4 py-5 sm:rounded-md shadow bg-white mt-5 space-y-6 sm:p-6">
          <h3 class="text-2xl font-semibold flex items-center justify-between">
            Questions
            <button
              class="flex items-center text-sm py-2 px-4 rounded text-white bg-gray-600 hover:bg-gray-700"
              type="button"
              @click="addQuestion()"
            >
              Add Question
              <svg
                class="w-5 h-5 ml-2 inline-block"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M12 4.5v15m7.5-7.5h-15"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </button>
          </h3>
          <div v-if="!model.questions.length" class="text-center text-gray-600">
            You dont have any questions
          </div>
          <div v-for="(question, index) in model.questions" :key="question.id">
            <QuestionEditor
              :index="index"
              :question="question"
              @addQuestion="addQuestion"
              @change="questionChange"
              @deleteQuestion="deleteQuestion"/>
          </div>
        </div>
        <!-- End Survey questions -->

      </div>

    </form>
  </PageComponent>
</template>

<script setup>
import PageComponent from '../components/PageComponent.vue'
import QuestionEditor from "../components/editor/QuestionEditor.vue";
import {ref, watch, computed} from 'vue'
import store from '../store'
import {useRoute, useRouter} from 'vue-router'
import {v4 as uuidv4} from 'uuid'

const route = useRoute()
const router = useRouter()

const surveyLoading = computed(() => store.state.currentSurvey.loading)

let model = ref({
  title: '',
  status: false,
  description: null,
  image_url: null,
  expire_date: null,
  questions: [],
})

if (route.params.id) {
  //if there is param id, then find that survey from store
  store.dispatch('getSurvey', route.params.id)
}

//watch for changes in current survey
watch(() => store.state.currentSurvey.data,
  (newVal, oldVal) => {
    model.value = {
      ...JSON.parse(JSON.stringify(newVal)),
      status: newVal.status !== 'draft',
    };
  });

function onImageChoose(ev) {
  const file = ev.target.files[0];
  const reader = new FileReader();
  reader.onload = () => {
    //the field to send on backend and apply validator
    model.value.image = reader.result;

    //the field to display here
    model.value.image_url = reader.result;
  }
  reader.readAsDataURL(file)
}

function addQuestion(index) {
  const newQuestion = {
    id: uuidv4(),
    type: 'text',
    question: '',
    description: null,
    data: {}
  }
  model.value.questions.splice(index, 0, newQuestion);
}

function deleteQuestion(question) {
  //whenever listening to deleteQuestion event, this function will be called
  model.value.questions = model.value.questions.filter(
    (q) => q !== question
  )
}

function questionChange(question) {
  model.value.questions = model.value.questions.map(q => {
    if (q.id === question.id) {
      return JSON.parse(JSON.stringify(question));
    }
    return q;
  })
}

function saveSurvey() {

  store.dispatch('saveSurvey', model.value)
    .then(({data}) => {
      store.commit('notify', {
        type: 'success',
        message: 'Survey saved successfully'
      })
      router.push({name: 'SurveyView', params: {id: data.data.id}});
    })
    .catch((err) => {
      console.log(err);
    });
}

function deleteSurvey() {
  if (confirm('Are you sure?')) {
    store.dispatch('deleteSurvey', model.value.id)
      .then(() => {
        router.push({name: 'Surveys'});
      })
      .catch((err) => {
        console.log(err);
      });
  }
}

</script>
