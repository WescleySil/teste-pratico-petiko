<template>
  <nav class="bg-white shadow-lg border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <div class="flex-shrink-0 flex items-center">
            <img class="h-8 w-8" src="/src/assets/todo-svgrepo-com.svg" alt="Todo App" />
            <span class="ml-2 text-xl font-bold text-gray-800">Todo List</span>
          </div>
          
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <router-link
              to="/tasks"
              :class="[
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200',
                isActiveRoute('/tasks') 
                  ? 'border-primary-500 text-primary-600' 
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              ]"
            >
              Tarefas
            </router-link>
            
            <router-link
              v-if="isAdmin"
              to="/users"
              :class="[
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200',
                isActiveRoute('/users') 
                  ? 'border-primary-500 text-primary-600' 
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              ]"
            >
              Usuários
            </router-link>
            
            <router-link
              v-if="!isAdmin"
              to="/users"
              :class="[
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200',
                isActiveRoute('/users') 
                  ? 'border-primary-500 text-primary-600' 
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              ]"
            >
              Perfil
            </router-link>
          </div>
        </div>
        
        <div class="hidden sm:ml-6 sm:flex sm:items-center">
          <div class="ml-3 relative">
            <button
              @click="handleLogout"
              class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200"
            >
              <span class="sr-only">Sair</span>
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
            </button>
          </div>
        </div>
        
        <div class="-mr-2 flex items-center sm:hidden">
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500"
          >
            <span class="sr-only">Abrir menu principal</span>
            <svg
              :class="mobileMenuOpen ? 'hidden' : 'block'"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg
              :class="mobileMenuOpen ? 'block' : 'hidden'"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
    
    <div :class="mobileMenuOpen ? 'block' : 'hidden'" class="sm:hidden">
      <div class="pt-2 pb-3 space-y-1">
        <router-link
          to="/tasks"
          :class="[
            'block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200',
            isActiveRoute('/tasks')
              ? 'bg-primary-50 border-primary-500 text-primary-700'
              : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'
          ]"
          @click="mobileMenuOpen = false"
        >
          Tarefas
        </router-link>
        
        <router-link
          v-if="isAdmin"
          to="/users"
          :class="[
            'block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200',
            isActiveRoute('/users')
              ? 'bg-primary-50 border-primary-500 text-primary-700'
              : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'
          ]"
          @click="mobileMenuOpen = false"
        >
          Usuários
        </router-link>
        
        <router-link
          v-if="!isAdmin"
          to="/users"
          :class="[
            'block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200',
            isActiveRoute('/users')
              ? 'bg-primary-50 border-primary-500 text-primary-700'
              : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'
          ]"
          @click="mobileMenuOpen = false"
        >
          Perfil
        </router-link>
      </div>
      
      <div class="pt-4 pb-3 border-t border-gray-200">
        <div class="flex items-center px-4">
          <button
            @click="handleLogout"
            class="flex-shrink-0 bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
          >
            <span class="sr-only">Sair</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
          </button>
          <span class="ml-3 text-base font-medium text-gray-800">Sair</span>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { logout, getCurrentUser } from '../../utils/auth'
import type { User } from '../../interfaces/user'

const route = useRoute()

const mobileMenuOpen = ref(false)
const currentUser = ref<User | null>(null)

const isAdmin = computed(() => currentUser.value?.is_admin || false)

onMounted(() => {
  try {
    currentUser.value = getCurrentUser()
  } catch (error) {

  }
})

const isActiveRoute = (path: string): boolean => {
  return route.path === path
}

const handleLogout = () => {
  logout()
}
</script>