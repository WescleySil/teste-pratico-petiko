export interface PaginationLink {
    url: string | null
    label: string
    active: boolean
}

export interface PaginationMeta {
    currentPage: number
    lastPage: number
    perPage: number
    total: number
    from: number
    to: number
}