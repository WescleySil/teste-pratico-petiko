<script setup lang="ts">
import { ref, watch } from 'vue'
import BaseInput from '../ui/BaseInput.vue'
import BaseButton from '../ui/BaseButton.vue'
import type { Task, TaskStatus } from '../../interfaces/task'
import { tasksService } from '../../services/tasks'
import { getErrorHandler } from '../../utils/errorHandler'
import type { ApiError } from '../../utils/errorHandler'

interface Props {
  isOpen: boolean
  task: Task | null
}

interface UpdateTaskData {
  title: string
  description: string | null
  status: TaskStatus
  due_date: string
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  update: [data: UpdateTaskData]
  success: []
}>()

const form = ref({
  title: '',
  description: '',
  status: 'pending' as TaskStatus,
  due_date: ''
})

const isSubmitting = ref(false)
const errorMessage = ref<string | null>(null)
const successMessage = ref<string | null>(null)

watch(() => props.task, (newTask) => {
  if (newTask) {
    form.value = {
      title: newTask.title,
      description: newTask.description || '',
      status: newTask.status,
      due_date: formatDateForInput(newTask.due_date)
    }
  }
}, { immediate: true })

watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    errorMessage.value = null
    successMessage.value = null
  }
})


const formatDateForInput = (dateString: string): string => {
  try {
    if (!dateString) return ''
    
    if (dateString.match(/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}/)) {
      const [datePart, timePart] = dateString.split(' ')
      const [day, month, year] = datePart.split('-')
      return `${year}-${month}-${day}T${timePart}`
    }
    
    const date = new Date(dateString)
    if (isNaN(date.getTime())) return ''
    const year = date.getFullYear()
    const month = (date.getMonth() + 1).toString().padStart(2, '0')
    const day = date.getDate().toString().padStart(2, '0')
    const hours = date.getHours().toString().padStart(2, '0')
    const minutes = date.getMinutes().toString().padStart(2, '0')
    
    return `${year}-${month}-${day}T${hours}:${minutes}`
  } catch {
    return ''
  }
}


const formatDateForAPI = (dateString: string): string => {
  try {
    if (!dateString) return ''
    
    const date = new Date(dateString)
    if (isNaN(date.getTime())) return ''
    
    const year = date.getFullYear()
    const month = (date.getMonth() + 1).toString().padStart(2, '0')
    const day = date.getDate().toString().padStart(2, '0')
    const hours = date.getHours().toString().padStart(2, '0')
    const minutes = date.getMinutes().toString().padStart(2, '0')
    const seconds = '00'
    
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`
  } catch {
    return ''
  }
}


const handleSubmit = async (): Promise<void> => {
  if (!props.task) return
  
  isSubmitting.value = true
  errorMessage.value = null
  successMessage.value = null
  
  try {
    const updateData: UpdateTaskData = {
      title: form.value.title.trim(),
      description: form.value.description.trim() || null,
      status: form.value.status,
      due_date: formatDateForAPI(form.value.due_date)
    }
    
    await tasksService.updateTask(props.task.id, updateData)
    emit('success')
    
  } catch (err: any) {
    const errorHandler = getErrorHandler()
    const apiError = err as ApiError
    
    errorHandler.logError(err, 'TaskEditModal.handleSubmit')
    
    errorMessage.value = apiError.message || 'Erro ao atualizar tarefa'
  } finally {
    isSubmitting.value = false
  }
}


const closeModal = (): void => {
  emit('close')
}
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div 
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
        aria-hidden="true"
        @click="closeModal"
      ></div>

      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title">
              Editar Tarefa
            </h3>
            <button
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div v-if="successMessage" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
              <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <p class="ml-3 text-sm font-medium text-green-800">{{ successMessage }}</p>
            </div>
          </div>

          <div v-if="errorMessage" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex">
              <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
              <p class="ml-3 text-sm font-medium text-red-800">{{ errorMessage }}</p>
            </div>
          </div>

          <form @submit.prevent="handleSubmit" class="space-y-4">
            <BaseInput
              id="title"
              v-model="form.title"
              label="Título"
              type="text"
              placeholder="Digite o título da tarefa"
              required
            />

            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                Descrição
              </label>
              <textarea
                id="description"
                v-model="form.description"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                placeholder="Digite a descrição da tarefa (opcional)"
              ></textarea>
            </div>

            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                Status
              </label>
              <select
                id="status"
                v-model="form.status"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                required
              >
                <option value="pending">Pendente</option>
                <option value="completed">Concluída</option>
                <option value="overdue">Atrasada</option>
              </select>
            </div>

            <BaseInput
              id="due_date"
              v-model="form.due_date"
              label="Data de Vencimento"
              type="datetime-local"
              required
            />
          </form>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <BaseButton
            type="button"
            variant="primary"
            :disabled="isSubmitting"
            @click="handleSubmit"
            class="w-full sm:w-auto sm:ml-3"
          >
            <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ isSubmitting ? 'Salvando...' : 'Salvar Alterações' }}
          </BaseButton>
          
          <BaseButton
            type="button"
            variant="secondary"
            @click="closeModal"
            class="mt-3 w-full sm:mt-0 sm:w-auto"
            :disabled="isSubmitting"
          >
            Cancelar
          </BaseButton>
        </div>
      </div>
    </div>
  </div>
</template>