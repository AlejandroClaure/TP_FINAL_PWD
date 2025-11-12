<?php include_once '../estructura/cabecera.php'; ?>
<body>

<div class="wrapper d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="auth-content">
    <div class="card shadow-lg border-0" style="width: 400px;">
      <div class="card-body text-center p-4">

        <!-- Botón de cambio tipo toggle -->
        <div class="btn-group mb-4 w-100" role="group">
          <button id="btn-login" class="btn btn-outline-primary active">Login</button>
          <button id="btn-register" class="btn btn-outline-primary">Registrar</button>
        </div>

        <h5 class="mb-4 text-muted" id="form-title">Iniciar sesión</h5>

        <!-- FORMULARIO LOGIN -->
        <form id="login-form" class="text-start">
          <div class="mb-3">
            <label for="login-email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="login-email" placeholder="Ingrese su correo" required>
          </div>
          <div class="mb-3">
            <label for="login-password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="login-password" placeholder="Ingrese su contraseña" required>
          </div>
          <button type="submit" class="btn btn-success w-100">Ingresar</button>
        </form>

        <!-- FORMULARIO REGISTRO -->
        <form id="register-form" class="text-start d-none">
          <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" placeholder="Ingrese nombre" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" placeholder="Ingrese correo" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" placeholder="Ingrese contraseña" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Crear cuenta</button>
        </form>

      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para alternar formularios -->
<script>
const btnLogin = document.getElementById("btn-login");
const btnRegister = document.getElementById("btn-register");
const loginForm = document.getElementById("login-form");
const registerForm = document.getElementById("register-form");
const title = document.getElementById("form-title");

btnLogin.addEventListener("click", () => {
  btnLogin.classList.add("active");
  btnRegister.classList.remove("active");
  loginForm.classList.remove("d-none");
  registerForm.classList.add("d-none");
  title.textContent = "Iniciar sesión";
});

btnRegister.addEventListener("click", () => {
  btnRegister.classList.add("active");
  btnLogin.classList.remove("active");
  registerForm.classList.remove("d-none");
  loginForm.classList.add("d-none");
  title.textContent = "Registrar nuevo usuario";
});
</script>

<style>
.btn-group .btn.active {
  background-color: #0d6efd;
  color: white;
  border-color: #0d6efd;
}
</style>

</body>
<?php include_once '../estructura/pie.php'; ?>
