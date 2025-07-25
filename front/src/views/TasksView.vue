<template>
  <AppLayout>
    <div class="space-y-6">
      <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="px-6 py-4">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Lista de tarefas</h1>
              <p class="mt-1 text-sm text-gray-600">Gerencie e visualize todas as suas tarefas</p>
            </div>
            <div class="flex items-center space-x-3">
              <button
                @click="openFiltersModal"
                :class="[
                  'relative inline-flex items-center px-4 py-2 text-sm font-medium transition-all duration-200 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2',
                  hasActiveFilters 
                    ? 'text-primary-700 bg-primary-50 border-primary-200 hover:bg-primary-100 focus:ring-primary-500' 
                    : 'text-gray-700 bg-white border-gray-300 hover:bg-gray-50 focus:ring-primary-500'
                ]"
              >
                <svg 
                  class="w-4 h-4 mr-2 transition-colors duration-200" 
                  :class="hasActiveFilters ? 'text-primary-600' : 'text-gray-500'"
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span class="font-medium">Filtros</span>
                
                <span 
                  v-if="hasActiveFilters" 
                  class="ml-2 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-primary-600 rounded-full animate-pulse"
                >
                  {{ activeFiltersCount }}
                </span>
                
                <span 
                  v-if="hasActiveFilters"
                  class="absolute -top-1 -right-1 w-3 h-3 bg-primary-500 border-2 border-white rounded-full"
                ></span>
              </button>
              
              <button
                @click="openCreateModal"
                v-if="isAdmin"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 border border-transparent rounded-md bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Tarefa
              </button>
            </div>
          </div>
        </div>
      </div>

      <TaskTable 
        :tasks="tasks" 
        :loading="loading" 
        :error="error"
        :is-admin="isAdmin"
        @retry="retryFetchTasks"
        @refresh="handleRefresh"
        @edit="handleEditTask"
        @delete="handleDeleteTask"
      />

      <TaskEditModal
        :is-open="isEditModalOpen"
        :task="selectedTask"
        @close="closeEditModal"
        @success="handleUpdateTaskSuccess"
      />

      <TaskCreateModal
        :is-open="isCreateModalOpen"
        @close="closeCreateModal"
        @success="handleCreateTaskSuccess"
      />

      <TaskDeleteModal
        :is-open="isDeleteModalOpen"
        :task="selectedTask"
        @close="closeDeleteModal"
        @delete="handleDeleteTaskConfirm"
      />

      <TaskFiltersModal
        :is-open="isFiltersModalOpen"
        :is-admin="isAdmin"
        @close="closeFiltersModal"
        @apply="handleFiltersApply"
      />
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import AppLayout from '../components/layout/AppLayout.vue'
import TaskTable from '../components/tasks/TaskTable.vue'
import TaskEditModal from '../components/tasks/TaskEditModal.vue'
import TaskCreateModal from '../components/tasks/TaskCreateModal.vue'
import TaskDeleteModal from '../components/tasks/TaskDeleteModal.vue'
import TaskFiltersModal from '../components/tasks/TaskFiltersModal.vue'
import { tasksService } from '../services/tasks'
import { getErrorHandler } from '../utils/errorHandler'
import type { Task } from '../interfaces/task'

import type { ApiError } from '../utils/errorHandler'
import { getCurrentUser } from '../utils/auth'
import { authService } from '../services/auth'

const isAdmin = ref(false)
const tasks = ref<Task[]>([])
const loading = ref(false)
const error = ref<string | null>(null)

const isEditModalOpen = ref(false)
const isCreateModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const isFiltersModalOpen = ref(false)
const selectedTask = ref<Task | null>(null)

const currentFilters = ref({
  title: '',
  status: '',
  user_id: ''
})

const hasActiveFilters = computed(() => {
  return currentFilters.value.title !== '' || 
         currentFilters.value.status !== '' || 
         currentFilters.value.user_id !== ''
})

const activeFiltersCount = computed(() => {
  let count = 0
  if (currentFilters.value.title !== '') count++
  if (currentFilters.value.status !== '') count++
  if (currentFilters.value.user_id !== '') count++
  return count
})

