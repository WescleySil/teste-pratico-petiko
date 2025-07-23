export default {
    baseUrl:
        import.meta.env.MODE === 'development'
            ? 'http://localhost:8000'
            : 'https://api.enexe.3esolucoes.com.br'
}