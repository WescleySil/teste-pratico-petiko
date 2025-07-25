<template>
  <AppLayout>
    <div class="space-y-6">
      <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="px-6 py-4">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">{{ isAdmin ? 'Gerenciamento de Usuários' : 'Perfil' }}</h1>
              <p class="mt-1 text-sm text-gray-600">
                {{ isAdmin ? 'Gerencie todos os usuários do sistema' : 'Visualize e atualize as informações do seu perfil' }}
              </p>
            </div>
            <div v-if="isAdmin" class="flex items-center space-x-3">
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
                  class="inline-flex items-center justify-center w-5 h-5 ml-2 text-xs font-bold text-white rounded-full bg-primary-600 animate-pulse"
                >
                  {{ activeFiltersCount }}
                </span>
                
                <span 
                  v-if="hasActiveFilters"
                  class="absolute w-3 h-3 border-2 border-white rounded-full -top-1 -right-1 bg-primary-500"
                ></span>
              </button>
            </div>
          </div>
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
      
      <BasePagination
        v-if="paginationMeta && users.length > 0 && isAdmin"
        :current-page="paginationMeta.currentPage"
        :last-page="paginationMeta.lastPage"
        :per-page="paginationMeta.perPage"
        :total="paginationMeta.total"
        :from="paginationMeta.from"
        :to="paginationMeta.to"
        @page-change="handlePageChange"
        @per-page-change="handlePerPageChange"
      />

      <div v-else class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div v-if="loading" class="px-6 py-12 text-center">
          <div class="inline-flex items-center">
            <svg class="w-5 h-5 mr-3 -ml-1 animate-spin text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Carregando perfil...
          </div>
        </div>

        <div v-else-if="error" class="px-6 py-12 text-center">
          <div class="mb-4 text-red-500">
            <svg class="w-12 h-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="mb-2 text-lg font-medium text-gray-900">Erro ao carregar perfil</h3>
          <p class="mb-4 text-gray-500">{{ error }}</p>
          <button @click="loadCurrentUser" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md bg-primary-600 hover:bg-primary-700">
            Tentar Novamente
          </button>
        </div>

        <div v-else>
          <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Informações do Perfil</h2>
            <p class="mt-1 text-sm text-gray-600">Atualize as informações do seu perfil e senha.</p>
          </div>

          <form @submit.prevent="handleSubmit" class="px-6 py-6 space-y-6">
            <div v-if="successMessage" class="p-4 border border-green-200 rounded-lg bg-green-50">
              <div class="flex">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800">{{ successMessage }}</p>
              </div>
            </div>

            <div v-if="formError" class="p-4 border border-red-200 rounded-lg bg-red-50">
              <div class="flex">
                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
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
                <svg v-if="isSubmitting" class="w-4 h-4 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
      
      <UserFiltersModal
        v-if="isAdmin"
        :is-open="isFiltersModalOpen"
        @close="closeFiltersModal"
        @apply="handleFiltersApply"
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
import UserFiltersModal from '../components/users/UserFiltersModal.vue'
import BaseInput from '../components/ui/BaseInput.vue'
import BaseButton from '../components/ui/BaseButton.vue'
import BasePagination from '../components/ui/BasePagination.vue'
import { usersService } from '../services/users'
import { getCurrentUser } from '../utils/auth'
import { getErrorHandler } from '../utils/errorHandler'
import type { User, UpdateUserData } from '../interfaces/user'
import type { ApiError } from '../utils/errorHandler'
import type { PaginationMeta } from '../interfaces/pagination'

const users = ref<User[]>([])
const currentUser = ref<User | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)
const paginationMeta = ref<PaginationMeta | null>(null)
const currentPage = ref<number>(1)
const perPage = ref<number>(10)

const currentFilters = ref({
  name: '',
  username: '',
  email: ''
})

const hasActiveFilters = computed(() => {
  return currentFilters.value.name !== '' || 
         currentFilters.value.username !== '' || 
         currentFilters.value.email !== ''
})

const activeFiltersCount = computed(() => {
  let count = 0
  if (currentFilters.value.name !== '') count++
  if (currentFilters.value.username !== '') count++
  if (currentFilters.value.email !== '') count++
  return count
})

const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const isFiltersModalOpen = ref(false)
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

const openFiltersModal = (): void => {
  isFiltersModalOpen.value = true
}

const closeFiltersModal = (): void => {
  isFiltersModalOpen.value = false
}

const handleFiltersApply = async (filters: any): Promise<void> => {
  currentFilters.value = { ...filters }
  await fetchUsers(true)
}


const fetchUsers = async (withFilters: boolean = false): Promise<void> => {
  loading.value = true
  error.value = null
  
  try {
    let response

    if (withFilters && hasActiveFilters.value) {
      const params = {
        filters: {
          name: currentFilters.value.name || undefined,
          username: currentFilters.value.username || undefined,
          email: currentFilters.value.email || undefined,
          per_page: perPage.value
        },
        page: currentPage.value
      }
      
      // Remove undefined values
      Object.keys(params.filters).forEach(key => {
        if (params.filters[key as keyof typeof params.filters] === undefined) {
          delete params.filters[key as keyof typeof params.filters]
        }
      })

      response = await usersService.getUsersWithFilters(params)
    } else {
      response = await usersService.getUsers(currentPage.value, perPage.value)
    }

    users.value = response.data
    paginationMeta.value = {
      currentPage: response.meta.current_page,
      lastPage: response.meta.last_page,
      perPage: response.meta.per_page,
      total: response.meta.total,
      from: response.meta.from,
      to: response.meta.to
    }
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

const handlePageChange = async (page: number): Promise<void> => {
  currentPage.value = page
  await fetchUsers(hasActiveFilters.value)
}

const handlePerPageChange = async (newPerPage: number): Promise<void> => {
  perPage.value = newPerPage
  currentPage.value = 1
  await fetchUsers(hasActiveFilters.value)
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