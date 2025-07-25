import http from './http'

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
    async getUsers(): Promise<User[]> {
        const response = await http.get('/api/user')
        return response.data.data
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