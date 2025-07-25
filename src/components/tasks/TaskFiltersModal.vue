<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 overflow-hidden">
      <div 
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        @click="closeModal"
      ></div>

      <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
        <div class="w-screen max-w-md transform transition ease-in-out duration-300" :class="isOpen ? 'translate-x-0' : 'translate-x-full'">
          <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
            <div class="px-4 py-6 bg-primary-600 sm:px-6">
              <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-white" id="slide-over-title">
                  Filtros de Tarefas
                </h2>
                <div class="ml-3 h-7 flex items-center">
                  <button
                    @click="closeModal"
                    class="bg-primary-600 rounded-md text-primary-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                  >
                    <span class="sr-only">Fechar painel</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <div class="flex-1 px-4 py-6 sm:px-6">
              <form @submit.prevent="applyFilters" class="space-y-6">
                <div>
                  <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Buscar por título
                  </label>
                  <BaseInput
                    id="title"
                    v-model="filters.title"
                    label="Buscar por título"
                    type="text"
                    placeholder="Digite o título da tarefa"
                  />
                </div>

                <div>
                  <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                  </label>
                  <select
                    id="status"
                    v-model="filters.status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                  >
                    <option value="">Todos os status</option>
                    <option value="pending">Pendente</option>
                    <option value="completed">Concluída</option>
                    <option value="overdue">Atrasada</option>
                  </select>
                </div>

                <div v-if="isAdmin">
                  <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Atribuído para
                  </label>
                  <select
                    id="user_id"
                    v-model="filters.user_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                  >
                    <option value="">Todos os usuários</option>
                    <option v-if="loadingUsers" disabled>Carregando usuários...</option>
                    <option 
                      v-for="user in users" 
                      :key="user.id" 
                      :value="user.id"
                    >
                      {{ user.name }} ({{ user.username }})
                    </option>
                  </select>
                </div>

                <div v-if="hasActiveFilters" class="border-t pt-4">
                  <h3 class="text-sm font-medium text-gray-700 mb-3">Filtros ativos:</h3>
                  <div class="space-y-2">
                    <div v-if="filters.title" class="flex items-center justify-between bg-blue-50 px-3 py-2 rounded-md">
                      <span class="text-sm text-blue-800">Título: "{{ filters.title }}"</span>
                      <button @click="filters.title = ''" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                    <div v-if="filters.status" class="flex items-center justify-between bg-green-50 px-3 py-2 rounded-md">
                      <span class="text-sm text-green-800">Status: {{ getStatusLabel(filters.status) }}</span>
                      <button @click="filters.status = ''" class="text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                    <div v-if="filters.user_id && isAdmin" class="flex items-center justify-between bg-purple-50 px-3 py-2 rounded-md">
                      <span class="text-sm text-purple-800">Usuário: {{ getUserName(filters.user_id) }}</span>
                      <button @click="filters.user_id = ''" class="text-purple-600 hover:text-purple-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <div class="flex-shrink-0 px-4 py-4 border-t border-gray-200 sm:px-6">
              <div class="space-y-3">
                <BaseButton
                  type="button"
                  variant="primary"
                  @click="applyFilters"
                  class="w-full flex items-center justify-center"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                  </svg>
                  <span>Aplicar Filtros</span>
                </BaseButton>
                
                <BaseButton
                  type="button"
                  variant="secondary"
                  @click="clearFilters"
                  class="w-full flex items-center justify-center"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  <span>Limpar Filtros</span>
                </BaseButton>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import BaseInput from '../ui/BaseInput.vue'
import BaseButton from '../ui/BaseButton.vue'
import { usersService, type User } from '../../services/users'

interface Props {
  isOpen: boolean
  isAdmin: boolean
}

interface TaskFilters {
  title: string
  status: string
  user_id: string
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  apply: [filters: TaskFilters]
}>()

const filters = ref<TaskFilters>({
  title: '',
  status: '',
  user_id: ''
})

const users = ref<User[]>([])
const loadingUsers = ref(false)

const hasActiveFilters = computed(() => {
  return filters.value.title !== '' || 
         filters.value.status !== '' || 
         filters.value.user_id !== ''
})

watch(() => props.isOpen, (isOpen) => {
  if (isOpen && props.isAdmin) {
    fetchUsers()
  }
})


const fetchUsers = async (): Promise<void> => {
  loadingUsers.value = true
  try {
    const allUsers = await usersService.getUsers()
    users.value = allUsers.data
  } catch (error) {

    users.value = []
  } finally {
    loadingUsers.value = false
  }
}


const getStatusLabel = (status: string): string => {
  switch (status) {
    case 'completed':
      return 'Concluída'
    case 'pending':
      return 'Pendente'
    case 'overdue':
      return 'Atrasada'
    default:
      return status
  }
}


const getUserName = (userId: string): string => {
  const user = users.value.find(u => u.id.toString() === userId)
  return user ? `${user.name} (${user.username})` : 'Usuário não encontrado'
}


const applyFilters = (): void => {
  emit('apply', { ...filters.value })
  closeModal()
}


const clearFilters = (): void => {
  filters.value = {
    title: '',
    status: '',
    user_id: ''
  }
  emit('apply', { ...filters.value })
  closeModal()
}


const closeModal = (): void => {
  emit('close')
}
</script>