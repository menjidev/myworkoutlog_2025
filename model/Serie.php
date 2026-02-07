<?php

require_once __DIR__ . '/../config/Conexion.php';

class Serie{

    public const LAB_REPS_MIN      = 8;
    public const LAB_REPS_MAX      = 12;
    public const LAB_INCREMENTO_KG = 2.5;
 
    private ?int $id = null;
    private int $id_entrenamiento;
    private int $id_ejercicio;
    private int $num_serie;
    private int $repeticiones;
    private ?float $peso_kg;
    private ?int $descanso_seg;
    private ?string $notas;

    public function __construct(
        ?int $id = null,
        int $id_entrenamiento = 0,
        int $id_ejercicio = 0,
        int $num_serie = 1,
        int $repeticiones = 0,
        ?float $peso_kg = null,
        ?int $descanso_seg = null,
        ?string $notas = null
    ) {
        $this->id               = $id;
        $this->id_entrenamiento = $id_entrenamiento;
        $this->id_ejercicio     = $id_ejercicio;
        $this->num_serie        = $num_serie;
        $this->repeticiones     = $repeticiones;
        $this->peso_kg          = $peso_kg;
        $this->descanso_seg     = $descanso_seg;
        $this->notas            = $notas;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEjercicio(): int
    {
        return $this->id_ejercicio;
    }

    public function setIdEjercicio(int $id_ejercicio): void
    {
        $this->id_ejercicio = $id_ejercicio;
    }

    public function getNumSerie(): int
    {
        return $this->num_serie;
    }

    public function setNumSerie(int $num_serie): void
    {
        $this->num_serie = $num_serie;
    }

    public function getRepeticiones(): int
    {
        return $this->repeticiones;
    }

    public function setRepeticiones(int $repeticiones): void
    {
        $this->repeticiones = $repeticiones;
    }

    public function getPesoKg(): ?float
    {
        return $this->peso_kg;
    }

    public function setPesoKg(?float $peso_kg): void
    {
        $this->peso_kg = $peso_kg;
    }

    public function getDescansoSeg(): ?int
    {
        return $this->descanso_seg;
    }

    public function setDescansoSeg(?int $descanso_seg): void
    {
        $this->descanso_seg = $descanso_seg;
    }

    public function getNotas(): ?string
    {
        return $this->notas;
    }

    public function setNotas(?string $notas): void
    {
        $this->notas = $notas;
    }

 
    public function crear(): bool{

        $pdo = Conexion::conectar();

        $sql = "INSERT INTO series 
                    (id_entrenamiento, id_ejercicio, num_serie, descanso_seg, repeticiones, peso_kg, notas)
                VALUES 
                    (:id_entrenamiento, :id_ejercicio, :num_serie, :descanso_seg, :repeticiones, :peso_kg, :notas)";

        $stmt = $pdo->prepare($sql);

        $ok = $stmt->execute([
            'id_entrenamiento' => $this->id_entrenamiento,
            'id_ejercicio'     => $this->id_ejercicio,
            'num_serie'        => $this->num_serie,
            'descanso_seg'     => $this->descanso_seg,
            'repeticiones'     => $this->repeticiones,
            'peso_kg'          => $this->peso_kg,
            'notas'            => $this->notas,
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

        $sql = "UPDATE series
                SET id_entrenamiento = :id_entrenamiento,
                    id_ejercicio     = :id_ejercicio,
                    num_serie        = :num_serie,
                    descanso_seg     = :descanso_seg,
                    repeticiones     = :repeticiones,
                    peso_kg          = :peso_kg,
                    notas            = :notas
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id_entrenamiento' => $this->id_entrenamiento,
            'id_ejercicio'     => $this->id_ejercicio,
            'num_serie'        => $this->num_serie,
            'descanso_seg'     => $this->descanso_seg,
            'repeticiones'     => $this->repeticiones,
            'peso_kg'          => $this->peso_kg,
            'notas'            => $this->notas,
            'id'               => $this->id,
        ]);
    }

    public function eliminar(): bool{

        if ($this->id === null) {
            return false;
        }

        $pdo = Conexion::conectar();

        $sql = "DELETE FROM series WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        $ok = $stmt->execute(['id' => $this->id]);

        if ($ok) {
            $this->id = null;
        }

        return $ok;
    }

    public static function listarPorEntrenamiento(int $id_entrenamiento, int $id_usuario): array{

        $pdo = Conexion::conectar();

        $sql = "SELECT s.*
                FROM series s
                JOIN entrenamientos e ON s.id_entrenamiento = e.id
                WHERE s.id_entrenamiento = :id_entrenamiento
                  AND e.id_usuario = :id_usuario
                ORDER BY s.num_serie";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id_entrenamiento' => $id_entrenamiento,
            'id_usuario'       => $id_usuario,
        ]);

        $series = [];

