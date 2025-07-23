<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import BaseInput from '../components/ui/BaseInput.vue'
import BaseButton from '../components/ui/BaseButton.vue'
import { authService } from '../services/auth'

const router = useRouter()

const form = ref({
  name: '',
  username: '',
  email: '',
  password: ''
})

const isLoading = ref(false)
const errorMessage = ref('')

const isFormValid = computed(() => {
  return form.value.name.trim() !== '' && 
         form.value.username.trim() !== '' &&
         form.value.email.trim() !== '' && 
         form.value.password.trim() !== ''
})

const handleRegister = async () => {
  if (!isFormValid.value) return

  isLoading.value = true
  errorMessage.value = ''

  try {
    const response = await authService.register({
      name: form.value.name,
      username: form.value.username,
      email: form.value.email,
      password: form.value.password
    })

    localStorage.setItem('auth_token', response.token)
    router.push('/dashboard')
    
    console.log('Cadastro realizado com sucesso:', response.user)
  } catch (error: any) {
    console.error('Erro no cadastro:', error)
    
    if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message
    } else if (error.response?.data?.errors) {
      // Tratar erros de validação do Laravel
      const errors = error.response.data.errors
      const firstError = Object.values(errors)[0] as string[]
      errorMessage.value = firstError[0]
    } else {
      errorMessage.value = 'Erro interno do servidor. Tente novamente.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="flex items-center justify-center w-screen h-screen p-4 overflow-hidden bg-gradient-to-br from-cozy-50 to-cozy-300">
    <div class="w-full max-w-md p-10 border shadow-xl bg-cozy-100 rounded-2xl border-cozy-200">
      <h1 class="mb-8 text-3xl font-bold text-center text-gray-800">Cadastro</h1>
      
      <!-- Mensagem de erro -->
      <div v-if="errorMessage" class="mb-4 p-3 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg">
        {{ errorMessage }}
      </div>
      
      <form @submit.prevent="handleRegister" class="mb-6">
        <BaseInput
          id="name"
          v-model="form.name"
          label="Nome completo"
          type="text"
          placeholder="Digite aqui"
        />
        
        <BaseInput
          id="username"
          v-model="form.username"
          label="Nome de usuário"
          type="text"
          placeholder="Digite aqui"
        />

        <BaseInput
          id="email"
          v-model="form.email"
          label="Email"
          type="email"
          placeholder="Digite aqui"
        />
        
        <BaseInput
          id="password"
          v-model="form.password"
          label="Senha"
          type="password"
          placeholder="Digite aqui"
        />
        
        <BaseButton
          type="submit"
          variant="primary"
          :disabled="!isFormValid || isLoading"
        >
          <span v-if="isLoading" class="flex items-center justify-center">
            <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Cadastrando...
          </span>
          <span v-else>Cadastrar</span>
        </BaseButton>
      </form>
      
      <p class="text-sm text-center text-gray-500">
        Já tem conta? 
        <router-link to="/login" class="font-medium no-underline transition-colors duration-200 text-primary-500 hover:text-primary-600 hover:underline">
          Faça login aqui
        </router-link>
      </p>
    </div>
  </div>
</template>