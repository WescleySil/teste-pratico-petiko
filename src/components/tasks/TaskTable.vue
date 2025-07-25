<template>
  <div class="bg-white shadow-sm rounded-lg border border-gray-200">
    <div v-if="loading" class="p-12 text-center">
      <div class="flex flex-col items-center space-y-4">
        <div class="relative">
          <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
          <div class="absolute inset-0 w-12 h-12 border-4 border-transparent border-r-blue-400 rounded-full animate-spin animation-delay-150"></div>
        </div>
        
        <div class="space-y-2">
          <p class="text-lg font-medium text-gray-700 animate-pulse">Carregando tarefas...</p>
          <p class="text-sm text-gray-500">Aguarde enquanto buscamos suas tarefas</p>
        </div>
        
        <div class="w-full max-w-6xl mt-8">
          <div class="space-y-4">
            <div class="grid grid-cols-5 gap-4 p-4 bg-gray-50 rounded-lg">
              <div class="h-4 bg-gray-200 rounded skeleton"></div>
              <div class="h-4 bg-gray-200 rounded skeleton animation-delay-150"></div>
              <div class="h-4 bg-gray-200 rounded skeleton animation-delay-300"></div>
              <div class="h-4 bg-gray-200 rounded skeleton"></div>
              <div class="h-4 bg-gray-200 rounded skeleton animation-delay-150"></div>
            </div>
            
            <div class="space-y-3">
              <div v-for="i in 3" :key="i" class="grid grid-cols-5 gap-4 p-4 bg-white border rounded-lg">
                <div class="h-3 bg-gray-200 rounded skeleton" :class="`animation-delay-${i * 100}`"></div>
                <div class="h-3 bg-gray-200 rounded w-3/4 skeleton" :class="`animation-delay-${i * 100 + 50}`"></div>
                <div class="h-3 bg-gray-200 rounded w-1/2 skeleton" :class="`animation-delay-${i * 100 + 100}`"></div>
                <div class="h-3 bg-gray-200 rounded w-2/3 skeleton" :class="`animation-delay-${i * 100 + 150}`"></div>
                <div class="h-3 bg-gray-200 rounded w-4/5 skeleton" :class="`animation-delay-${i * 100 + 200}`"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="error" class="p-8 text-center">
      <div class="text-red-500 mb-4">
        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
      </div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">Não foi possível carregar as tarefas</h3>
      <p class="text-gray-600 mb-6 max-w-md mx-auto">{{ error }}</p>
      <div class="space-x-3">
        <button 
          @click="$emit('retry')"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          Tentar Novamente
        </button>
        <button 
          @click="$emit('refresh')"
          class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          Atualizar Página
        </button>
      </div>
    </div>

    <div v-else-if="!tasks || tasks.length === 0" class="p-12 text-center">
      <div class="flex flex-col items-center space-y-4">
        <div class="relative">
          <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
          </div>
          <div class="absolute -top-1 -right-1 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
            <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
        
        <div class="space-y-2">
          <h3 class="text-lg font-medium text-gray-900">Nenhuma tarefa encontrada</h3>
          <p class="text-gray-500 max-w-sm mx-auto">
            Você ainda não possui tarefas. As tarefas aparecerão aqui quando forem criadas.
          </p>
        </div>
        
        <div class="pt-4">
          <button 
            @click="$emit('retry')"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Atualizar
          </button>
        </div>
      </div>
    </div>

    <div v-else class="overflow-x-auto fade-in">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Título
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Descrição
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th v-if="isAdmin" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Atribuído para
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Data de Vencimento
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Criado em
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Ações
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr 
            v-for="(task, index) in tasks" 
            :key="task.id"
            class="hover:bg-gray-50 transition-colors duration-150 fade-in cursor-pointer"
            :style="{ animationDelay: `${index * 50}ms` }"
            @click="$emit('edit', task)"
          >
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">
                {{ task.title }}
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-gray-600 max-w-xs truncate" :title="task.description || 'Sem descrição'">
                {{ task.description || 'Sem descrição' }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusBadgeClasses(task.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full transition-all duration-200">
                {{ getStatusLabel(task.status) }}
              </span>
            </td>
            <td v-if="isAdmin" class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ task.user?.name || 'Não atribuído' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ formatDate(task.due_date) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ formatDate(task.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <div class="flex items-center space-x-3">
                <button
                  @click.stop="$emit('edit', task)"
                  class="text-primary-600 hover:text-primary-900 transition-colors duration-200"
                  title="Editar tarefa"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button
                  @click.stop="$emit('delete', task)"
                  class="text-red-600 hover:text-red-900 transition-colors duration-200"
                  title="Excluir tarefa"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Task, TaskStatus } from '../../interfaces/task'

interface Props {
  tasks: Task[]
  loading: boolean
  error: string | null
  isAdmin?: boolean
}

defineProps<Props>()

defineEmits<{
  retry: []
  refresh: []
  edit: [task: Task]
  delete: [task: Task]
}>()

const formatDate = (dateString: string): string => {
  try {
    if (!dateString) {
      return 'Data não informada'
    }
    
    if (dateString.match(/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}/)) {
      return dateString.replace(/-/g, '/')
    }
    
    const date = new Date(dateString)
    if (isNaN(date.getTime())) {
      return 'Data inválida'
    }
    
    const day = date.getDate().toString().padStart(2, '0')
    const month = (date.getMonth() + 1).toString().padStart(2, '0')
    const year = date.getFullYear()
    const hours = date.getHours().toString().padStart(2, '0')
    const minutes = date.getMinutes().toString().padStart(2, '0')
    const seconds = date.getSeconds().toString().padStart(2, '0')
    
    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`
  } catch (error) {
    return 'Data inválida'
  }
}

const getStatusBadgeClasses = (status: TaskStatus): string => {
  const baseClasses = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full'
  
  switch (status) {
    case 'completed':
      return `${baseClasses} bg-green-100 text-green-800`
    case 'pending':
      return `${baseClasses} bg-yellow-100 text-yellow-800`
    case 'overdue':
      return `${baseClasses} bg-red-100 text-red-800`
    default:
      return `${baseClasses} bg-gray-100 text-gray-800`
  }
}

/**
 * Get human-readable status label
 */
const getStatusLabel = (status: TaskStatus): string => {
  switch (status) {
    case 'completed':
      return 'Concluída'
    case 'pending':
      return 'Pendente'
    case 'overdue':
      return 'Atrasada'
    default:
      return status
  }
}
</script>