<template>
  <div class="flex items-center justify-between">
    <h3 class="text-lg font-bold">
      {{ index + 1 }}. {{ model.question }}
    </h3>
    <div class="flex items-center gap-x-3">
      <!--Add new Question-->
      <button
        class="flex items-center text-sm py-2 px-4 rounded text-white bg-gray-600 hover:bg-gray-700"
        type="button"
        @click="addQuestion()"
      >
        Add
        <svg class="w-5 ml-2 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
             xmlns="http://www.w3.org/2000/svg">
          <path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
      <!--End Add new Question-->
      <!--Delete Question-->
      <button
        class="flex items-center text-sm py-2 px-4 rounded text-white bg-red-600 hover:bg-red-700"
        type="button"
        @click="deleteQuestion()"
      >
        Delete
        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
             xmlns="http://www.w3.org/2000/svg">
          <path
            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
            stroke-linecap="round"
            stroke-linejoin="round"/>
        </svg>

      </button>
      <!--End Delete Question-->
    </div>
  </div>
  <div class="grid gap-3 grid-cols-12">
    <!--Question-->
    <div class="mt-3 col-span-9">
      <label :for="'question_text_'+model.data"
             class="block text-sm font-medium text-gray-700">
        Question Text
      </label>
      <input
        :id="'question_text_'+model.data"
        v-model="model.question"
        :name="'question_text_'+model.data"
        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500
        block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
        type="text"
        @change="dataChange"
      >
    </div>
    <!--End Question-->
    <!--Question Type-->
    <div class="mt-3 col-span-3">
      <label class="block text-sm font-medium text-gray-700"
             for="question_type">
        Select Question Type
      </label>
      <select id="question_type"
              v-model="model.type"
              class="mt-1 block w-full py-2 px-3 border border-gray=300
              bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500
              focus:border-indigo-500 sm:text-sm"
              name="question_type"
              @change="typeChange">
        >
        <option v-for="type in questionTypes" :key="type"
                :value="type">
          {{ upperCaseFirst(type) }}
        </option>
      </select>
    </div>
    <!--End Question Type-->
  </div>
  <!--Question Description-->
  <div class="mt-3 col-span-9">
    <label :for="'question_description_'+model.id"
           class="block text-sm font-medium text-gray-700">
      Description
    </label>
    <textarea
      :id="'question_description_'+model.id"
      :name="'question_description_'+model.id"
      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500
      block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" cols="30"
      rows="5"
    ></textarea>
  </div>
  <!--End Question Description-->
  <!--  Data-->
  <div>
    <div v-if="shouldHaveOptions()" class="mt-4">
      <h4 class="text-sm font-semibold mb-1 flex justify-between
             items-center">
        Options
        <!--      Add new options-->
        <button
          class="flex items-center text-xs py-2 px-4 rounded text-white
               bg-gray-600 hoverLbg-gray-700"
          type="button"
          @click="addOptions()">
          Add Option
          <svg class="w-5 ml-2 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
               xmlns="http://www.w3.org/2000/svg">
            <path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <!--      End Add new options-->
      </h4>
      <div v-if="!model.data.options.length"
           class="text-xs text-gray-600 text-center py-3">
        You dont have any options defined
      </div>
      <!--      Option List-->
      <div v-for="(option, index) in model.data.options"
           :key="option.uuid"
           class="flex items-center mb-1">
        <span class="w-6 text-sm">{{ index + 1 }}</span>
        <input v-model="option.text"
               class="w-full rounded-md py-1 px-2 border border-gray-300
                      focus:border-indigo-500"
               type="text"
               @change="dataChange">
        <button
          class="h-8 w-8 rounded-full ml-3 flex items-center justify-center
               border border-transparent transition-colors text-red-700 hover:bg-red-500 hover:text-white"
          type="button"
          @click="removeOption(option)">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
              stroke-linecap="round"
              stroke-linejoin="round"/>
          </svg>
        </button>
      </div>
      <!--      End Option List-->
    </div>
  </div>
  <!--  End Data-->
  <hr class="my-4">
</template>

<script setup>
import {ref, computed} from "vue";
import store from '../../store';
import {v4 as uuidv4} from 'uuid';

const props = defineProps({
  question: Object,
  index: Number,
})
const emit = defineEmits(['change', 'addQuestion', 'deleteQuestion'])

//recreate the whole question data to avoid unintentional reference change
const model = ref(JSON.parse(JSON.stringify(props.question)))

const questionTypes = computed(() => store.state.questionTypes)

function upperCaseFirst(str) {
  return str.charAt(0).toUpperCase() + str.slice(1)
}

//check if the question type should have options
function shouldHaveOptions() {
  return ['radio', 'checkbox', 'select'].includes(model.value.type)
}

function getOptions() {
  return model.value.data.options;
}

function setOptions(options) {
  model.value.data.options = options;
}

function addOptions() {
  setOptions([
    ...getOptions(),
    {uuid: uuidv4(), text: ''}
  ]);
  dataChange();
}

function removeOption(op) {
  setOptions(getOptions().filter(opt => opt !== op));
  dataChange();
}

function typeChange() {
  if (shouldHaveOptions()) {
    setOptions(getOptions() || [])
  }
  dataChange();
}

function dataChange() {
  //create clone to avoid reference change
  const data = JSON.parse(JSON.stringify(model.value))

  if (!shouldHaveOptions()) {
    //delete options if questions type changed
    //and do not require any options
    delete data.data.options;
  }

  emit('change', data);
}

function addQuestion() {
  emit('addQuestion', props.index + 1);
}

function deleteQuestion() {
  emit('deleteQuestion', props.question);
}
</script>

<style scoped>

</style>
