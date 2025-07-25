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
    is_admin?: boolean
}