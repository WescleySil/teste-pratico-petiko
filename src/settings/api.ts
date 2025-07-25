export default {
    baseUrl:
        import.meta.env.MODE === 'development'
            ? 'http://localhost:8000'
            : 'https://teste-pratico-petiko.onrender.com'
}