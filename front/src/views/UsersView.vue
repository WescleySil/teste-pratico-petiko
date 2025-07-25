<template>
  <AppLayout>
    <div class="space-y-6">
      <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4">
          <h1 class="text-2xl font-bold text-gray-900">{{ isAdmin ? 'Gerenciamento de Usuários' : 'Perfil' }}</h1>
          <p class="mt-1 text-sm text-gray-600">
            {{ isAdmin ? 'Gerencie todos os usuários do sistema' : 'Visualize e atualize as informações do seu perfil' }}
          </p>
        </div>
      </div>

      <UserTable 
        v-if="isAdmin"
        :users="users" 
        :loading="loading" 
        :error="error"
        @retry="fetchUsers"
        @refresh="handleRefresh"
        @edit="handleEditUser"
        @delete="handleDeleteUser"
      />

      <div v-else class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div v-if="loading" class="px-6 py-12 text-center">
          <div class="inline-flex items-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Carregando perfil...
          </div>
        </div>

        <div v-else-if="error" class="px-6 py-12 text-center">
          <div class="text-red-500 mb-4">
            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Erro ao carregar perfil</h3>
          <p class="text-gray-500 mb-4">{{ error }}</p>
          <button @click="loadCurrentUser" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
            Tentar Novamente
          </button>
        </div>

        <div v-else>
          <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Informações do Perfil</h2>
            <p class="mt-1 text-sm text-gray-600">Atualize as informações do seu perfil e senha.</p>
          </div>

          <form @submit.prevent="handleSubmit" class="px-6 py-6 space-y-6">
            <div v-if="successMessage" class="p-4 bg-green-50 border border-green-200 rounded-lg">
              <div class="flex">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800">{{ successMessage }}</p>
              </div>
            </div>

            <div v-if="formError" class="p-4 bg-red-50 border border-red-200 rounded-lg">
              <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm font-medium text-red-800">{{ formError }}</p>
              </div>
            </div>

            <BaseInput
              id="name"
              v-model="form.name"
              label="Nome"
              type="text"
              placeholder="Digite seu nome completo"
              required
            />

            <BaseInput
              id="username"
              v-model="form.username"
              label="Nome de usuário"
              type="text"
              placeholder="Digite seu nome de usuário"
              required
            />

            <BaseInput
              id="email"
              v-model="form.email"
              label="E-mail"
              type="email"
              placeholder="Digite seu endereço de e-mail"
              required
            />

            <BaseInput
              id="password"
              v-model="form.password"
              label="Senha"
              type="password"
              placeholder="Deixe vazio para manter a senha atual"
            />

            <div class="flex justify-end">
              <BaseButton
                type="submit"
                variant="primary"
                :disabled="isSubmitting"
              >
                <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ isSubmitting ? 'Atualizando...' : 'Atualizar Perfil' }}
              </BaseButton>
            </div>
          </form>
        </div>
      </div>

      <UserEditModal
        v-if="isAdmin"
        :is-open="isEditModalOpen"
        :user="selectedUser"
        @close="closeEditModal"
        @success="handleEditUserSuccess"
      />

      <UserDeleteModal
        v-if="isAdmin"
        :is-open="isDeleteModalOpen"
        :user="selectedUser"
        @close="closeDeleteModal"
        @success="handleDeleteUserSuccess"
      />
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import AppLayout from '../components/layout/AppLayout.vue'
import UserTable from '../components/users/UserTable.vue'
import UserEditModal from '../components/users/UserEditModal.vue'
import UserDeleteModal from '../components/users/UserDeleteModal.vue'
import BaseInput from '../components/ui/BaseInput.vue'
import BaseButton from '../components/ui/BaseButton.vue'
import { usersService } from '../services/users'
import { getCurrentUser } from '../utils/auth'
import { getErrorHandler } from '../utils/errorHandler'
import type { User, UpdateUserData } from '../interfaces/user'
import type { ApiError } from '../utils/errorHandler'

const users = ref<User[]>([])
const currentUser = ref<User | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)

const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const selectedUser = ref<User | null>(null)

const form = ref({
  name: '',
  username: '',
  email: '',
  password: ''
})
const isSubmitting = ref(false)
const formError = ref<string | null>(null)
const successMessage = ref<string | null>(null)

const isAdmin = computed(() => currentUser.value?.is_admin || false)


const fetchUsers = async (): Promise<void> => {
  loading.value = true
  error.value = null
  
  try {
    users.value = await usersService.getUsers()
  } catch (err: any) {
    const errorHandler = getErrorHandler()
    const apiError = err as ApiError
    
    errorHandler.logError(err, 'UsersView.fetchUsers')
    
    if (apiError.code === 'AUTHENTICATION_ERROR') {
      return
    }
    
    error.value = apiError.message || 'Falha ao carregar usuários. Tente novamente.'
  } finally {
    loading.value = false
  }
}


const loadCurrentUser = (): void => {
  loading.value = true
  error.value = null
  
  try {
    const user = getCurrentUser()
    if (!user) {
      error.value = 'Usuário não encontrado. Faça login novamente.'
      return
    }
    currentUser.value = user
    
    form.value = {
      name: user.name || '',
      username: user.username || '',
      email: user.email || '',
      password: ''
    }
  } catch (err: any) {
    error.value = 'Falha ao carregar dados do usuário. Faça login novamente.'
  } finally {
    loading.value = false
  }
}


const handleSubmit = async (): Promise<void> => {
  if (!currentUser.value) return
  
  isSubmitting.value = true
  formError.value = null
  successMessage.value = null
  
  try {
    const updateData: UpdateUserData = {
      name: form.value.name,
      username: form.value.username,
      email: form.value.email
    }
    
    if (form.value.password.trim() !== '') {
      updateData.password = form.value.password
    }
    
    const updatedUser = await usersService.updateUser(currentUser.value.id, updateData)
    currentUser.value = updatedUser
    
    localStorage.setItem('current_user', JSON.stringify(updatedUser))
    form.value.password = ''
    successMessage.value = 'Perfil atualizado com sucesso!'
    
    setTimeout(() => {
      successMessage.value = null
    }, 3000)
  } catch (error: any) {
    formError.value = error.message || 'Falha ao atualizar perfil'
  } finally {
    isSubmitting.value = false
  }
}


const handleRefresh = (): void => {
  window.location.reload()
}


const handleEditUser = (user: User): void => {
  selectedUser.value = user
  isEditModalOpen.value = true
}


const closeEditModal = (): void => {
  isEditModalOpen.value = false
  selectedUser.value = null
}


const handleEditUserSuccess = async (): Promise<void> => {
  closeEditModal()
  await fetchUsers()
}


const handleDeleteUser = (user: User): void => {
  selectedUser.value = user
  isDeleteModalOpen.value = true
}


const closeDeleteModal = (): void => {
  isDeleteModalOpen.value = false
  selectedUser.value = null
}


const handleDeleteUserSuccess = async (): Promise<void> => {
  closeDeleteModal()
  await fetchUsers()
}

onMounted(async () => {
  loadCurrentUser()
  if (isAdmin.value) {
    await fetchUsers()
  }
})
</script>