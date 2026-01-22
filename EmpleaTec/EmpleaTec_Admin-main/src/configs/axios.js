import axios from 'axios';

export const api = axios.create({
  baseURL: 'http://localhost:3000/api/',
  timeout: 10000, 
  headers: { 'Content-Type': 'application/json' },
});

// Interceptor para manejar respuestas de error globalmente
api.interceptors.response.use(
  (response) => response, 
  (error) => {
    
    if (error.response) {
      console.error('Error response:', error.response.data);
      console.error('Error status:', error.response.status);
      console.error('Error headers:', error.response.headers);
      
      if (error.response.status === 401) {
        alert('No autorizado. Por favor inicia sesión.');
      } else if (error.response.status === 500) {
        alert('Error del servidor. Intenta más tarde.');
      }
    } else if (error.request) {
      console.error('Error request:', error.request);
      alert('No se pudo conectar al servidor. Verifica tu conexión.');
    } else {
      console.error('Error message:', error.message);
      alert('Ocurrió un error desconocido.');
    }
    
    return Promise.reject(error);
  }
);

export default api;
