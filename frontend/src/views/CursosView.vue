<template>
  <div class="cursos">
    <div class="page-header bg-light py-4">
      <div class="container">
        <h1>Nuestros Cursos Gastronómicos</h1>
        <p class="lead">
          Explora nuestra variedad de cursos y encuentra el que mejor se adapte
          a tus intereses
        </p>
      </div>
    </div>

    <div class="container mt-5">
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="input-group">
            <input
              type="text"
              class="form-control"
              placeholder="Buscar cursos..."
              v-model="searchQuery"
            />
            <button class="btn btn-outline-secondary" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex justify-content-md-end mt-3 mt-md-0">
            <select class="form-select w-auto" v-model="sortBy">
              <option value="nombre">Ordenar por nombre</option>
              <option value="precio_asc">Precio: Menor a Mayor</option>
              <option value="precio_desc">Precio: Mayor a Menor</option>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div v-if="loading" class="col-12 text-center my-5">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
        </div>
        <div
          v-else-if="cursosFiltrados.length === 0"
          class="col-12 text-center my-5"
        >
          <p>No se encontraron cursos que coincidan con tu búsqueda.</p>
        </div>
        <div
          v-else
          v-for="curso in cursosFiltrados"
          :key="curso.id"
          class="col-md-6 col-lg-4 mb-4"
        >
          <div class="card h-100">
            <img
              :src="getImagenUrl(curso.imagen)"
              class="card-img-top"
              alt="Imagen del curso"
            />
            <div class="card-body">
              <h5 class="card-title">{{ curso.nombre }}</h5>
              <p class="card-text">
                {{ curso.descripcion.substring(0, 100) }}...
              </p>
              <p class="price">${{ curso.precio }}</p>
            </div>
            <div class="card-footer bg-white border-top-0">
              <router-link
                :to="`/cursos/${curso.id}`"
                class="btn btn-outline-primary w-100"
                style="border-color: #f36608; color: #f36608;"
                >Ver Detalles</router-link
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "CursosView",
  data() {
    return {
      loading: true,
      searchQuery: "",
      sortBy: "nombre",
    };
  },
  computed: {
    ...mapState(["cursos"]),
    cursosFiltrados() {
      let resultado = [...this.cursos];

      // Aplicar filtro de búsqueda
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        resultado = resultado.filter(
          (curso) =>
            curso.nombre.toLowerCase().includes(query) ||
            curso.descripcion.toLowerCase().includes(query)
        );
      }

      // Aplicar ordenamiento
      switch (this.sortBy) {
        case "nombre":
          resultado.sort((a, b) => a.nombre.localeCompare(b.nombre));
          break;
        case "precio_asc":
          resultado.sort((a, b) => parseFloat(a.precio) - parseFloat(b.precio));
          break;
        case "precio_desc":
          resultado.sort((a, b) => parseFloat(b.precio) - parseFloat(a.precio));
          break;
      }

      return resultado;
    },
  },
  methods: {
    getImagenUrl(imagenPath) {
      return imagenPath
        ? `http://localhost:8000/uploads/cursos/${imagenPath}`
        : "http://localhost:8000/uploads/cursos/default.jpg";
    },
  },
  async created() {
    try {
      await this.$store.dispatch("fetchCursos");
    } catch (error) {
      console.error("Error al cargar cursos", error);
    } finally {
      this.loading = false;
    }
  },
};
</script>

<style scoped lang="scss">
.page-header {
  text-align: center;
}

.price {
  font-weight: bold;
  font-size: 1.2rem;
  color: #f36608;
}
</style>
