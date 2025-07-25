import axios from 'axios'
import apiConfig from '../settings/api'
import { getErrorHandler } from '../utils/errorHandler'

const http = axios.create({
    baseURL: apiConfig.baseUrl,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})

http.interceptors.request.use((config) => {
    const token = localStorage.getItem('auth_token')
    if (token && token.trim() !== '') {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

http.interceptors.response.use(
    (response) => response,
    (error) => {
        try {
            const errorHandler = getErrorHandler()
            const apiError = errorHandler.handleApiError(error)

            errorHandler.logError(error, 'HTTP Request')

            return Promise.reject(apiError)
        } catch (handlerError) {


            if (error.response?.status === 401) {
                localStorage.removeItem('auth_token')
                window.location.href = '/login'
            }

            return Promise.reject(error)
        }
    }
)

export default http