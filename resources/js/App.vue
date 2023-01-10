<script>
import draggable from 'vuedraggable'

export default {
  data() {
    return {
      columns: null,
      change: [],
      updatedCard: null,
      changeHistory: [],
    }
  },
  components: {
    draggable,
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
          <span class="flex items-center justify-center w-5 h-5 ml-2 text-sm font-semibold text-indigo-500 bg-white rounded bg-opacity-30">
            {{ column.cards.length }}
          </span>
          <button
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
              <div class="md:flex justify-between items-center p-3 bg-white mb-2">
                <div class="md:w-10/12">{{ element.title }}</div>
              </div>
            </template>
          </draggable>
        </div>
      </div>
      <div class="flex-shrink-0 w-6"></div>
    </div>
  </div>
</template>
