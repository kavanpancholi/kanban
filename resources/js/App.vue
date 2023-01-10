<script>
import draggable from 'vuedraggable'
import {VueFinalModal, ModalsContainer} from 'vue-final-modal'

export default {
  data() {
    return {
      columns: null,
      change: [],
      updatedCard: null,
      showCreateModal: false,
      deleteCardModal: false,
      createCard: {
        column_id: null,
        title: null,
        description: null,
      },
      deleteCard: {
        column_id: null,
        card_id: null,
      }
    }
  },
  components: {
    draggable,
    VueFinalModal,
    ModalsContainer
  },

  beforeMount() {
    const columns = axios.get('/api/columns').then((res) => {
      this.columns = res.data.data
    })
  },

  methods: {
    onChange(event) {
      this.updatedCard = event.added?.element || event.removed?.element || event.moved?.element
    },

    onEnd() {
      const column = this.columns.find((column) => column.cards.some((card) => card.id === this.updatedCard.id))
      const orders = column.cards.map((card) => card.id)

      axios.post(`/api/columns/${this.updatedCard.id}/reorder`, {
        column_id: column.id,
        orders: orders
      })
    },

    addNewCard(column) {
      this.showCreateModal = true
      this.createCard.column_id = column
      this.$refs.formInput.focus()
    },

    triggerDeleteCard(column, card) {
      this.deleteCardModal = true
      this.deleteCard.column_id = column
      this.deleteCard.card = card
    },

    submitCard() {
      axios.post('/api/columns', this.card).then((res) => {
        const column = this.columns.find((column) => column.id === this.createCard.column_id)
        column.cards.push({
          id: res.data.id,
          title: res.data.title,
          description: res.data.description,
        })
        this.resetCreateCard()
        this.showCreateModal = false
      })
    },

    resetCreateCard() {
      this.createCard = {
        column_id: null,
        title: null,
        description: null,
      }
    },

    confirmDeleteCard() {
      axios.delete(`/api/columns/${this.deleteCard.column_id}`, {
        data: {card_id: this.deleteCard.card}
      }).then((res) => {
        const column = this.columns.find((column) => column.id === this.deleteCard.column_id)
        column.cards = column.cards.filter((card) => card.id !== this.deleteCard.card)
        this.deleteCardModal = false
      })
    }
  },
}
</script>


<template>
  <div
      class="flex flex-col w-screen h-screen overflow-auto text-gray-700 bg-gradient-to-tr from-blue-200 via-indigo-200 to-pink-200">
    <div class="px-10 mt-6">
      <h1 class="text-2xl font-bold">Team Project Board</h1>
    </div>
    <div class="flex flex-grow px-10 mt-4 space-x-6 overflow-auto">
      <div class="flex flex-col flex-shrink-0 w-72" v-for="column in columns">
        <div class="flex items-center flex-shrink-0 h-10 px-2">
          <span class="block text-sm font-semibold">{{ column.title }}</span>
          <span
              class="flex items-center justify-center w-5 h-5 ml-2 text-sm font-semibold text-indigo-500 bg-white rounded bg-opacity-30">
            {{ column.cards.length }}
          </span>
          <button
              @click="addNewCard(column.id)"
              class="flex items-center justify-center w-6 h-6 ml-auto text-indigo-500 rounded hover:bg-indigo-500 hover:text-indigo-100">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
          </button>
        </div>
        <div class="flex flex-col pb-2 overflow-auto">
          <draggable
              v-if="column.cards.length"
              :list="column.cards"
              itemKey="id"
              class="draggable-list"
              group="kanban"
              @change="onChange"
              @end="onEnd"
          >
            <template #item="{element}">
              <div
                  class="relative flex flex-col items-start p-4 mt-3 bg-white rounded-lg cursor-pointer bg-opacity-90 group hover:bg-opacity-100">
                <div class="md:w-10/12">{{ element.title }}</div>
                <button
                    @click="triggerDeleteCard(column.id, element.id)"
                    class="absolute top-0 right-0 flex items-center justify-center hidden w-5 h-5 mt-3 mr-2 text-gray-500 rounded hover:bg-gray-200 hover:text-gray-700 group-hover:flex">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                       stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                  </svg>
                </button>
              </div>
            </template>
          </draggable>
        </div>
      </div>
      <div class="flex-shrink-0 w-6"></div>
    </div>
  </div>

  <vue-final-modal
      v-model="showCreateModal"
      classes="flex justify-center items-center"
      content-class="relative md:min-w-[400px] flex flex-col max-h-full mx-4 p-4 border rounded bg-white"
  >
    <h2 class="mr-8 text-2xl font-bold mb-5">Add new Card</h2>
    <div class="flex-grow overflow-y-auto">
      <form @submit.prevent="submitCard">
        <div class="flex mb-3 items-center">
          <label class="w-1/3" for="name">Title:</label>
          <input type="text" class="w-2/3 p-2 border border-black" id="name" ref="formInput" autofocus
                 autocomplete="off" v-model="createCard.title" required>
        </div>
        <div class="flex mb-3 mb-10 items-center">
          <label class="w-1/3" for="description">Description:</label>
          <textarea id="description" class="w-2/3 p-2 border border-black" v-model="createCard.description"/>
        </div>
        <div class="flex justify-between">
          <button class="py-2 px-4 border border-black" type="button" @click="showCreateModal = false">Cancel</button>
          <button class="py-2 px-4 border border-black" type="submit">Submit</button>
        </div>
      </form>
    </div>
  </vue-final-modal>

  <vue-final-modal
      v-model="deleteCardModal"
      classes="flex justify-center items-center"
      content-class="relative md:min-w-[400px] flex flex-col max-h-full mx-4 p-4 border rounded bg-white"
  >
    <h2 class="mr-8 text-2xl font-bold mb-5">Delete Card</h2>
    <div class="flex-grow overflow-y-auto">
      <h2 class="font-bold text-xl mb-5">Are you sure want to delete this task?</h2>
      <div class="flex justify-between">
        <button class="py-2 px-4 border border-black" type="button" @click="deleteCardModal = false">Cancel</button>
        <button class="py-2 px-4 border border-black bg-green-300" type="submit" @click="confirmDeleteCard">Confirm</button>
      </div>
    </div>
  </vue-final-modal>
</template>