        while ($fila = $stmt->fetch()) {
            $series[] = new Serie(
                (int)$fila['id'],
                (int)$fila['id_entrenamiento'],
                (int)$fila['id_ejercicio'],
                (int)$fila['num_serie'],
                (int)$fila['repeticiones'],
                $fila['peso_kg'] !== null ? (float)$fila['peso_kg'] : null,
                $fila['descanso_seg'] !== null ? (int)$fila['descanso_seg'] : null,
                $fila['notas'] !== null ? (string)$fila['notas'] : null
            );
        }

        return $series;
    }
        
    public static function eliminarPorEntrenamientoYEjercicio(int $idEntrenamiento, int $idEjercicio): bool{

        $pdo = Conexion::conectar();

        $sql = "DELETE FROM series
                WHERE id_entrenamiento = :id_entrenamiento
                AND id_ejercicio     = :id_ejercicio";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id_entrenamiento' => $idEntrenamiento,
            ':id_ejercicio'     => $idEjercicio,
        ]);
    }


    public static function contarSeriesPorUsuarioYRango(
    int $id_usuario,
    ?string $desde,
    ?string $hasta
    ): int {
        $pdo = Conexion::conectar();

        $sql = "SELECT COUNT(*) AS total
                FROM series s
                JOIN entrenamientos e ON s.id_entrenamiento = e.id
                WHERE e.id_usuario = :id_usuario";

        $params = ['id_usuario' => $id_usuario];

        if ($desde !== null) {
            $sql .= " AND e.fecha >= :desde";
            $params['desde'] = $desde;
        }

        if ($hasta !== null) {
            $sql .= " AND e.fecha <= :hasta";
            $params['hasta'] = $hasta;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $fila = $stmt->fetch();

        return $fila ? (int)$fila['total'] : 0;
    }

    public static function ejercicioConMasPeso(
        int $id_usuario,
        ?string $desde,
        ?string $hasta,
        ?string $grupoMuscular = null
    ): ?array {
        $pdo = Conexion::conectar();

        $sql = "SELECT s.id_ejercicio,
                    MAX(s.peso_kg) AS max_peso,
                    ej.nombre_ejercicio
                FROM series s
                JOIN entrenamientos e ON s.id_entrenamiento = e.id
                JOIN ejercicios ej    ON s.id_ejercicio = ej.id
                WHERE e.id_usuario = :id_usuario
                AND s.peso_kg IS NOT NULL";

        $params = ['id_usuario' => $id_usuario];

        if ($desde !== null) {
            $sql .= " AND e.fecha >= :desde";
            $params['desde'] = $desde;
        }

        if ($hasta !== null) {
            $sql .= " AND e.fecha <= :hasta";
            $params['hasta'] = $hasta;
        }

        if ($grupoMuscular !== null && $grupoMuscular !== '') {
            $sql .= " AND ej.grupo_muscular = :grupo_muscular";
            $params['grupo_muscular'] = $grupoMuscular;
        }

        $sql .= " GROUP BY s.id_ejercicio, ej.nombre_ejercicio
                ORDER BY max_peso DESC
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $fila = $stmt->fetch();

        if (!$fila) {
            return null;
        }

        return [
            'id_ejercicio'      => (int)$fila['id_ejercicio'],
            'nombre_ejercicio'  => $fila['nombre_ejercicio'],
            'max_peso'          => (float)$fila['max_peso'],
        ];
    }

    public static function ejerciciosMasFrecuentes(
        int $id_usuario,
        ?string $desde,
        ?string $hasta,
        int $limite = 5
    ): array {
        $pdo = Conexion::conectar();

        $sql = "SELECT s.id_ejercicio,
                    ej.nombre_ejercicio,
                    COUNT(DISTINCT s.id_entrenamiento) AS total_entrenamientos
                FROM series s
                JOIN entrenamientos e ON s.id_entrenamiento = e.id
                JOIN ejercicios ej    ON s.id_ejercicio = ej.id
                WHERE e.id_usuario = :id_usuario";

        $params = ['id_usuario' => $id_usuario];

        if ($desde !== null) {
            $sql .= " AND e.fecha >= :desde";
            $params['desde'] = $desde;
        }

        if ($hasta !== null) {
            $sql .= " AND e.fecha <= :hasta";
            $params['hasta'] = $hasta;
        }

        $sql .= " GROUP BY s.id_ejercicio, ej.nombre_ejercicio
                ORDER BY total_entrenamientos DESC
                LIMIT " . (int)$limite;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $resultado = [];
        while ($fila = $stmt->fetch()) {
            $resultado[] = [
                'id_ejercicio'         => (int)$fila['id_ejercicio'],
                'nombre_ejercicio'     => $fila['nombre_ejercicio'],
                'total_entrenamientos' => (int)$fila['total_entrenamientos'],
            ];
        }

        return $resultado;
    }


    public static function contarDiasPorGrupoMuscular(
        int $idUsuario,
        ?string $desde = null,
        ?string $hasta = null
    ): array {
        $pdo = Conexion::conectar();

        $sql = "
            SELECT ex.grupo_muscular,
                COUNT(DISTINCT e.fecha) AS total_dias
            FROM series s
            INNER JOIN entrenamientos e ON s.id_entrenamiento = e.id
            INNER JOIN ejercicios ex     ON s.id_ejercicio     = ex.id
            WHERE e.id_usuario = :id_usuario
        ";

        $params = [
            ':id_usuario' => $idUsuario,
        ];

        if ($desde !== null && $desde !== '') {
            $sql .= " AND e.fecha >= :desde";
            $params[':desde'] = $desde;
        }

        if ($hasta !== null && $hasta !== '') {
            $sql .= " AND e.fecha <= :hasta";
            $params[':hasta'] = $hasta;
        }

        $sql .= "
            GROUP BY ex.grupo_muscular
            ORDER BY ex.grupo_muscular
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function recomendarPesoInicial(
        int $idUsuario,
        int $idEjercicio,
        int $idEntrenamientoActual
    ): ?array {
        $pdo = Conexion::conectar();

        $sqlUltimo = "
            SELECT e.id
            FROM entrenamientos e
            INNER JOIN series s ON s.id_entrenamiento = e.id
            WHERE e.id_usuario   = :id_usuario
            AND s.id_ejercicio = :id_ejercicio
            AND e.id          != :id_entrenamiento_actual
            ORDER BY e.fecha DESC, e.id DESC
            LIMIT 1
        ";
        $stmtUltimo = $pdo->prepare($sqlUltimo);
        $stmtUltimo->execute([
            ':id_usuario'              => $idUsuario,
            ':id_ejercicio'            => $idEjercicio,
            ':id_entrenamiento_actual' => $idEntrenamientoActual,
        ]);
        $filaUltimo = $stmtUltimo->fetch();

        if (!$filaUltimo) {
            return null;
        }

        $idUltimoEntrenamiento = (int)$filaUltimo['id'];

        $sqlSeries = "
            SELECT s.*
            FROM series s
            WHERE s.id_entrenamiento = :id_entrenamiento
            AND s.id_ejercicio     = :id_ejercicio
            ORDER BY s.num_serie ASC
        ";
        $stmtSeries = $pdo->prepare($sqlSeries);
        $stmtSeries->execute([
            ':id_entrenamiento' => $idUltimoEntrenamiento,
            ':id_ejercicio'     => $idEjercicio,
        ]);
        $series = $stmtSeries->fetchAll();

        if (!$series) {
            return null;
        }

        $detalleSeries = [];
        foreach ($series as $fila) {
            $detalleSeries[] = [
                'reps' => (int)$fila['repeticiones'],
                'peso' => (float)$fila['peso_kg'],
            ];
        }

        $ultimaFila = end($series);
        $repsUltima = (int)$ultimaFila['repeticiones'];
        $pesoUltima = (float)$ultimaFila['peso_kg'];

        $decision        = 'mantener';
        $pesoRecomendado = $pesoUltima;

        if ($repsUltima >= 11) {
            $decision        = 'subir';
            $pesoRecomendado = $pesoUltima + self::LAB_INCREMENTO_KG;
        } elseif ($repsUltima >= self::LAB_REPS_MIN) {
            $decision        = 'mantener';
            $pesoRecomendado = $pesoUltima;
        } else {
            $decision        = 'bajar';
            $pesoRecomendado = max(0, $pesoUltima - self::LAB_INCREMENTO_KG);
        }

        return [
            'decision'           => $decision,
            'peso_base'          => $pesoUltima,        
            'peso_recomendado'   => $pesoRecomendado,     
            'reps_objetivo_min'  => self::LAB_REPS_MIN,  
            'reps_objetivo_max'  => self::LAB_REPS_MAX,   
            'detalle_series'     => $detalleSeries,      
        ];
    }

    
    public static function recomendarDesdeSerieActual(
    int $repsActuales,
    float $pesoActual
    ): array {
        $decision        = 'mantener';
        $pesoRecomendado = $pesoActual;

        if ($repsActuales >= 11) {
            $decision        = 'subir';
            $pesoRecomendado = $pesoActual + self::LAB_INCREMENTO_KG;

        } elseif ($repsActuales >= self::LAB_REPS_MIN) { 
            $decision        = 'mantener';
            $pesoRecomendado = $pesoActual;

        } else { 
            $decision        = 'bajar';
            $pesoRecomendado = max(0, $pesoActual - self::LAB_INCREMENTO_KG);
        }

        return [
            'decision'          => $decision,
            'peso_actual'       => $pesoActual,
            'reps_actuales'     => $repsActuales,
            'peso_recomendado'  => $pesoRecomendado,
            'reps_objetivo_min' => self::LAB_REPS_MIN, 
            'reps_objetivo_max' => self::LAB_REPS_MAX,
        ];
    }
}