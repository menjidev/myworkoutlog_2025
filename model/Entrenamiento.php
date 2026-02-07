<?php

require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/Serie.php';

class Entrenamiento
{

    private ?int $id = null;
    private int $id_usuario;
    private string $fecha;              
    private string $nombre_entrenamiento;
    private ?string $notas;

    public function __construct(
        ?int $id = null,
        int $id_usuario = 0,
        string $fecha = '',
        string $nombre_entrenamiento = '',
        ?string $notas = null
    ) {
        $this->id                  = $id;
        $this->id_usuario          = $id_usuario;
        $this->fecha               = $fecha;
        $this->nombre_entrenamiento = $nombre_entrenamiento;
        $this->notas               = $notas;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsuario(): int
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(int $id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }

    public function getFecha(): string
    {
        return $this->fecha;
    }

    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getNombreEntrenamiento(): string
    {
        return $this->nombre_entrenamiento;
    }

    public function setNombreEntrenamiento(string $nombre): void
    {
        $this->nombre_entrenamiento = $nombre;
    }

    public function getNotas(): ?string
    {
        return $this->notas;
    }

    public function setNotas(?string $notas): void
    {
        $this->notas = $notas;
    }

    public function crear(): bool
    {
        $pdo = Conexion::conectar();

        $sql = "INSERT INTO entrenamientos (id_usuario, fecha, nombre_entrenamiento, notas)
                VALUES (:id_usuario, :fecha, :nombre_entrenamiento, :notas)";

        $stmt = $pdo->prepare($sql);

        $ok = $stmt->execute([
            'id_usuario'           => $this->id_usuario,
            'fecha'                => $this->fecha,
            'nombre_entrenamiento' => $this->nombre_entrenamiento,
            'notas'                => $this->notas,
        ]);

        if ($ok) {
            $this->id = (int)$pdo->lastInsertId();
        }

        return $ok;
    }

    public function actualizar(): bool
    {
        if ($this->id === null) {
            return false;
        }

        $pdo = Conexion::conectar();

        $sql = "UPDATE entrenamientos
                SET id_usuario           = :id_usuario,
                    fecha                = :fecha,
                    nombre_entrenamiento = :nombre_entrenamiento,
                    notas                = :notas
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id_usuario'           => $this->id_usuario,
            'fecha'                => $this->fecha,
            'nombre_entrenamiento' => $this->nombre_entrenamiento,
            'notas'                => $this->notas,
            'id'                   => $this->id,
        ]);
    }

    public function eliminar(): bool
    {
        if ($this->id === null) {
            return false;
        }

        $pdo = Conexion::conectar();

        $sql = "DELETE FROM entrenamientos WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        $ok = $stmt->execute(['id' => $this->id]);

        if ($ok) {
            $this->id = null;
        }

        return $ok;
    }

    public static function listarPorUsuario(
        int $id_usuario,
        ?string $desde = null,
        ?string $hasta = null
    ): array {
        $pdo = Conexion::conectar();

        $sql = "SELECT * FROM entrenamientos
                WHERE id_usuario = :id_usuario";
        $params = ['id_usuario' => $id_usuario];

        if ($desde !== null && $desde !== '') {
            $sql .= " AND fecha >= :desde";
            $params['desde'] = $desde;
        }

        if ($hasta !== null && $hasta !== '') {
            $sql .= " AND fecha <= :hasta";
            $params['hasta'] = $hasta;
        }

        $sql .= " ORDER BY fecha DESC, id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $entrenamientos = [];

        while ($fila = $stmt->fetch()) {
            $entrenamientos[] = new Entrenamiento(
                (int)$fila['id'],
                (int)$fila['id_usuario'],
                $fila['fecha'],
                $fila['nombre_entrenamiento'],
                $fila['notas'] !== null ? (string)$fila['notas'] : null
            );
        }

        return $entrenamientos;
    }

    public static function buscarPorId(int $id, int $id_usuario): ?Entrenamiento
    {
        $pdo = Conexion::conectar();

        $sql = "SELECT * FROM entrenamientos
                WHERE id = :id
                  AND id_usuario = :id_usuario
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id'         => $id,
            'id_usuario' => $id_usuario,
        ]);

        $fila = $stmt->fetch();

        if ($fila === false) {
            return null;
        }

        return new Entrenamiento(
            (int)$fila['id'],
            (int)$fila['id_usuario'],
            $fila['fecha'],
            $fila['nombre_entrenamiento'],
            $fila['notas'] !== null ? (string)$fila['notas'] : null
        );
    }
    public static function listarPorMes(
        int $idUsuario,
        int $year,
        int $month,
        ?string $busqueda = null
    ): array {
        $pdo = Conexion::conectar();

        $primerDia = sprintf('%04d-%02d-01', $year, $month);
        $ultimoDia = date('Y-m-t', strtotime($primerDia));

        if ($busqueda === null || $busqueda === '') {
            $sql = "
                SELECT e.id,
                    e.fecha,
                    e.nombre_entrenamiento
                FROM entrenamientos e
                WHERE e.id_usuario = :id_usuario
                AND e.fecha BETWEEN :desde AND :hasta
                ORDER BY e.fecha ASC, e.id ASC
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $idUsuario,
                ':desde'      => $primerDia,
                ':hasta'      => $ultimoDia,
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $sql = "
            SELECT DISTINCT e.id,
                            e.fecha,
                            e.nombre_entrenamiento
            FROM entrenamientos e
            INNER JOIN series s     ON s.id_entrenamiento = e.id
            INNER JOIN ejercicios ej ON s.id_ejercicio    = ej.id
            WHERE e.id_usuario = :id_usuario
            AND e.fecha BETWEEN :desde AND :hasta
            AND (
                    ej.nombre_ejercicio   LIKE :busqueda
                OR ej.grupo_muscular     LIKE :busqueda
            )
            ORDER BY e.fecha ASC, e.id ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_usuario' => $idUsuario,
            ':desde'      => $primerDia,
            ':hasta'      => $ultimoDia,
            ':busqueda'   => '%' . $busqueda . '%',
        ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
