import http from './http'
import type { Task, TaskResponse, SingleTaskResponse } from '../interfaces/task'
import type { PaginationData } from '../interfaces/pagination'

export interface UpdateTaskData {
    title: string
    description: string | null
    status: 'pending' | 'overdue' | 'completed'
    due_date: string
}

export interface CreateTaskData {
    title: string
    description: string | null
    status: 'pending' | 'overdue' | 'completed'
    due_date: string
    user_id?: number | null
}

export const tasksService = {
    async getTasks(page: number = 1, perPage: number = 10): Promise<PaginationData> {
        const response = await http.get<PaginationData>('/api/task', {
            params: {
                'filters[per_page]': perPage,
                page: page
            }
        })
        return response.data
    },

    async getTasksWithFilters(filtersData: any): Promise<PaginationData> {
        const response = await http.get<PaginationData>('/api/task', {
            params: filtersData
        })
        return response.data
    },

    async createTask(data: CreateTaskData): Promise<Task> {
        const response = await http.post<SingleTaskResponse>('/api/task', data)
        return response.data.data
    },

    async updateTask(taskId: number, data: UpdateTaskData): Promise<Task> {
        const response = await http.put<SingleTaskResponse>(`/api/task/${taskId}`, data)
        return response.data.data
    },

    async deleteTask(taskId: number): Promise<void> {
        await http.delete(`/api/task/${taskId}`)
    }
}