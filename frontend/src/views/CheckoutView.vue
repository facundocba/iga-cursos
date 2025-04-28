<template>
  <div class="checkout">
    <div class="container py-5">
      <div class="row">
        <div class="col-lg-8">
          <h1 class="mb-4">Checkout</h1>

          <div v-if="loading" class="text-center my-5">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
          </div>

          <div v-else-if="!curso" class="alert alert-danger">
            El curso solicitado no existe o ha sido eliminado.
          </div>

          <div v-else>
            <div class="card mb-4">
              <div class="card-header bg-primary text-white">
                Información de Compra
              </div>
              <div class="card-body">
                <div class="mb-3 row">
                  <div class="col-md-3">
                    <img
                      :src="getImagenUrl(curso.imagen)"
                      class="img-fluid rounded"
                      alt="Imagen del curso"
                    />
                  </div>
                  <div class="col-md-9">
                    <h5 class="card-title">{{ curso.nombre }}</h5>
                    <p class="card-text">{{ curso.descripcion }}</p>
                    <div class="fw-bold text-primary fs-4">
                      ${{ curso.precio }}
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-header bg-primary text-white">
                Información del Cliente
              </div>
              <div class="card-body">
                <form @submit.prevent="procesarCompra">
                  <div class="mb-3">
                    <label for="nombre" class="form-label"
                      >Nombre Completo</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      id="nombre"
                      v-model="cliente.nombre"
                      required
                    />
                    <div class="invalid-feedback" v-if="errors.nombre">
                      {{ errors.nombre }}
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="email" class="form-label"
                      >Correo Electrónico</label
                    >
                    <input
                      type="email"
                      class="form-control"
                      id="email"
                      v-model="cliente.email"
                      required
                    />
                    <div class="invalid-feedback" v-if="errors.email">
                      {{ errors.email }}
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="telefono" class="form-label"
                      >Número de Teléfono</label
                    >
                    <input
                      type="tel"
                      class="form-control"
                      id="telefono"
                      v-model="cliente.telefono"
                      required
                    />
                    <div class="invalid-feedback" v-if="errors.telefono">
                      {{ errors.telefono }}
                    </div>
                  </div>

                  <div class="form-check mb-3">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="terminos"
                      v-model="aceptaTerminos"
                      required
                    />
                    <label class="form-check-label" for="terminos">
                      Acepto los términos y condiciones
                    </label>
                  </div>

                  <div class="d-grid">
                    <button
                      type="submit"
                      class="btn btn-primary btn-lg"
                      :disabled="procesando || !aceptaTerminos"
                    >
                      <span v-if="procesando">
                        <span
                          class="spinner-border spinner-border-sm"
                          role="status"
                          aria-hidden="true"
                        ></span>
                        Procesando...
                      </span>
                      <span v-else> Completar Compra </span>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
          <div class="card">
            <div class="card-header bg-primary text-white">
              Resumen de la Compra
            </div>
            <div class="card-body">
              <div v-if="curso" class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                  <span>Curso:</span>
                  <span>{{ curso.nombre }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Precio:</span>
                  <span>${{ curso.precio }}</span>
                </div>
                <hr />
                <div class="d-flex justify-content-between fw-bold">
                  <span>Total:</span>
                  <span>${{ curso.precio }}</span>
                </div>
              </div>
              <div v-else class="text-center py-3">
                <div class="spinner-border" role="status" v-if="loading">
                  <span class="visually-hidden">Cargando...</span>
                </div>
                <p v-else class="mb-0">No hay información disponible</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmación -->
    <div
      class="modal fade"
      id="confirmationModal"
      tabindex="-1"
      aria-labelledby="confirmationModalLabel"
      aria-hidden="true"
      ref="confirmationModal"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="confirmationModalLabel">
              ¡Compra Exitosa!
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <p>Tu compra se ha procesado correctamente.</p>
            <p>
              Hemos enviado un correo electrónico a
              <strong>{{ cliente.email }}</strong> con los detalles de acceso al
              curso.
            </p>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
              @click="irAMisCompras"
            >
              Ver Mis Compras
            </button>
            <button
              type="button"
              class="btn btn-primary"
              data-bs-dismiss="modal"
              @click="irACursos"
            >
              Seguir Comprando
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import { Modal } from "bootstrap";

export default {
  name: "CheckoutView",
  props: {
    cursoId: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: true,
      procesando: false,
      confirmationModal: null,
      cliente: {
        nombre: "",
        email: "",
        telefono: "",
      },
      aceptaTerminos: false,
      errors: {
        nombre: "",
        email: "",
        telefono: "",
      },
    };
  },
  computed: {
    ...mapState(["cursoSeleccionado"]),
    curso() {
      return this.cursoSeleccionado;
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
        await this.$store.dispatch("fetchCurso", this.cursoId);

        // Cargar datos del cliente si existen en localStorage
        const clienteInfo = localStorage.getItem("cliente_info");
        if (clienteInfo) {
          this.cliente = JSON.parse(clienteInfo);
        }
      } catch (error) {
        console.error("Error al cargar el curso:", error);
      } finally {
        this.loading = false;
      }
    },
    validarFormulario() {
      let isValid = true;
      this.errors = {
        nombre: "",
        email: "",
        telefono: "",
      };

      if (!this.cliente.nombre || this.cliente.nombre.length < 3) {
        this.errors.nombre = "Por favor ingresa un nombre válido";
        isValid = false;
      }

      if (!this.cliente.email || !this.validateEmail(this.cliente.email)) {
        this.errors.email = "Por favor ingresa un correo electrónico válido";
        isValid = false;
      }

      if (!this.cliente.telefono || this.cliente.telefono.length < 6) {
        this.errors.telefono = "Por favor ingresa un número de teléfono válido";
        isValid = false;
      }

      return isValid;
    },
    validateEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    },
    async procesarCompra() {
      if (!this.validarFormulario()) {
        return;
      }

      this.procesando = true;

      try {
        await this.$store.dispatch("realizarCompra", {
          cliente: this.cliente,
          cursoId: this.cursoId,
        });

        // Mostrar modal de confirmación
        this.confirmationModal.show();
      } catch (error) {
        console.error("Error al procesar la compra:", error);
        alert(
          "Hubo un error al procesar tu compra. Por favor intenta nuevamente."
        );
      } finally {
        this.procesando = false;
      }
    },
    irAMisCompras() {
      this.$router.push("/mis-compras");
    },
    irACursos() {
      this.$router.push("/cursos");
    },
  },
  mounted() {
    // Inicializar el modal de Bootstrap
    this.confirmationModal = new Modal(
      document.getElementById("confirmationModal")
    );
  },
  created() {
    this.loadCurso();
  },
};
</script>

<style scoped lang="scss">
.invalid-feedback {
  display: block;
}
</style>
