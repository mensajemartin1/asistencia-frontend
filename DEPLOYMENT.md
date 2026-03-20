# Guía de Deployment - Sistema de Asistencia

## 🚀 Inicio rápido con Docker

### Requisitos previos
- Docker y Docker Compose instalados
- Git configurado

### Pasos para levantar el proyecto

#### 1. Clonar el repositorio
```bash
git clone <repository-url>
cd asistencia-frontend
```

#### 2. Configurar variables de entorno
```bash
# Copiar el archivo de ejemplo
cp .env.example .env

# Las variables están preconfiguradas para Docker:
# DB_HOST=db (servicio MySQL en Docker)
# DB_USER=itsz
# DB_PASS=itsz123
# APP_URL=http://127.0.0.1:3000
```

#### 3. Levantar los servicios
```bash
docker compose up -d --build
```

Esto levanta:
- **PHP App** en `http://127.0.0.1:3000`
- **MySQL** en puerto 3307
- **phpMyAdmin** en `http://127.0.0.1:8080`

#### 4. Ejecutar script de seeding (llenar BD)
```bash
# PowerShell (Windows)
Get-Content database/seed_dev.sql -Raw | docker exec -i itsz_asistencia_db mysql -uroot -proot

# Bash (Linux/Mac)
cat database/seed_dev.sql | docker exec -i itsz_asistencia_db mysql -uroot -proot
```

#### 5. Verificar la instalación
```bash
# Ver logs del PHP
docker compose logs app

# Entrar a base de datos
docker exec -it itsz_asistencia_db mysql -uitsz -pitsz123 -e "USE Zongolica; SHOW TABLES;"
```

---

## 👤 Usuarios de prueba disponibles

Todos con contraseña: **`itsz12345`**

| Correo | Rol | Acceso |
|--------|-----|--------|
| admin@zongolica.tecnm.mx | admin | Panel administrativo |
| docente@zongolica.tecnm.mx | docente | Módulo de asistencia |
| control@zongolica.tecnm.mx | control_escolar | Módulo de consultas |
| alumno@zongolica.tecnm.mx | estudiante | Portal de estudiante |

---

## 📁 Estructura de datos

### Datos iniciales seeding:
- **Grupo**: 803 (Ingeniería en Sistemas, Semestre 8, Campus Zongolica)
- **Materia**: Programación Web (ISC-803-PW)
- **Horario**: 00:00:00 - 23:59:59 (todas las horas, útil en desarrollo)
- **Alumno**: Enlazado al grupo 803

---

## 🔧 Cambios de schema v3

### Migraciones principales realizadas:
1. **Materias → GruposMaterias**: Los horarios (horaInicio/horaFin) se movieron a tabla de relaciones
2. **Alumnos.grupo (string) → Alumnos.idGrupo (FK)**: Normalización de claves foráneas
3. **Estados de asistencia**: Ahora usa enum (presente, retardo, falta, ausente)

### Archivos actualizados:
- `modules/attendance/controllers/guardar_asistencia.php`
- `modules/attendance/controllers/obtener_asistencia.php`
- `modules/attendance/views/index.php`
- `modules/queries/controllers/consultasModel.php`
- `modules/queries/controllers/grafica_grupo.php`
- `src/js/modules/attendance.js`
- `src/js/modules/queries.js`

---

## 🛑 Solución de problemas

### "No puedo acceder a http://localhost:3000"
**Solución**: Usa `http://127.0.0.1:3000` (en Windows, localhost puede no resolver correctamente con Docker)

### "Base de datos no tiene usuarios"
**Solución**: Ejecutar script de seeding:
```bash
docker exec -i itsz_asistencia_db mysql -uroot -proot < database/seed_dev.sql
```

### "Error de conexión a base de datos"
**Solución**: Verificar que los servicios estén corriendo:
```bash
docker compose ps
```

### "Puerto 3000 ya está en uso"
**Solución**: Cambiar puerto en `docker-compose.yml`:
```yaml
ports:
  - "3001:80"  # Cambiar a otro puerto
```

---

## 📚 Referencias

- **Docker Compose**: Ver `docker-compose.yml`
- **Imagen PHP**: Ver `docker/php/Dockerfile`
- **Database**: Ver `database/schema_v3.sql`
- **Seeder**: Ver `database/seed_dev.sql`

---

## ✅ Validación end-to-end

1. Acceder a `http://127.0.0.1:3000`
2. Loguear con `alumno@zongolica.tecnm.mx` / `itsz12345`
3. Verificar acceso al módulo de asistencia
4. Registrar asistencia (estado: presente/retardo/falta)
5. Verificar que la asistencia aparece en la tabla
6. Acceder a módulo de consultas y verificar gráficas

---

_Última actualización: Marzo 2026_
