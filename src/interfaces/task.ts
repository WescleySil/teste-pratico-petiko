export type TaskStatus = 'pending' | 'overdue' | 'completed';
export interface Task {
    id: number;
    title: string;
    description: string | null;
    status: TaskStatus;
    due_date: string;
    created_at: string;
    user_id: number;
    user?: {
        id: number;
        name: string;
        username: string;
        email: string;
    };
}

export interface TaskResponse {
    data: Task[];
}

export interface SingleTaskResponse {
    data: Task;
}