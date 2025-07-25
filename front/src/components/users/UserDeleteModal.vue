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
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Excluir Usuário
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Tem certeza de que deseja excluir o usuário "<strong>{{ user?.name }}</strong>"? 
                  Esta ação não pode ser desfeita e todos os dados do usuário serão permanentemente removidos.
                </p>
              </div>
            </div>
          </div>

          <div v-if="errorMessage" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex">
              <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
              <p class="ml-3 text-sm font-medium text-red-800">{{ errorMessage }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <BaseButton
            type="button"
            variant="danger"
            :disabled="isDeleting"
            @click="handleDelete"
            class="w-full sm:w-auto sm:ml-3"
          >
            <svg v-if="isDeleting" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ isDeleting ? 'Excluindo...' : 'Excluir' }}
          </BaseButton>
          
          <BaseButton
            type="button"
            variant="secondary"
            @click="closeModal"
            class="mt-3 w-full sm:mt-0 sm:w-auto"
            :disabled="isDeleting"
          >
            Cancelar
          </BaseButton>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import BaseButton from '../ui/BaseButton.vue'
import type { User } from '../../interfaces/user'
import { usersService } from '../../services/users'
import { getErrorHandler } from '../../utils/errorHandler'
import type { ApiError } from '../../utils/errorHandler'

interface Props {
  isOpen: boolean
  user: User | null
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  success: []
}>()

const isDeleting = ref(false)
const errorMessage = ref<string | null>(null)

watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    errorMessage.value = null
  }
})


const handleDelete = async (): Promise<void> => {
  if (!props.user) return
  
  isDeleting.value = true
  errorMessage.value = null
  
  try {
    await usersService.deleteUser(props.user.id)
    emit('success')
    
  } catch (err: any) {
    const errorHandler = getErrorHandler()
    const apiError = err as ApiError
    
    errorHandler.logError(err, 'UserDeleteModal.handleDelete')
    
    errorMessage.value = apiError.message || 'Erro ao excluir usuário'
  } finally {
    isDeleting.value = false
  }
}


const closeModal = (): void => {
  emit('close')
}
</script>