import axios from 'axios'

export const http = axios.create({
    baseURL: "http://backend.vm1.test/api",
    withCredentials: true,
    xsrfCookieName: "XSRF-TOKEN",
    xsrfHeaderName: "X-XSRF-TOKEN",
    headers:{
        "Accept": "application/json",
        "Content-Type": "application/json" 
    }
})

http.interceptors.request.use(config => {
    const xsrfToken = document.cookie
        .split('; ')
        .find(row => row.startsWith('XSRF-TOKEN='))
        ?.split('=')[1];
    if (xsrfToken) {
        config.headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrfToken);
    }
    return config;
});
