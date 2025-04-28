<template>
  <div class="admin-dashboard">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <router-link class="navbar-brand" to="/admin/dashboard"
          >IGA Admin</router-link
        >
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a
                class="nav-link"
                href="#"
                @click.prevent="setActiveTab('dashboard')"
                :class="{ active: activeTab === 'dashboard' }"
                >Dashboard</a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                href="#"
                @click.prevent="setActiveTab('ventas')"
                :class="{ active: activeTab === 'ventas' }"
                >Ventas</a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                href="#"
                @click.prevent="setActiveTab('cursos')"
                :class="{ active: activeTab === 'cursos' }"
                >Cursos</a
              >
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="navbarDropdown"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                {{ adminName }}
              </a>
              <ul
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="navbarDropdown"
              >
                <li>
                  <a class="dropdown-item" href="#" @click.prevent="logout"
                    >Cerrar Sesión</a
                  >
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid py-4">
      <!-- Dashboard Tab -->
      <div v-if="activeTab === 'dashboard'">
        <h1 class="mb-4">Dashboard</h1>

        <div class="row mb-4">
          <div class="col-lg-4 mb-3">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="card-title">Total de Ventas</h6>
                    <h3 class="mb-0">
                      {{ estadisticas ? estadisticas.total_compras : 0 }}
                    </h3>
                  </div>
                  <div>
                    <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 mb-3">
            <div class="card bg-success text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="card-title">Ingresos Totales</h6>
                    <h3 class="mb-0">
                      ${{ estadisticas ? estadisticas.ingresos_totales : 0 }}
                    </h3>
                  </div>
                  <div>
                    <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 mb-3">
            <div class="card bg-info text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="card-title">Total de Cursos</h6>
                    <h3 class="mb-0">{{ cursos.length }}</h3>
                  </div>
                  <div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-8">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title mb-0">Ventas por Curso</h5>
              </div>
              <div class="card-body">
                <div v-if="loading" class="text-center my-5">
                  <div class="spinner-border" role="status">
                    <span class="visually-hidden">Cargando...</span>
                  </div>
                </div>
                <div v-else>
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Curso</th>
                          <th>Ventas</th>
                          <th>Ingresos</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(
                            curso, index
                          ) in estadisticas?.compras_por_curso || []"
                          :key="index"
                        >
                          <td>{{ curso.nombre }}</td>
                          <td>{{ curso.total_compras }}</td>
                          <td>${{ curso.ingresos_totales }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title mb-0">Últimas Ventas</h5>
              </div>
              <div class="card-body">
                <div v-if="loading" class="text-center my-5">
                  <div class="spinner-border" role="status">
                    <span class="visually-hidden">Cargando...</span>
                  </div>
                </div>
                <div v-else>
                  <ul class="list-group list-group-flush">
                    <li
                      v-for="(compra, index) in ultimasCompras"
                      :key="index"
                      class="list-group-item px-0"
                    >
                      <div class="d-flex justify-content-between">
                        <div>
                          <strong>{{ compra.cliente_nombre }}</strong
                          ><br />
                          <small class="text-muted">{{
                            compra.curso_nombre
                          }}</small>
                        </div>
                        <div class="text-end">
                          <span
                            class="badge"
                            :class="getStatusBadgeClass(compra.estado)"
                          >
                            {{ capitalizeFirst(compra.estado) }} </span
                          ><br />
                          <small class="text-muted">{{
                            formatDate(compra.fecha_compra)
                          }}</small>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Ventas Tab -->
      <div v-if="activeTab === 'ventas'">
        <h1 class="mb-4">Registro de Ventas</h1>

        <div class="card">
          <div class="card-body">
            <div v-if="loading" class="text-center my-5">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>
            <div v-else>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Cliente</th>
                      <th>Curso</th>
                      <th>Fecha</th>
                      <th>Precio</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="compra in todasLasCompras" :key="compra.id">
                      <td>{{ compra.id }}</td>
                      <td>
                        {{ compra.cliente_nombre }}<br />
                        <small class="text-muted">{{
                          compra.cliente_email
                        }}</small>
                      </td>
                      <td>{{ compra.curso_nombre }}</td>
                      <td>{{ formatDate(compra.fecha_compra) }}</td>
                      <td>${{ compra.curso_precio }}</td>
                      <td>
                        <span
                          class="badge"
                          :class="getStatusBadgeClass(compra.estado)"
                        >
                          {{ capitalizeFirst(compra.estado) }}
                        </span>
                      </td>
                      <td>
                        <div class="btn-group btn-group-sm">
                          <button class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i>
                          </button>
                          <button
                            class="btn btn-outline-success"
                            v-if="compra.estado !== 'completada'"
                          >
                            <i class="fas fa-check"></i>
                          </button>
                          <button
                            class="btn btn-outline-danger"
                            v-if="compra.estado !== 'cancelada'"
                          >
                            <i class="fas fa-times"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Cursos Tab -->
      <div v-if="activeTab === 'cursos'">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1>Administración de Cursos</h1>
          <button class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Curso
          </button>
        </div>

        <div class="card">
          <div class="card-body">
            <div v-if="loading" class="text-center my-5">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>
            <div v-else>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Imagen</th>
                      <th>Nombre</th>
                      <th>Precio</th>
                      <th>Fecha de Creación</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="curso in cursos" :key="curso.id">
                      <td>{{ curso.id }}</td>
                      <td>
                        <img
                          :src="getImagenUrl(curso.imagen)"
                          class="admin-thumbnail"
                          alt="Imagen del curso"
                        />
                      </td>
                      <td>{{ curso.nombre }}</td>
                      <td>${{ curso.precio }}</td>
                      <td>{{ formatDate(curso.created_at) }}</td>
                      <td>
                        <div class="btn-group btn-group-sm">
                          <button class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i>
                          </button>
                          <button class="btn btn-outline-secondary">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";

export default {
  name: "AdminDashboardView",
  data() {
    return {
      activeTab: "dashboard",
      loading: true,
    };
  },
  computed: {
    ...mapState(["estadisticasCompras", "todasLasCompras", "cursos"]),
    ...mapGetters(["getNombreAdmin"]),
    adminName() {
      return this.getNombreAdmin || "Administrador";
    },
    estadisticas() {
      return this.estadisticasCompras;
    },
    ultimasCompras() {
      return this.todasLasCompras.slice(0, 5);
    },
  },
  methods: {
    setActiveTab(tab) {
      this.activeTab = tab;
    },
    logout() {
      this.$store.dispatch("logout");
      this.$router.push("/admin/login");
    },
    formatDate(dateString) {
      const options = {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      };
      return new Date(dateString).toLocaleDateString("es-ES", options);
    },
    getStatusBadgeClass(status) {
      switch (status) {
        case "completada":
          return "bg-success";
        case "pendiente":
          return "bg-warning";
        case "cancelada":
          return "bg-danger";
        default:
          return "bg-secondary";
      }
    },
    capitalizeFirst(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    getImagenUrl(imagenPath) {
      return imagenPath
        ? `http://localhost:8000/uploads/cursos/${imagenPath}`
        : "http://localhost:8000/uploads/cursos/default.jpg";
    },
    async loadData() {
      this.loading = true;
      try {
        await Promise.all([
          this.$store.dispatch("fetchCursos"),
          this.$store.dispatch("fetchTodasLasCompras"),
          this.$store.dispatch("fetchEstadisticasCompras"),
        ]);
      } catch (error) {
        console.error("Error al cargar datos:", error);
      } finally {
        this.loading = false;
      }
    },
  },
  created() {
    this.loadData();
  },
};
</script>

<style scoped lang="scss">
.admin-dashboard {
  min-height: 100vh;
  background-color: #f8f9fa;
}

.card {
  margin-bottom: 1.5rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.admin-thumbnail {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 4px;
}
</style>
