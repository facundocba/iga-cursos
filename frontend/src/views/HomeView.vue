<template>
  <div class="home">
    <div class="hero-section">
      <div class="container">
        <h1>Instituto de Gastronomía IGA</h1>
        <p class="lead">
          Descubre nuestra selección de cursos gastronómicos online de alta
          calidad
        </p>
        <router-link to="/cursos" class="btn btn-primary btn-lg"
          >Ver cursos</router-link
        >
      </div>
    </div>

    <div class="container mt-5">
      <h2 class="text-center mb-4">Destacados</h2>
      <div class="row">
        <div v-if="loading" class="col-12 text-center">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
        </div>
        <div v-else-if="cursos.length === 0" class="col-12 text-center">
          <p>No hay cursos disponibles en este momento.</p>
        </div>
        <div
          v-else
          v-for="curso in cursosDestacados"
          :key="curso.id"
          class="col-md-6 col-lg-3 mb-4"
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
                >Ver Detalles</router-link
              >
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-4">
        <router-link to="/cursos" class="btn btn-outline-primary"
          >Ver todos los cursos</router-link
        >
      </div>
    </div>

    <div class="features-section bg-light py-5 mt-5">
      <div class="container">
        <h2 class="text-center mb-5">¿Por qué estudiar con nosotros?</h2>
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="feature-item text-center">
              <div class="feature-icon mb-3">
                <i class="fas fa-chalkboard-teacher fa-3x"></i>
              </div>
              <h4>Profesores Expertos</h4>
              <p>
                Aprende con chefs profesionales y experimentados en la industria
                gastronómica.
              </p>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="feature-item text-center">
              <div class="feature-icon mb-3">
                <i class="fas fa-laptop fa-3x"></i>
              </div>
              <h4>Acceso 24/7</h4>
              <p>
                Estudia a tu propio ritmo y accede al contenido cuando más te
                convenga.
              </p>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="feature-item text-center">
              <div class="feature-icon mb-3">
                <i class="fas fa-certificate fa-3x"></i>
              </div>
              <h4>Certificación</h4>
              <p>
                Recibe un certificado al completar el curso que valida tus
                nuevas habilidades.
              </p>
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
  name: "HomeView",
  data() {
    return {
      loading: true,
    };
  },
  computed: {
    ...mapState(["cursos"]),
    cursosDestacados() {
      // solo 4 cursos en la página home
      return this.cursos.slice(0, 4);
    },
  },
  methods: {
    getImagenUrl(imagenPath) {
      // En un entorno real, esto podría apuntar a una URL de imágenes almacenadas
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
.hero-section {
  background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), #3a6ea5;
  color: white;
  padding: 100px 0;
  text-align: center;

  h1 {
    font-size: 3rem;
    margin-bottom: 20px;
  }

  .lead {
    font-size: 1.5rem;
    margin-bottom: 30px;
  }
}

.price {
  font-weight: bold;
  font-size: 1.2rem;
  color: #f36608;
}

.feature-icon {
  color: #f36608;
}
.hero-section {
  background-image: url('@/assets/hero-section.jpg');  
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}
</style>
