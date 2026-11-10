import axiosLib from 'axios';

const axios = axiosLib.create({
    baseURL: '',
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
});

export default axios;

export async function getCsrfCookie(): Promise<void> {
    await axios.get('/sanctum/csrf-cookie');
}
