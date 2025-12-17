import { createApp } from 'vue';
import App from './App.vue';
import router from 'vue-router';
import { createPinia } from 'pinia';

const app = createApp(App)
const pinia = createPinia();

app.mount('#app');
