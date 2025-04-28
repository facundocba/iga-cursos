import { createStore } from 'vuex'
import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

export default createStore({
  state: {
    cursos: [],
    cursoSeleccionado: null,
    misCompras: [],
    cliente: {
      nombre: '',
      email: '',
      telefono: ''
    },
    adminToken: localStorage.getItem('admin_token') || null,
    adminData: JSON.parse(localStorage.getItem('admin_data')) || null,
    estadisticasCompras: null,
    todasLasCompras: []
  },
  getters: {
    estaAutenticado(state) {
      return !!state.adminToken
    },
    getNombreAdmin(state) {
      return state.adminData ? state.adminData.nombre : ''
    },
    totalCompras(state) {
      return state.estadisticasCompras ? state.estadisticasCompras.total_compras : 0
    },
    ingresosTotales(state) {
      return state.estadisticasCompras ? state.estadisticasCompras.ingresos_totales : 0
    }
  },
  mutations: {
    setCursos(state, cursos) {
      state.cursos = cursos
    },
    setCursoSeleccionado(state, curso) {
      state.cursoSeleccionado = curso
    },
    setMisCompras(state, compras) {
      state.misCompras = compras
    },
    setCliente(state, cliente) {
      state.cliente = { ...cliente }
      // Guardar en localStorage para futuros usos
      localStorage.setItem('cliente_info', JSON.stringify(cliente))
    },
    setAdminToken(state, token) {
      state.adminToken = token
      localStorage.setItem('admin_token', token)
    },
    setAdminData(state, adminData) {
      state.adminData = adminData
      localStorage.setItem('admin_data', JSON.stringify(adminData))
    },
    clearAdminAuth(state) {
      state.adminToken = null
      state.adminData = null
      localStorage.removeItem('admin_token')
      localStorage.removeItem('admin_data')
    },
    setEstadisticasCompras(state, estadisticas) {
      state.estadisticasCompras = estadisticas
    },
    setTodasLasCompras(state, compras) {
      state.todasLasCompras = compras
    }
  },
  actions: {
    // Acciones para cursos
    async fetchCursos({ commit }) {
      try {
        const response = await axios.get(`${API_URL}/cursos`)
        commit('setCursos', response.data.data)
        return response.data.data
      } catch (error) {
        console.error('Error al obtener cursos:', error)
        throw error
      }
    },
    async fetchCurso({ commit }, id) {
      try {
        const response = await axios.get(`${API_URL}/cursos/${id}`)
        commit('setCursoSeleccionado', response.data.data)
        return response.data.data
      } catch (error) {
        console.error(`Error al obtener el curso con ID ${id}:`, error)
        throw error
      }
    },
    
    // Acciones para compras
    async realizarCompra({ commit }, { cliente, cursoId }) {
      try {
        commit('setCliente', cliente)
        const response = await axios.post(`${API_URL}/compras`, {
          cliente: cliente,
          curso_id: cursoId
        })
        return response.data
      } catch (error) {
        console.error('Error al realizar la compra:', error)
        throw error
      }
    },
    async fetchMisCompras({ state, commit }) {
      try {
        const email = state.cliente.email
        if (!email) {
          const clienteInfo = localStorage.getItem('cliente_info')
          if (clienteInfo) {
            const cliente = JSON.parse(clienteInfo)
            commit('setCliente', cliente)
          }
        }
        
        if (!state.cliente.email) {
          return []
        }
        
        const response = await axios.get(`${API_URL}/compras/mis-compras?email=${state.cliente.email}`)
        commit('setMisCompras', response.data.data)
        return response.data.data
      } catch (error) {
        console.error('Error al obtener mis compras:', error)
        throw error
      }
    },
    
    // Acciones para administrador
    async loginAdmin({ commit }, { email, password }) {
      try {
        const response = await axios.post(`${API_URL}/admin/login`, {
          email,
          password
        })
        
        const { token, admin } = response.data
        commit('setAdminToken', token)
        commit('setAdminData', admin)
        return response.data
      } catch (error) {
        console.error('Error en el inicio de sesión:', error)
        throw error
      }
    },
    logout({ commit }) {
      commit('clearAdminAuth')
    },
    async fetchEstadisticasCompras({ commit, state }) {
      try {
        const response = await axios.get(`${API_URL}/compras/estadisticas`, {
          headers: { Authorization: state.adminToken }
        })
        commit('setEstadisticasCompras', response.data.data)
        return response.data.data
      } catch (error) {
        console.error('Error al obtener estadísticas de compras:', error)
        throw error
      }
    },
    async fetchTodasLasCompras({ commit, state }) {
      try {
        const response = await axios.get(`${API_URL}/compras`, {
          headers: { Authorization: state.adminToken }
        })
        commit('setTodasLasCompras', response.data.data)
        return response.data.data
      } catch (error) {
        console.error('Error al obtener todas las compras:', error)
        throw error
      }
    }
  },
  modules: {
  }
})