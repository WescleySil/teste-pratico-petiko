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
              Editar Usuário
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
              id="name"
              v-model="form.name"
              label="Nome"
              type="text"
              placeholder="Digite o nome completo"
              required
            />

            <BaseInput
              id="username"
              v-model="form.username"
              label="Nome de usuário"
              type="text"
              placeholder="Digite o nome de usuário"
              required
            />

            <BaseInput
              id="email"
              v-model="form.email"
              label="E-mail"
              type="email"
              placeholder="Digite o endereço de e-mail"
              required
            />

            <BaseInput
              id="password"
              v-model="form.password"
              label="Senha"
              type="password"
              placeholder="Deixe vazio para manter a senha atual"
            />

            <div>
              <label class="flex items-center space-x-3">
                <input
                  id="is_admin"
                  v-model="form.is_admin"
                  type="checkbox"
                  class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2"
                />
                <div class="flex flex-col">
                  <span class="text-sm font-medium text-gray-700">Administrador</span>
                  <span class="text-xs text-gray-500">
                    Usuários administradores podem gerenciar todos os usuários e tarefas
                  </span>
                </div>
              </label>
            </div>
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

<script setup lang="ts">
import { ref, watch } from 'vue'
import BaseInput from '../ui/BaseInput.vue'
import BaseButton from '../ui/BaseButton.vue'
import type { User } from '../../interfaces/user'
import { usersService } from '../../services/users'
import { getErrorHandler } from '../../utils/errorHandler'
import type { ApiError } from '../../utils/errorHandler'

interface Props {
  isOpen: boolean
  user: User | null
}

interface UpdateUserData {
  name: string
  username: string
  email: string
  password?: string
  is_admin?: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  success: []
}>()

const form = ref({
  name: '',
  username: '',
  email: '',
  password: '',
  is_admin: false
})

const isSubmitting = ref(false)
const errorMessage = ref<string | null>(null)

watch(() => props.user, (newUser) => {
  if (newUser) {
    form.value = {
      name: newUser.name,
      username: newUser.username,
      email: newUser.email,
      password: '',
      is_admin: newUser.is_admin
    }
  }
}, { immediate: true })

watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    errorMessage.value = null
  }
})

const handleSubmit = async (): Promise<void> => {
  if (!props.user) return
  
  isSubmitting.value = true
  errorMessage.value = null
  
  try {
    const updateData: UpdateUserData = {
      name: form.value.name.trim(),
      username: form.value.username.trim(),
      email: form.value.email.trim(),
      is_admin: form.value.is_admin
    }
    
    if (form.value.password.trim() !== '') {
      updateData.password = form.value.password
    }
    
    await usersService.updateUser(props.user.id, updateData)
    emit('success')
    
  } catch (err: any) {
    const errorHandler = getErrorHandler()
    const apiError = err as ApiError
    
    errorHandler.logError(err, 'UserEditModal.handleSubmit')
    
    errorMessage.value = apiError.message || 'Erro ao atualizar usuário'
  } finally {
    isSubmitting.value = false
  }
}

const closeModal = (): void => {
  emit('close')
}
</script>