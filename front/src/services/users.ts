import http from './http'
import type { PaginationData } from '../interfaces/pagination'

export interface User {
    id: number
    name: string
    username: string
    email: string
    is_admin: boolean
    created_at: string
    updated_at: string
}

export interface UpdateUserData {
    name: string
    username: string
    email: string
    password?: string
}

class UsersService {
    async getUsers(page: number = 1, perPage: number = 10): Promise<PaginationData> {
        const response = await http.get<PaginationData>('/api/user', {
            params: {
                'filters[per_page]': perPage,
                page: page
            }
        })
        return response.data
    }

    async getUsersWithFilters(filtersData: any): Promise<PaginationData> {
        const response = await http.get<PaginationData>('/api/user', {
            params: filtersData
        })
        return response.data
    }

    async updateUser(userId: number, data: UpdateUserData): Promise<User> {
        const response = await http.put(`/api/user/${userId}`, data)
        return response.data.data
    }

    async deleteUser(userId: number): Promise<void> {
        await http.delete(`/api/user/${userId}`)
    }
}

export const usersService = new UsersService()