const fetchTasks = async (): Promise<void> => {
  loading.value = true
  error.value = null
  
  try {
    tasks.value = await tasksService.getTasks()
  } catch (err: any) {
    const errorHandler = getErrorHandler()
    
    const apiError = err as ApiError
    errorHandler.logError(err, 'TasksView.fetchTasks')
    
    if (apiError.code === 'AUTHENTICATION_ERROR') {
      return
    }
    
    error.value = apiError.message || 'Falha ao carregar tarefas. Tente novamente.'
  } finally {
    loading.value = false
  }
}

const handleRefresh = (): void => {
  window.location.reload()
}

const openCreateModal = (): void => {
  isCreateModalOpen.value = true
}

const closeCreateModal = (): void => {
  isCreateModalOpen.value = false
}

const handleEditTask = (task: Task): void => {
  selectedTask.value = task
  isEditModalOpen.value = true
}

const closeEditModal = (): void => {
  isEditModalOpen.value = false
  selectedTask.value = null
}

const handleDeleteTask = (task: Task): void => {
  selectedTask.value = task
  isDeleteModalOpen.value = true
}

const closeDeleteModal = (): void => {
  isDeleteModalOpen.value = false
  selectedTask.value = null
}



const handleCreateTaskSuccess = async (): Promise<void> => {
  closeCreateModal()
  if (hasActiveFilters.value) {
    await fetchTasksWithFilters()
  } else {
    await fetchTasks()
  }
}

const handleUpdateTaskSuccess = async (): Promise<void> => {
  closeEditModal()
  if (hasActiveFilters.value) {
    await fetchTasksWithFilters()
  } else {
    await fetchTasks()
  }
}

const handleDeleteTaskConfirm = async (taskId: number): Promise<void> => {
  try {
    await tasksService.deleteTask(taskId)
    
    tasks.value = tasks.value.filter(t => t.id !== taskId)
    closeDeleteModal()
  } catch (err: any) {
    const errorHandler = getErrorHandler()
    const apiError = err as ApiError
    
    errorHandler.logError(err, 'TasksView.handleDeleteTaskConfirm')
    
    throw apiError
  }
}

const openFiltersModal = (): void => {
  isFiltersModalOpen.value = true
}

const closeFiltersModal = (): void => {
  isFiltersModalOpen.value = false
}

const handleFiltersApply = async (filters: any): Promise<void> => {
  currentFilters.value = { ...filters }
  await fetchTasksWithFilters()
}

const fetchTasksWithFilters = async (): Promise<void> => {
  loading.value = true
  error.value = null
  
  try {
    const filtersData = {
      filters: {
        title: currentFilters.value.title || undefined,
        status: currentFilters.value.status || undefined,
        user_id: currentFilters.value.user_id ? parseInt(currentFilters.value.user_id) : undefined
      }
    }
    
    Object.keys(filtersData.filters).forEach(key => {
      if (filtersData.filters[key as keyof typeof filtersData.filters] === undefined) {
        delete filtersData.filters[key as keyof typeof filtersData.filters]
      }
    })
    
    tasks.value = await tasksService.getTasksWithFilters(filtersData)
  } catch (err: any) {
    const errorHandler = getErrorHandler()
    const apiError = err as ApiError
    
    errorHandler.logError(err, 'TasksView.fetchTasksWithFilters')
    
    if (apiError.code === 'AUTHENTICATION_ERROR') {
      return
    }
    
    error.value = apiError.message || 'Falha ao carregar tarefas. Tente novamente.'
  } finally {
    loading.value = false
  }
}

const retryFetchTasks = async (): Promise<void> => {
  if (hasActiveFilters.value) {
    await fetchTasksWithFilters()
  } else {
    await fetchTasks()
  }
}

onMounted(async () => {
  let currentUser = getCurrentUser()
  if (!currentUser) {

    try {
      currentUser = await authService.getCurrentUser()

    } catch (error) {

    }
  } else {

  }
  
  if (currentUser) {
    isAdmin.value = currentUser.is_admin === true || currentUser.is_admin === 1

    
    if (isAdmin.value) {
      currentFilters.value = {
        title: '',
        status: '',
        user_id: currentUser.id.toString()
      }

      await fetchTasksWithFilters()
    } else {
      await fetchTasks()
    }
  } else {

    isAdmin.value = false
    await fetchTasks()
  }
})
</script>