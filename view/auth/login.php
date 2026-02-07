<?php
$tituloPagina = 'Login - MyWorkoutLog';
require __DIR__ . '/../partials/header.php';
?>
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <main class="auth-contenedor">
    <div class="auth-contenedor__logo">
        <div class="auth-contenedor__logo-circulo">
            <img class="auth-contenedor__logo-imagen" 
            src="public/img/MyWorkoutLog_Light.svg" 
            alt="MyWorkoutLog">
        </div>
    </div>

    <section class="auth-card">
        <form
            class="auth-formulario"
            action="index.php?controller=auth&action=procesarLogin"
            method="post"
        >
            <div class="auth-form__label-input">
                <label class="auth-form__label" for="email">Email</label>
                <input
                    class="auth-form__input"
                    type="email"
                    id="email"
                    name="email"
                    required
                >
            </div>

            <div class="auth-form__label-input">
                <label class="auth-form__label" for="password">Contraseña</label>
                <input
                    class="auth-form__input"
                    type="password"
                    id="password"
                    name="password"
                    required
                >
            </div>

            <button class="auth-form__button" type="submit">
                Entrar
            </button>
        </form>
    </section>

    <p class="auth-contenedor__registro">
        ¿No tienes cuenta?
        <a
            class="auth-contenedor__registro-link"
            href="index.php?controller=auth&action=registro"
        >
            Regístrate
        </a>
    </p>
<?php require __DIR__ . '/../partials/footer.php';?>