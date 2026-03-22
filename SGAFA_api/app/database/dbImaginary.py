from typing import List


buzon_db: List[dict] = [
    {
        "id": 1,
        "codigo": "COD-001",
        "activo": "Laptop Lenovo",
        "tipo": "Activo Faltante",
        "reportado_por": "Kevin Aguilar",
        "area": "Laboratorio 3",
        "estado": "Pendiente",
        "descripcion": "Laptop no encontrada en inventario",
        "foto": None,
        "created_at": "2026-02-11T10:00:00",
    },
    {
        "id": 2,
        "codigo": "COD-002",
        "activo": "Proyector Epson",
        "tipo": "Reporte de Daño",
        "reportado_por": "Ana Ruiz",
        "area": "Aula 5",
        "estado": "En revisión",
        "descripcion": "Proyector con pantalla dañada",
        "foto": None,
        "created_at": "2026-02-10T09:30:00",
    },
    {
        "id": 3,
        "codigo": "COD-003",
        "activo": "Monitor Dell 27\"",
        "tipo": "Activo Fantasma",
        "reportado_por": "Carlos Gómez",
        "area": "Oficina Administrativa",
        "estado": "Resuelto",
        "descripcion": "Monitor sin etiqueta QR",
        "foto": None,
        "created_at": "2026-02-09T08:00:00",
    },
]

activos_db = [
    {"id": 1, "codigo": "ACT-001", "nombre": "Escritorio", "usuario": "Santiago Meneses", "ubicacion": "Oficina", "fecha": "2026-02-16", "estado": "Asignado", "categoria": "Mobiliario", "color_badge": "blue"},
    {"id": 2, "codigo": "ACT-002", "nombre": "Ventilador", "usuario": "Carlos Chavez", "ubicacion": "Almacén", "fecha": "2026-02-18", "estado": "Disponible", "categoria": "Electrónica", "color_badge": "green"},
    {"id": 3, "codigo": "ACT-003", "nombre": "Impresora HP Laser", "usuario": "Ruth Piña", "ubicacion": "Sala IT", "fecha": "2026-02-17", "estado": "Mantenimiento", "categoria": "Electrónica", "color_badge": "yellow"},
    {"id": 4, "codigo": "ACT-004", "nombre": "Silla de escritorio", "usuario": "Andre Martinez", "ubicacion": "Oficina", "fecha": "2026-02-19", "estado": "Asignado", "categoria": "Mobiliario", "color_badge": "blue"},
    {"id": 5, "codigo": "ACT-005", "nombre": "Proyector", "usuario": "Valeria Briones", "ubicacion": "Sala Reuniones", "fecha": "2026-02-16", "estado": "Prestado", "categoria": "Electrónica", "color_badge": "purple"},
]

# MOCK DB RELACIONAL BASADA EN EL DICCIONARIO DE DATOS DEL PDF

categorias_db = [
    {"id": 1, "categoria": "Mobiliario"},
    {"id": 2, "categoria": "Electrónica"},
    {"id": 3, "categoria": "Audio-Video"}
]

ubicaciones_db = [
    {"id": 1, "nombre": "Oficina Central", "edificio": "Edificio A"},
    {"id": 2, "nombre": "Almacén General", "edificio": "Edificio B"},
    {"id": 3, "nombre": "Sala IT", "edificio": "Edificio A"}
]

usuarios_db = [
    {"id": "uuid-user-1", "nombre": "Santiago", "apellidos": "Meneses"},
    {"id": "uuid-user-2", "nombre": "André", "apellidos": "Sierra"}
]

# Tabla: bienes_muebles
bienes_muebles_db = [
    {"id": "uuid-bien-1", "codigo_inventario": "ACT-001", "nombre": "Escritorio Ejecutivo", "categoria_id": 1, "ubicacion_id": 1, "usuario_responsable_id": "uuid-user-1", "estado": "funcional", "creado_en": "2026-02-16"},
    {"id": "uuid-bien-2", "codigo_inventario": "ACT-002", "nombre": "Ventilador de Torre", "categoria_id": 2, "ubicacion_id": 2, "usuario_responsable_id": "uuid-user-2", "estado": "funcional", "creado_en": "2026-02-18"},
    {"id": "uuid-bien-3", "codigo_inventario": "ACT-003", "nombre": "Impresora HP Laser", "categoria_id": 2, "ubicacion_id": 3, "usuario_responsable_id": "uuid-user-1", "estado": "mantenimiento", "creado_en": "2026-02-17"}
]

# Tabla: solicitudes_cambio (Para contar pendientes)
solicitudes_cambio_db = [
    {"id": 1, "bien_id": "uuid-bien-2", "estatus": "Pendiente"},
    {"id": 2, "bien_id": "uuid-bien-3", "estatus": "En progreso"}
]

# Tabla: detalle_auditoria (Para contar activos faltantes según el PDF)
detalle_auditoria_db = [
    {"id": 1, "bien_id": "uuid-bien-1", "estado_encontrado": "Faltante"}
]
