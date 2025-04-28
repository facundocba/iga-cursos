<template>
  <div id="app">
    <NavBar v-if="!isAdminRoute" />
    <router-view />
    <AppFooter v-if="!isAdminRoute" />
  </div>
</template>

<script>
import NavBar from "@/components/NavBar.vue";
import AppFooter from "@/components/Footer.vue";

export default {
  name: "App",
  components: {
    NavBar,
    AppFooter,
  },
  computed: {
    isAdminRoute() {
      return this.$route.path.includes("/admin");
    },
  },
};
</script>

<style lang="scss">
// Aquí podrías importar tus estilos globales
// o archivos de Bootstrap si lo prefieres

// Importar Bootstrap
@import "bootstrap/dist/css/bootstrap.min.css";
// Importar Font Awesome (necesitarías agregar esto a tu proyecto)
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css";

body {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.price {
  font-weight: bold;
  color: #007bff;
}
</style>
