import axios from 'axios';

// Set axios globally
window.axios = axios;

// Get the CSRF token from the meta tag in your HTML
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Set the CSRF token in Axios headers
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
axios.defaults.headers.common['Content-Type'] = "application/json";
axios.defaults.headers.common['Accept'] = "application/json";
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true; // Required for Sanctum with cookies

// ✅ Fetch the Sanctum token
const fetchSanctumToken = async () => {
    try {
        // console.log('Attempting to fetch Sanctum token');

        const response = await axios.get(userTokenRoute);
        const token = response.data.token;

        // console.log('Received raw token:', token);

        if (token) {
            // Set the token globally in Axios headers
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            localStorage.setItem("sanctum-token", `Bearer ${token}`);

            // Manually trigger the storage event in the same tab
            const event = new Event('storage');
            event.key = 'sanctum-token';
            event.newValue = `Bearer ${token}`;
            window.dispatchEvent(event);

            // console.log('Token set in Axios from bootstrap.js:', axios.defaults.headers.common['Authorization']);
        } else {
            console.error('No token received from server');
        }
    } catch (error) {
        console.error('Failed to fetch Sanctum token:', error.response || error);
    }
};

fetchSanctumToken();
