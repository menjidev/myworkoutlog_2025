<?php

require_once __DIR__ . '/../config/Conexion.php';

class Usuario{

    private ?int $id = null;
    private string $nombre_usuario;
    private string $email;
    private string $password_hash;
    private string $estado;
    private string $rol;

    public function __construct(
        ?int $id = null,
        string $nombre_usuario = '',
        string $email = '',
        string $password_hash = '',
        string $estado = 'pendiente',
        string $rol = 'usuario'
    ) {
        $this->id             = $id;
        $this->nombre_usuario = $nombre_usuario;
        $this->email          = $email;
        $this->password_hash  = $password_hash;
        $this->estado         = $estado;
        $this->rol            = $rol;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreUsuario(): string
    {
        return $this->nombre_usuario;
    }

    public function setNombreUsuario(string $nombre): void
    {
        $this->nombre_usuario = $nombre;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    public function setPasswordPlano(string $passwordPlano): void
    {
        $this->password_hash = password_hash($passwordPlano, PASSWORD_DEFAULT);
    }

    public function crear(): bool{

        $pdo = Conexion::conectar();

        $sql = "INSERT INTO usuarios (nombre_usuario, email, password_hash, rol, estado)
                VALUES (:nombre_usuario, :email, :password_hash, :rol, :estado)";

        $stmt = $pdo->prepare($sql);

        $ok = $stmt->execute([
            'nombre_usuario' => $this->nombre_usuario,
            'email'          => $this->email,
            'password_hash'  => $this->password_hash,
            'rol'            => $this->rol,
            'estado'         => $this->estado,
        ]);

        if ($ok) {
            $this->id = (int)$pdo->lastInsertId();
        }

        return $ok;
    }

    public function actualizar(): bool{

        if ($this->id === null) {
            return false; 
        }

        $pdo = Conexion::conectar();

        $sql = "UPDATE usuarios
                SET nombre_usuario = :nombre_usuario,
                    email          = :email,
                    password_hash  = :password_hash,
                    rol            = :rol,
                    estado         = :estado
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'nombre_usuario' => $this->nombre_usuario,
            'email'          => $this->email,
            'password_hash'  => $this->password_hash,
            'rol'            => $this->rol,
            'estado'         => $this->estado,
            'id'             => $this->id,
        ]);
    }

    public function eliminar(): bool{

        if ($this->id === null) {
            return false;
        }

        $pdo = Conexion::conectar();

        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        $ok = $stmt->execute(['id' => $this->id]);

        if ($ok) {
            $this->id = null;
        }

        return $ok;
    }

    public function validarPassword(string $passwordPlano): bool{
        return password_verify($passwordPlano, $this->password_hash);
    }

    
    public static function buscarPorId(int $id): ?Usuario{

        $pdo = Conexion::conectar();

        $sql = "SELECT * FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $fila = $stmt->fetch();

        if ($fila === false) {
            return null;
        }

        return new Usuario(
            (int)$fila['id'],
            $fila['nombre_usuario'],
            $fila['email'],
            $fila['password_hash'],
            $fila['estado'],
            $fila['rol']
        );
    }

   
    public static function buscarPorEmail(string $email): ?Usuario{

        $pdo = Conexion::conectar();

        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $fila = $stmt->fetch();

        if ($fila === false) {
            return null;
        }

        return new Usuario(
            (int)$fila['id'],
            $fila['nombre_usuario'],
            $fila['email'],
            $fila['password_hash'],
            $fila['estado'],
            $fila['rol']
        );
    }

    public static function listarTodos(): array{
        
        $pdo = Conexion::conectar();

        $sql = "SELECT * FROM usuarios ORDER BY id ASC";
        $stmt = $pdo->query($sql);

        $usuarios = [];

        while ($fila = $stmt->fetch()) {
            $usuarios[] = new Usuario(
                (int)$fila['id'],
                $fila['nombre_usuario'],
                $fila['email'],
                $fila['password_hash'],
                $fila['estado'],
                $fila['rol']
            );
        }

        return $usuarios;
    }
}
