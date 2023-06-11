import axios from 'axios';
axios.defaults.withCredentials = true;

export default defineNuxtRouteMiddleware((to, from) => {
    const auth = useAuthState();
    if (auth.value != null) {
      return navigateTo('/dashboard')
    }
});