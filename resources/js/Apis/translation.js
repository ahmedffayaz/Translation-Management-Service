import axios from 'axios'

export const getTranslations = async (filters = {}, page = 1, perPage = 10) => {
    const config = {
        method: 'GET',
        url: `/api/translations`,
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            Accept: 'application/json',
        },
        params: {
            ...filters,
            page: page,
            per_page: perPage,
        },
    }

    try {
        const response = await axios.request(config)
        return {
            data: response.data.data,
        }
    } catch (error) {
        console.error('API error:', error.response || error)
        return { type: "ERROR" }
    }
}

export const storeTranslations = async (form) => {
    const config = {
        method: 'POST',
        url: `/api/store-translations`,
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            Accept: 'application/json',
        },
         data: form,

    }

    try {
        const response = await axios.request(config)
        return {
            data: response.data,
        }
    } catch (error) {
        console.error('API error:', error.response || error)
        return { type: "ERROR" }
    }
}
