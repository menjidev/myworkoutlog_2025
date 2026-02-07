<?php
$tituloPagina = 'Registro - MyWorkoutLog';
require __DIR__ . '/../partials/header.php';
?>

<?php if (!empty($error)): ?>
    <p class="auth-page__error"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php if (!empty($ok)): ?>
    <p class="auth-page__success"><?php echo htmlspecialchars($ok); ?></p>
<?php endif; ?>

<main class="auth-contenedor auth-contenedor--login">
    <div class="auth-contenedor__logo">
        <div class="auth-contenedor__logo-circulo">
            <img
                src="public/img/MyWorkoutLog_Light.svg"
                alt="MyWorkoutLog"
                class="auth-contenedor__logo-imagen"
            >
        </div>
    </div>

    <section class="auth-card">
        <form
            class="auth-formulario"
            action="index.php?controller=auth&action=procesarRegistro"
            method="post"
        >
            <div class="auth-form__label-input">
                <label class="auth-form__label" for="nombre_usuario">Nombre de usuario</label>
                <input
                    class="auth-form__input"
                    type="text"
                    id="nombre_usuario"
                    name="nombre_usuario"
                    required
                >
            </div>

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

            <div class="auth-form__label-input">
                <label class="auth-form__label" for="password2">Repite la contraseña</label>
                <input
                    class="auth-form__input"
                    type="password"
                    id="password2"
                    name="password2"
                    required
                >
            </div>

            <button class="auth-form__button" type="submit">
                Crear cuenta
            </button>
        </form>
    </section>

    <p class="auth-contenedor__registro">
        ¿Ya tienes cuenta?
        <a
            class="auth-contenedor__registro-link"
            href="index.php?controller=auth&action=login"
        >
            Inicia sesión
        </a>
    </p>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
