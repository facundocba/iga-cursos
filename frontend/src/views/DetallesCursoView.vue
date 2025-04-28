<template>
  <div class="detalles-curso">
    <div class="container py-5">
      <div v-if="loading" class="text-center my-5">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
      </div>

      <div v-else-if="!curso" class="alert alert-danger">
        El curso solicitado no existe o ha sido eliminado.
      </div>

      <div v-else class="row">
        <div class="col-lg-5 mb-4 mb-lg-0">
          <div class="curso-imagen">
            <img
              :src="getImagenUrl(curso.imagen)"
              class="img-fluid rounded"
              alt="Imagen del curso"
            />
          </div>
        </div>

        <div class="col-lg-7">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link to="/">Inicio</router-link>
              </li>
              <li class="breadcrumb-item">
                <router-link to="/cursos">Cursos</router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                {{ curso.nombre }}
              </li>
            </ol>
          </nav>

          <h1 class="mb-3">{{ curso.nombre }}</h1>
          <p class="lead mb-4">{{ curso.descripcion }}</p>

          <div class="price-box bg-light p-4 rounded mb-4">
            <div class="d-flex justify-content-between align-items-center">
              <div class="price-tag">
                <span class="fs-3 fw-bold text-primary"
                  >${{ curso.precio }}</span
                >
              </div>
              <div>
                <router-link
                  :to="`/checkout/${curso.id}`"
                  class="btn btn-primary btn-lg"
                >
                  Comprar Ahora
                </router-link>
              </div>
            </div>
          </div>

          <div class="curso-contenido">
            <h3 class="mb-3">Detalles del Curso</h3>
            <div class="curso-detalle">
              {{ curso.detalle }}
            </div>
          </div>
        </div>
      </div>

      <div v-if="curso" class="related-courses mt-5 pt-5 border-top">
        <h3 class="mb-4">Cursos Relacionados</h3>
        <div class="row">
          <div
            v-for="cursoRelacionado in cursosRelacionados"
            :key="cursoRelacionado.id"
            class="col-md-3 mb-4"
          >
            <div class="card h-100">
              <img
                :src="getImagenUrl(cursoRelacionado.imagen)"
                class="card-img-top"
                alt="Imagen del curso"
              />
              <div class="card-body">
                <h5 class="card-title">{{ cursoRelacionado.nombre }}</h5>
                <p class="price">${{ cursoRelacionado.precio }}</p>
              </div>
              <div class="card-footer bg-white border-top-0">
                <router-link
                  :to="`/cursos/${cursoRelacionado.id}`"
                  class="btn btn-outline-primary w-100"
                  >Ver Detalles</router-link
                >
              </div>
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
  name: "DetallesCursoView",
  props: {
    id: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: true,
    };
  },
  computed: {
    ...mapState(["cursos", "cursoSeleccionado"]),
    curso() {
      return this.cursoSeleccionado;
    },
    cursosRelacionados() {
      // Mostrar otros cursos (excluyendo el actual) - máximo 4
      return this.cursos.filter((c) => c.id !== parseInt(this.id)).slice(0, 4);
    },
  },
  methods: {
    getImagenUrl(imagenPath) {
      return imagenPath
        ? `http://localhost:8000/uploads/cursos/${imagenPath}`
        : "http://localhost:8000/uploads/cursos/default.jpg";
    },
    async loadCurso() {
      this.loading = true;
      try {
        await this.$store.dispatch("fetchCurso", this.id);
        // Si no hemos cargado la lista de cursos todavía
        if (this.cursos.length === 0) {
          await this.$store.dispatch("fetchCursos");
        }
      } catch (error) {
        console.error("Error al cargar el curso:", error);
      } finally {
        this.loading = false;
      }
    },
  },
  created() {
    this.loadCurso();
  },
  watch: {
    // Si el ID cambia (por navegación), recargamos el curso
    id() {
      this.loadCurso();
    },
  },
};
</script>

<style scoped lang="scss">
.curso-detalle {
  white-space: pre-line;
}

.price {
  font-weight: bold;
  font-size: 1.2rem;
  color: #007bff;
}
</style>
