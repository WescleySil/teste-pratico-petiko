export const isAuthenticated = (): boolean => {
    const token = localStorage.getItem('auth_token')
    return !!(token && token.trim() !== '')
}

export const getAuthToken = (): string | null => {
    const token = localStorage.getItem('auth_token')
    return token && token.trim() !== '' ? token : null
}

export const setAuthToken = (token: string): void => {
    if (token && token.trim() !== '') {
        localStorage.setItem('auth_token', token)
    }
}

export const getCurrentUser = (): any | null => {
    const userStr = localStorage.getItem('current_user')
    if (!userStr) return null

    try {
        return JSON.parse(userStr)
    } catch {
        return null
    }
}

export const logout = (): void => {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('current_user')
    window.location.href = '/login'
}

export const requiresAuth = (path: string): boolean => {
    const publicRoutes = ['/login', '/register']
    return !publicRoutes.includes(path)
}