import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/',
});

export default {
    getList(type, search= '') {
        return apiClient.get(`${type}/?search=${search}`);
    },
    getDetails(type, id) {
        return apiClient.get(`${type}/${id}`);
    }
}
