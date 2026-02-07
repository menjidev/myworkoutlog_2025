<?php

require_once __DIR__ . '/../config/Conexion.php';

class Ejercicio{

    private ?int $id = null;
    private ?int $id_usuario = null;     
    private string $nombre_ejercicio;
    private string $grupo_muscular;
    private string $tipo;


    public function __construct(
        ?int $id = null,
        ?int $id_usuario = null,
        string $nombre_ejercicio = '',
        string $grupo_muscular = '',
        string $tipo = ''
    ) {
        $this->id              = $id;
        $this->id_usuario      = $id_usuario;
        $this->nombre_ejercicio = $nombre_ejercicio;
        $this->grupo_muscular  = $grupo_muscular;
        $this->tipo            = $tipo;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsuario(): ?int
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?int $id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }

    public function getNombreEjercicio(): string
    {
        return $this->nombre_ejercicio;
    }

    public function getGrupoMuscular(): string
    {
        return $this->grupo_muscular;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function crear(): bool{

        $pdo = Conexion::conectar();

        $sql = "INSERT INTO ejercicios (id_usuario, nombre_ejercicio, grupo_muscular, tipo)
                VALUES (:id_usuario, :nombre_ejercicio, :grupo_muscular, :tipo)";

        $stmt = $pdo->prepare($sql);

        $ok = $stmt->execute([
            'id_usuario'       => $this->id_usuario,
            'nombre_ejercicio' => $this->nombre_ejercicio,
            'grupo_muscular'   => $this->grupo_muscular,
            'tipo'             => $this->tipo,
        ]);

        if ($ok) {
            $this->id = (int)$pdo->lastInsertId();
        }

        return $ok;
    }

    public function eliminar(int $id_usuario_actual, bool $esAdmin): bool{

        if ($this->id === null) {
            return false;
        }

        if (!$esAdmin && $this->id_usuario !== $id_usuario_actual) {
            return false;
        }

        $pdo = Conexion::conectar();

        $sql = "DELETE FROM ejercicios WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        $ok = $stmt->execute(['id' => $this->id]);

        if ($ok) {
            $this->id = null;
        }

        return $ok;
    }

    public static function listarPorUsuario(int $id_usuario): array{

        $pdo = Conexion::conectar();

        $sql = "SELECT * FROM ejercicios
                WHERE id_usuario IS NULL
                   OR id_usuario = :id_usuario
                ORDER BY grupo_muscular, nombre_ejercicio";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_usuario' => $id_usuario]);

        $ejercicios = [];

        while ($fila = $stmt->fetch()) {
            $ejercicios[] = new Ejercicio(
                (int)$fila['id'],
                $fila['id_usuario'] !== null ? (int)$fila['id_usuario'] : null,
                $fila['nombre_ejercicio'],
                $fila['grupo_muscular'],
                $fila['tipo']
            );
        }

        return $ejercicios;
    }

  
    public static function buscarPorId(int $id, int $id_usuario_actual): ?Ejercicio{

        $pdo = Conexion::conectar();

        $sql = "SELECT * FROM ejercicios
                WHERE id = :id
                  AND (id_usuario IS NULL OR id_usuario = :id_usuario) 
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id'         => $id,
            'id_usuario' => $id_usuario_actual,
        ]);

        $fila = $stmt->fetch();

        if ($fila === false) {
            return null;
        }

        return new Ejercicio(
            (int)$fila['id'],
            $fila['id_usuario'] !== null ? (int)$fila['id_usuario'] : null,
            $fila['nombre_ejercicio'],
            $fila['grupo_muscular'],
            $fila['tipo']
        );
    }

    public static function listarGlobales(): array{

    $pdo = Conexion::conectar();

    $sql = "SELECT * FROM ejercicios
            WHERE id_usuario IS NULL
            ORDER BY grupo_muscular, nombre_ejercicio";

    $stmt = $pdo->query($sql);

    $ejercicios = [];

    while ($fila = $stmt->fetch()) {
        $ejercicios[] = new Ejercicio(
            (int)$fila['id'],
            null,
            $fila['nombre_ejercicio'],
            $fila['grupo_muscular'],
            $fila['tipo']
        );
    }
        return $ejercicios;
    }

    public static function listarCatalogoFiltrado(
        int $id_usuario,
        ?string $nombre = null,
        ?string $grupo = null
    ): array {
        $pdo = Conexion::conectar();

        $sql = "SELECT * FROM ejercicios
                WHERE (id_usuario IS NULL OR id_usuario = :id_usuario)";
        $params = ['id_usuario' => $id_usuario];

        if ($nombre !== null && trim($nombre) !== '') {
            $sql .= " AND nombre_ejercicio LIKE :nombre";
            $params['nombre'] = '%' . trim($nombre) . '%';
        }

        if ($grupo !== null && trim($grupo) !== '' && $grupo !== 'todos') {
            $sql .= " AND grupo_muscular = :grupo";
            $params['grupo'] = $grupo;
        }

        $sql .= " ORDER BY grupo_muscular, nombre_ejercicio";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $ejercicios = [];

        while ($fila = $stmt->fetch()) {
            $ejercicios[] = new Ejercicio(
                (int)$fila['id'],
                $fila['id_usuario'] !== null ? (int)$fila['id_usuario'] : null,
                $fila['nombre_ejercicio'],
                $fila['grupo_muscular'],
                $fila['tipo']
            );
        }

        return $ejercicios;
    }

public static function listarGruposMuscularesCatalogo(int $id_usuario): array{
    
    $pdo = Conexion::conectar();

    $sql = "SELECT DISTINCT grupo_muscular
            FROM ejercicios
            WHERE (id_usuario IS NULL OR id_usuario = :id_usuario)
            ORDER BY grupo_muscular";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_usuario' => $id_usuario]);

    $grupos = [];
    while ($fila = $stmt->fetch()) {
        $grupos[] = $fila['grupo_muscular'];
    }

    return $grupos;
}


}
