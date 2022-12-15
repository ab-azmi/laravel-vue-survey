<template>
  <PageComponent>
    <template #header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">
          Surveys
        </h1>
        <router-link
          :to="{name: 'SurveyCreate'}"
          class="py-2 px-3 text-white bg-emerald-500 rounded-md hover:bg-emerald-600"
        >
          Add Survey
          <svg
            class="w-6 h-6 -mt-1 inline-block"
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
        </router-link>
      </div>
    </template>
    <div v-if="surveys.loading"
         class="flex justify-center">Loading...
    </div>
    <div v-else>
      <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
        <SurveyListItem
          v-for="(survey, index) in surveys.data"
          :key="survey.id"
          :style="{animationDelay: `${index * 0.1}s`}"
          :survey="survey"
          class="opacity-0 animate-fade-in-down"
          @delete="deleteSurvey(survey)"
        />
      </div>
      <!--pagination-->
      <!--end pagination-->
      <div class="flex justify-center mt-5">
        <nav aria-label="Pagination"
             class="relative z-0 inline-flex justify-center rounded-md shadow">
          <a v-for="(link, i) of surveys.links"
             :key="i"
             :class="[link.active
              ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
              : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              i === 0 ? 'rounded-l-md' : '',
              i === surveys.links.length - 1 ? 'rounded-r-md' : '',]"
             :disabled="!link.url"
             class="relative inline-flex items-center px-4 py-2 border text-sm
                    font-medium whitespace-nowrap"
             v-html="link.label"
             @click.prevent="getForPage(link)"></a>
        </nav>
      </div>
    </div>
  </PageComponent>
</template>

<script setup>
import PageComponent from '../components/PageComponent.vue';
import store from '../store'
import {computed} from 'vue'
import SurveyListItem from '../components/SurveyListItem.vue'

const surveys = computed(() => store.state.surveys)

store.dispatch('getSurveys')

function deleteSurvey(survey) {
  if (confirm('Yakin??')) {
    store.dispatch('deleteSurvey', survey.id)
      .then(() => {
        store.dispatch('getSurveys')
      })
  }
}

function getForPage(link) {
  if (!link.url || link.active) {
    return;
  }
  store.dispatch('getSurveys', {url: link.url})
}
</script>

