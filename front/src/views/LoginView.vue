<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import BaseInput from '../components/ui/BaseInput.vue'
import BaseButton from '../components/ui/BaseButton.vue'
import { authService } from '../services/auth'

const router = useRouter()

const form = ref({
  login: '',
  password: ''
})

const isLoading = ref(false)
const errorMessage = ref('')

const isFormValid = computed(() => {
  return form.value.login.trim() !== '' && form.value.password.trim() !== ''
})

const handleLogin = async () => {
  if (!isFormValid.value) return

  isLoading.value = true
  errorMessage.value = ''

  try {
    const response = await authService.login({
      login: form.value.login,
      password: form.value.password
    })

    // Salvar token no localStorage
    localStorage.setItem('auth_token', response.token)
    
    // Redirecionar para dashboard ou página principal
    router.push('/dashboard')
    
    console.log('Login realizado com sucesso:', response.user)
  } catch (error: any) {
    console.error('Erro no login:', error)
    
    if (error.response?.status === 401) {
      errorMessage.value = 'Credenciais inválidas. Verifique seu usuário e senha.'
    } else if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message
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
      <h1 class="mb-8 text-3xl font-bold text-center text-gray-800">Login</h1>
      
      <!-- Mensagem de erro -->
      <div v-if="errorMessage" class="mb-4 p-3 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg">
        {{ errorMessage }}
      </div>
      
      <form @submit.prevent="handleLogin" class="mb-6">
        <BaseInput
          id="username"
          v-model="form.login"
          label="Nome de usuário ou email"
          type="text"
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
            Entrando...
          </span>
          <span v-else>Fazer Login</span>
        </BaseButton>
      </form>
      
      <p class="text-sm text-center text-gray-500">
        Não tem conta? 
        <router-link to="/register" class="font-medium no-underline transition-colors duration-200 text-primary-500 hover:text-primary-600 hover:underline">
          Cadastre-se aqui
        </router-link>
      </p>
    </div>
  </div>
</template>