import axios from 'axios'

export const login = async (credentials) => {
    const config = {
        method: 'POST',
        url: `/api/login`,
        data: credentials,
    }
    try {
        const response = await axios.request(config)
        return response
    } catch (error) {
        console.error('Login error:', error)
        return { type: "ERROR" }
    }
}

export const register = async (credentials) => {
    const config = {
        method: 'POST',
        url: `/api/register`,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        data: credentials,
    }
    try {
        const response = await axios.request(config)
        return response.data
    } catch (error) {
        console.error('Login error:', error)
        return { type: "ERROR" }
    }
}

export const logout = async () => {
    const config = {
        method: 'POST',
        url: `/api/logout`,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
             Authorization: `Bearer ${localStorage.getItem('token')}`
        },
    }
    try {
        const response = await axios.request(config)
        return response.data
    } catch (error) {
        console.error('Login error:', error)
        return { type: "ERROR" }
    }
}