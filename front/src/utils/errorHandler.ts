import type { Router } from 'vue-router'

export interface ApiError {
    message: string
    status?: number
    code?: string
    data?: any
}

export class ErrorHandler {
    private router: Router

    constructor(router: Router) {
        this.router = router
    }

    handleApiError(error: any): ApiError {
        if (!error.response) {
            return {
                message: 'Network error. Please check your connection and try again.',
                code: 'NETWORK_ERROR'
            }
        }

        const status = error.response.status
        const data = error.response.data

        switch (status) {
            case 401:
                this.handleAuthenticationError()
                return {
                    message: 'Your session has expired. Please log in again.',
                    status,
                    code: 'AUTHENTICATION_ERROR'
                }

            case 403:
                return {
                    message: 'You do not have permission to perform this action.',
                    status,
                    code: 'AUTHORIZATION_ERROR'
                }

            case 404:
                return {
                    message: 'The requested resource was not found.',
                    status,
                    code: 'NOT_FOUND'
                }

            case 422:
                const validationMessage = this.formatValidationErrors(data.errors)
                return {
                    message: validationMessage || 'Please check your input and try again.',
                    status,
                    code: 'VALIDATION_ERROR',
                    data: data.errors
                }

            case 429:
                return {
                    message: 'Too many requests. Please wait a moment and try again.',
                    status,
                    code: 'RATE_LIMIT'
                }

            case 500:
            case 502:
            case 503:
            case 504:
                return {
                    message: 'Server error. Please try again later.',
                    status,
                    code: 'SERVER_ERROR'
                }

            default:
                return {
                    message: data?.message || 'An unexpected error occurred. Please try again.',
                    status,
                    code: 'UNKNOWN_ERROR'
                }
        }
    }

    private handleAuthenticationError(): void {
        localStorage.removeItem('auth_token')
        this.router.push('/login')
    }

    private formatValidationErrors(errors: Record<string, string[]>): string {
        if (!errors) return ''

        const messages = Object.values(errors).flat()
        return messages.join('. ')
    }

    logError(error: any, context?: string): void {

    }
}

let errorHandlerInstance: ErrorHandler | null = null

export const createErrorHandler = (router: Router): ErrorHandler => {
    errorHandlerInstance = new ErrorHandler(router)
    return errorHandlerInstance
}

export const getErrorHandler = (): ErrorHandler => {
    if (!errorHandlerInstance) {
        throw new Error('ErrorHandler not initialized. Call createErrorHandler first.')
    }
    return errorHandlerInstance
}