<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
      <router-link class="navbar-brand" to="/">
        <img
          src="/img/logo.png"
          alt="IGA Logo"
          height="40"
          class="d-inline-block align-text-top me-2"
        />
        Instituto de Gastronomía IGA
      </router-link>

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
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <router-link class="nav-link" to="/" exact-active-class="active"
              >Inicio</router-link
            >
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/cursos" active-class="active"
              >Cursos</router-link
            >
          </li>
          <li class="nav-item">
            <router-link
              class="nav-link"
              to="/mis-compras"
              active-class="active"
              >Mis Cursos</router-link
            >
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/admin/login">Admin</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "NavBar",
};
</script>

<style scoped lang="scss">
.navbar {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.navbar-brand {
  display: flex;
  align-items: center;
}
</style>
