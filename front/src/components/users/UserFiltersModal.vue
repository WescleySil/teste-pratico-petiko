<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 overflow-hidden">
      <div 
        class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
        @click="closeModal"
      ></div>

      <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div class="w-screen max-w-md transition duration-300 ease-in-out transform" :class="isOpen ? 'translate-x-0' : 'translate-x-full'">
          <div class="flex flex-col h-full overflow-y-scroll bg-white shadow-xl">
            <div class="px-4 py-6 bg-primary-600 sm:px-6">
              <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-white" id="slide-over-title">
                  Filtros de Usuários
                </h2>
                <div class="flex items-center ml-3 h-7">
                  <button
                    @click="closeModal"
                    class="rounded-md bg-primary-600 text-primary-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                  >
                    <span class="sr-only">Fechar painel</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <div class="flex-1 px-4 py-6 sm:px-6">
              <form @submit.prevent="applyFilters" class="space-y-6">
                <div v-if="validationMessage" class="p-4 mb-4 text-sm rounded-lg" :class="validationMessage.type === 'error' ? 'text-red-800 bg-red-50' : 'text-green-800 bg-green-50'">
                  {{ validationMessage.text }}
                </div>
                <div>
                  <BaseInput
                    id="name"
                    v-model="filters.name"
                    label="Buscar por nome"
                    type="text"
                    placeholder="Digite o nome do usuário"
                  />
                </div>

                <div>
                  <BaseInput
                    id="username"
                    v-model="filters.username"
                    label="Buscar por nome de usuário"
                    type="text"
                    placeholder="Digite o nome de usuário"
                  />
                </div>

                <div>
                  <BaseInput
                    id="email"
                    v-model="filters.email"
                    label="Buscar por e-mail"
                    type="email"
                    placeholder="Digite o e-mail do usuário"
                    :error="emailError"
                    @blur="validateEmail"
                  />
                </div>

                <div v-if="hasActiveFilters" class="pt-4 border-t">
                  <h3 class="mb-3 text-sm font-medium text-gray-700">Filtros ativos:</h3>
                  <div class="space-y-2">
                    <div v-if="filters.name" class="flex items-center justify-between px-3 py-2 rounded-md bg-blue-50">
                      <span class="text-sm text-blue-800">Nome: "{{ filters.name }}"</span>
                      <button @click="filters.name = ''" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>

                    <div v-if="filters.username" class="flex items-center justify-between px-3 py-2 rounded-md bg-green-50">
                      <span class="text-sm text-green-800">Nome de usuário: "{{ filters.username }}"</span>
                      <button @click="filters.username = ''" class="text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>

                    <div v-if="filters.email" class="flex items-center justify-between px-3 py-2 rounded-md bg-purple-50">
                      <span class="text-sm text-purple-800">E-mail: "{{ filters.email }}"</span>
                      <button @click="filters.email = ''" class="text-purple-600 hover:text-purple-800">
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
                  class="flex items-center justify-center w-full"
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
                  class="flex items-center justify-center w-full"
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
import { ref, computed } from 'vue'
import BaseInput from '../ui/BaseInput.vue'
import BaseButton from '../ui/BaseButton.vue'

interface Props {
  isOpen: boolean
}

interface UserFilters {
  name: string
  username: string
  email: string
}

interface ValidationMessage {
  type: 'error' | 'success'
  text: string
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  apply: [filters: UserFilters]
}>()

const validationMessage = ref<ValidationMessage | null>(null)
const filters = ref<UserFilters>({
  name: '',
  username: '',
  email: ''
})

const emailError = ref<string>('')

const isValidEmail = (email: string): boolean => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return email === '' || emailRegex.test(email)
}

const validateEmail = () => {
  if (!isValidEmail(filters.value.email)) {
    emailError.value = 'Por favor, insira um e-mail válido'
    validationMessage.value = {
      type: 'error',
      text: 'Por favor, insira um e-mail válido para aplicar o filtro'
    }
    return false
  }
  emailError.value = ''
  validationMessage.value = null
  return true
}

const hasActiveFilters = computed(() => {
  return filters.value.name !== '' || 
         filters.value.username !== '' || 
         filters.value.email !== ''
})

const applyFilters = (): void => {
  if (!validateEmail()) {
    return
  }
  validationMessage.value = {
    type: 'success',
    text: 'Filtros aplicados com sucesso!'
  }
  emit('apply', { ...filters.value })
  setTimeout(() => {
    closeModal()
  }, 1000)
}

const clearFilters = (): void => {
  filters.value = {
    name: '',
    username: '',
    email: ''
  }
  emit('apply', { ...filters.value })
  closeModal()
}

const closeModal = (): void => {
  emit('close')
}
</script>
