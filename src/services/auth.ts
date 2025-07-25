import http from './http'

export const authService = {
    async register(data: any): Promise<any> {
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
        localStorage.removeItem('current_user')
    },


    async getCurrentUser(): Promise<any> {
        try {
            const response = await http.get('/api/me')
            const currentUser = response.data.data

            if (currentUser) {
                localStorage.setItem('current_user', JSON.stringify(currentUser))
                return currentUser
            }
            return null
        } catch (error) {

            return null
        }
    }
}