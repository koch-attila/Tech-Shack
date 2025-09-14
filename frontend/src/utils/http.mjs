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
