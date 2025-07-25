export default {
    baseUrl:
        import.meta.env.MODE === 'development'
            ? 'http://localhost:8000'
            : 'url de produção'
}