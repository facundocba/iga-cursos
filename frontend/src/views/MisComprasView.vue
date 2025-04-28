<template>
  <div class="mis-compras">
    <div class="container py-5">
      <h1 class="mb-4">Mis Cursos Comprados</h1>

      <div v-if="clienteNoIdentificado" class="cliente-form card mb-4">
        <div class="card-header bg-primary text-white">
          Identifícate para ver tus cursos
        </div>
        <div class="card-body">
          <form @submit.prevent="buscarCompras">
            <div class="mb-3">
              <label for="email" class="form-label">Correo Electrónico</label>
              <input
                type="email"
                class="form-control"
                id="email"
                v-model="clienteEmail"
                required
                placeholder="Ingresa el correo con el que realizaste tus compras"
              />
            </div>
            <div class="d-grid">
              <button
                type="submit"
                class="btn btn-primary"
                :disabled="buscando"
              >
                <span v-if="buscando">
                  <span
                    class="spinner-border spinner-border-sm"
                    role="status"
                    aria-hidden="true"
                  ></span>
                  Buscando...
                </span>
                <span v-else> Buscar Mis Compras </span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <div v-else>
        <div v-if="buscando" class="text-center my-5">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
        </div>

        <div v-else-if="misCompras.length === 0" class="alert alert-info">
          <p class="mb-0">
            No has realizado compras aún. ¡Explora nuestros cursos disponibles!
          </p>
          <router-link to="/cursos" class="btn btn-primary mt-3"
            >Ver Cursos</router-link
          >
        </div>

        <div v-else>
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h5>Cliente: {{ clienteInfo }}</h5>
            </div>
            <div>
              <button
                @click="cambiarCliente"
                class="btn btn-outline-secondary btn-sm"
              >
                Cambiar Cliente
              </button>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-light">
                <tr>
                  <th>Curso</th>
                  <th>Fecha de Compra</th>
                  <th>Precio</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="compra in misCompras" :key="compra.id">
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
                    <button class="btn btn-sm btn-primary">
                      Acceder al Curso
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "MisComprasView",
  data() {
    return {
      clienteNoIdentificado: true,
      buscando: false,
      clienteEmail: "", // Renombrado de 'cliente.email' a 'clienteEmail'
    };
  },
  computed: {
    ...mapState(["misCompras", "cliente"]),
    clienteInfo() {
      return `${this.cliente.nombre} (${this.cliente.email})`;
    },
  },
  methods: {
    async buscarCompras() {
      this.buscando = true;

      try {
        // Actualizar el store con el email proporcionado
        this.$store.commit("setCliente", {
          ...this.cliente,
          email: this.clienteEmail,
        });

        await this.$store.dispatch("fetchMisCompras");
        this.clienteNoIdentificado = false;
      } catch (error) {
        console.error("Error al buscar compras:", error);
        alert(
          "Hubo un error al buscar tus compras. Por favor intenta nuevamente."
        );
      } finally {
        this.buscando = false;
      }
    },
    formatDate(dateString) {
      const options = {
        year: "numeric",
        month: "long",
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
    cambiarCliente() {
      this.clienteNoIdentificado = true;
      this.clienteEmail = "";
    },
  },
  async created() {
    // Verificar si ya tenemos información del cliente
    const clienteInfo = localStorage.getItem("cliente_info");

    if (clienteInfo) {
      const clienteData = JSON.parse(clienteInfo);
      // Actualizar el store con la información del cliente
      this.$store.commit("setCliente", clienteData);
      this.clienteEmail = clienteData.email;

      // Buscar compras automáticamente
      this.buscando = true;
      try {
        await this.$store.dispatch("fetchMisCompras");
        this.clienteNoIdentificado = false;
      } catch (error) {
        console.error("Error al buscar compras:", error);
      } finally {
        this.buscando = false;
      }
    }
  },
};
</script>

<style scoped lang="scss">
.badge {
  padding: 0.5em 0.7em;
}
</style>
