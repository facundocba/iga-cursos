<template>
  <div class="admin-login">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <div class="card mt-5">
            <div class="card-header bg-primary text-white text-center py-3 " style="background-color: #f36608;"> 
              <h4 class="mb-0">Acceso Administrativo</h4>
            </div>
            <div class="card-body p-4">
              <div v-if="error" class="alert alert-danger" role="alert">
                {{ error }}
              </div>

              <form @submit.prevent="login">
                <div class="mb-3">
                  <label for="email" class="form-label"
                    >Correo Electrónico</label
                  >
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="credentials.email"
                    required
                  />
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Contraseña</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    v-model="credentials.password"
                    required
                  />
                </div>

                <div class="d-grid">
                  <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="procesando"
                  >
                    <span v-if="procesando">
                      <span
                        class="spinner-border spinner-border-sm"
                        role="status"
                        aria-hidden="true"
                      ></span>
                      Iniciando sesión...
                    </span>
                    <span v-else> Iniciar Sesión </span>
                  </button>
                </div>
              </form>
            </div>
            <div class="card-footer text-center py-3 bg-light">
              <router-link to="/" class="text-decoration-none" style="color: #f36608;">
                <i class="fas fa-arrow-left me-1"></i> Volver al sitio
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "AdminLoginView",
  data() {
    return {
      credentials: {
        email: "",
        password: "",
      },
      error: "",
      procesando: false,
    };
  },
  computed: {
    ...mapGetters(["estaAutenticado"]),
  },
  methods: {
    async login() {
      this.error = "";
      this.procesando = true;

      try {
        await this.$store.dispatch("loginAdmin", this.credentials);
        this.$router.push("/admin/dashboard");
      } catch (error) {
        console.error("Error de inicio de sesión:", error);
        if (
          error.response &&
          error.response.data &&
          error.response.data.message
        ) {
          this.error = error.response.data.message;
        } else {
          this.error =
            "Error al iniciar sesión. Por favor, verifica tus credenciales.";
        }
      } finally {
        this.procesando = false;
      }
    },
  },
  created() {
    // Si ya está autenticado, redirigir al dashboard
    if (this.estaAutenticado) {
      this.$router.push("/admin/dashboard");
    }
  },
};
</script>

<style scoped lang="scss">
.admin-login {
  min-height: 100vh;
  display: flex;
  align-items: center;
  background-color: #f8f9fa;
}
</style>
