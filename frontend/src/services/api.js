import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

// Crear instancia de axios con configuración base
const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Interceptor para agregar el token en las solicitudes
api.interceptors.request.use(config => {
  const token = localStorage.getItem('admin_token')
  if (token) {
    config.headers.Authorization = token
  }
  return config
}, error => {
  return Promise.reject(error)
})

// Interceptor para manejar errores de respuesta
api.interceptors.response.use(
  response => response,
  error => {
    // Manejar errores 401 (no autorizado)
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('admin_token')
      localStorage.removeItem('admin_data')
      window.location.href = '/admin/login'
    }
    return Promise.reject(error)
  }
)

export default api