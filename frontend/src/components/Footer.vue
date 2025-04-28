<template>
  <footer class="footer mt-auto py-4 bg-dark text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
          <h5>Instituto Gastronómico IGA</h5>
          <p class="text-muted mb-3">
            Transformando la pasión por la cocina en habilidades profesionales
            desde 2010.
          </p>
          <div class="social-icons">
            <a href="#" class="text-white me-3"
              ><i class="fab fa-facebook-f"></i
            ></a>
            <a href="#" class="text-white me-3"
              ><i class="fab fa-instagram"></i
            ></a>
            <a href="#" class="text-white me-3"
              ><i class="fab fa-youtube"></i
            ></a>
            <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
          </div>
        </div>

        <div class="col-md-4 mb-4 mb-md-0">
          <h5>Enlaces Rápidos</h5>
          <ul class="list-unstyled">
            <li class="mb-2">
              <router-link to="/" class="text-muted text-decoration-none"
                >Inicio</router-link
              >
            </li>
            <li class="mb-2">
              <router-link to="/cursos" class="text-muted text-decoration-none"
                >Cursos</router-link
              >
            </li>
            <li class="mb-2">
              <router-link
                to="/mis-compras"
                class="text-muted text-decoration-none"
                >Mis Cursos</router-link
              >
            </li>
            <li class="mb-2">
              <router-link to="/about" class="text-muted text-decoration-none"
                >Acerca de</router-link
              >
            </li>
          </ul>
        </div>

        <div class="col-md-4">
          <h5>Contacto</h5>
          <ul class="list-unstyled text-muted">
            <li class="mb-2">
              <i class="fas fa-map-marker-alt me-2"></i> Ave. Principal 123,
              Ciudad
            </li>
            <li class="mb-2">
              <i class="fas fa-phone me-2"></i> +123 456 7890
            </li>
            <li class="mb-2">
              <i class="fas fa-envelope me-2"></i> info@iga-cursos.com
            </li>
          </ul>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-12 text-center">
          <hr class="bg-secondary" />
          <p class="mb-0 text-muted">
            &copy; {{ new Date().getFullYear() }} Instituto Gastronómico IGA.
            Todos los derechos reservados.
          </p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: "AppFooter", // Cambiado de "Footer" a "AppFooter"
};
</script>

<style scoped lang="scss">
.footer {
  margin-top: 100px;
}

.social-icons {
  a {
    font-size: 1.2rem;

    &:hover {
      opacity: 0.8;
    }
  }
}
</style>
