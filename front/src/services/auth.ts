import http from './http'

export const authService = {
    async register(data: any): Promise<anyaqq                                                                                                                                                                                                                                                                                                                                   > {
        const response = await http.post('/api/user', data)
        return response.data
    },

    async login(data: any): Promise<any> {
        const response = await http.post('/api/login', data)
        return response.data
    },

    async logout(): Promise<void> {
        await http.post('/api/logout')
        localStorage.removeItem('auth_token')
    }
}