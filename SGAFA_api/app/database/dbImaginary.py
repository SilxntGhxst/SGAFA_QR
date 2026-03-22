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
    {"id": 2, "categoria": "Computación"},
    {"id": 3, "categoria": "Laboratorio"},
    {"id": 4, "categoria": "Audio-Video"},
    {"id": 5, "categoria": "Vehículos"}
]

ubicaciones_db = [
    {"id": 1, "nombre": "Oficina Administrativa", "edificio": "CEDECE"},
    {"id": 2, "nombre": "Almacén General", "edificio": "Móvil 3"},
    {"id": 3, "nombre": "Kiosko IT", "edificio": "LT1"},
    {"id": 4, "nombre": "Laboratorio Redes", "edificio": "LT2"},
    {"id": 5, "nombre": "Aula 34", "edificio": "Edificio A"},
    {"id": 6, "nombre": "Sala de Lectura", "edificio": "Biblioteca"},
    {"id": 7, "nombre": "Aula Móvil 1", "edificio": "Móvil 1"},
    {"id": 8, "nombre": "Aula Móvil 2", "edificio": "Móvil 2"}
]

usuarios_db = [
    {"id": "uuid-user-1", "nombre": "Santiago", "apellidos": "Meneses"},
    {"id": "uuid-user-2", "nombre": "André", "apellidos": "Sierra"},
    {"id": "uuid-user-3", "nombre": "María", "apellidos": "García (Docente)"},
    {"id": "uuid-user-4", "nombre": "Juan", "apellidos": "Pérez (Sistemas)"},
    {"id": "uuid-user-5", "nombre": "Laura", "apellidos": "Gómez (Mantenimiento)"}
]

# Tabla: bienes_muebles
bienes_muebles_db = [
    {"id": "uuid-bien-1", "codigo_inventario": "ACT-001", "nombre": "Escritorio Ejecutivo", "categoria_id": 1, "ubicacion_id": 1, "usuario_responsable_id": "uuid-user-1", "estado": "funcional", "creado_en": "2026-02-16"},
    {"id": "uuid-bien-2", "codigo_inventario": "ACT-002", "nombre": "Ventilador de Torre", "categoria_id": 1, "ubicacion_id": 2, "usuario_responsable_id": "uuid-user-5", "estado": "funcional", "creado_en": "2026-02-18"},
    {"id": "uuid-bien-3", "codigo_inventario": "ACT-003", "nombre": "Impresora HP Laser", "categoria_id": 2, "ubicacion_id": 3, "usuario_responsable_id": "uuid-user-4", "estado": "en mantenimiento", "creado_en": "2026-02-17"},
    {"id": "uuid-bien-4", "codigo_inventario": "ACT-004", "nombre": "Proyector Epson AU-1", "categoria_id": 4, "ubicacion_id": 7, "usuario_responsable_id": "uuid-user-3", "estado": "funcional", "creado_en": "2026-02-19"},
    {"id": "uuid-bien-5", "codigo_inventario": "ACT-005", "nombre": "Silla Universitaria Doble", "categoria_id": 1, "ubicacion_id": 8, "usuario_responsable_id": "", "estado": "funcional", "creado_en": "2026-02-20"},
    {"id": "uuid-bien-6", "codigo_inventario": "ACT-006", "nombre": "Osciloscopio Digital", "categoria_id": 3, "ubicacion_id": 4, "usuario_responsable_id": "uuid-user-3", "estado": "funcional", "creado_en": "2026-02-20"},
    {"id": "uuid-bien-7", "codigo_inventario": "ACT-007", "nombre": "Laptop Dell Latitude", "categoria_id": 2, "ubicacion_id": 3, "usuario_responsable_id": "uuid-user-4", "estado": "en mantenimiento", "creado_en": "2026-02-01"},
    {"id": "uuid-bien-8", "codigo_inventario": "ACT-008", "nombre": "Pizarrón Blanco 2x1", "categoria_id": 1, "ubicacion_id": 5, "usuario_responsable_id": "", "estado": "funcional", "creado_en": "2026-02-21"},
    {"id": "uuid-bien-9", "codigo_inventario": "ACT-009", "nombre": "Switch Cisco 24p", "categoria_id": 2, "ubicacion_id": 4, "usuario_responsable_id": "uuid-user-4", "estado": "funcional", "creado_en": "2026-02-22"},
    {"id": "uuid-bien-10", "codigo_inventario": "ACT-010", "nombre": "Carrito de Servicio", "categoria_id": 5, "ubicacion_id": 2, "usuario_responsable_id": "uuid-user-5", "estado": "funcional", "creado_en": "2026-02-22"},
    {"id": "uuid-bien-11", "codigo_inventario": "ACT-011", "nombre": "Archivero Metálico 4 Gavetas", "categoria_id": 1, "ubicacion_id": 1, "usuario_responsable_id": "uuid-user-1", "estado": "funcional", "creado_en": "2026-02-23"},
    {"id": "uuid-bien-12", "codigo_inventario": "ACT-012", "nombre": "Aire Acondicionado Minisplit", "categoria_id": 1, "ubicacion_id": 6, "usuario_responsable_id": "uuid-user-5", "estado": "baja", "creado_en": "2026-02-23"},
    {"id": "uuid-bien-13", "codigo_inventario": "ACT-013", "nombre": "Mesa de Biblioteca Modular", "categoria_id": 1, "ubicacion_id": 6, "usuario_responsable_id": "", "estado": "funcional", "creado_en": "2026-02-23"},
    {"id": "uuid-bien-14", "codigo_inventario": "ACT-014", "nombre": "PC Lenovo ThinkCentre", "categoria_id": 2, "ubicacion_id": 3, "usuario_responsable_id": "", "estado": "en mantenimiento", "creado_en": "2026-02-24"},
    {"id": "uuid-bien-15", "codigo_inventario": "ACT-015", "nombre": "PC Lenovo ThinkCentre", "categoria_id": 2, "ubicacion_id": 3, "usuario_responsable_id": "", "estado": "funcional", "creado_en": "2026-02-24"},
    {"id": "uuid-bien-16", "codigo_inventario": "ACT-016", "nombre": "PC Lenovo ThinkCentre", "categoria_id": 2, "ubicacion_id": 3, "usuario_responsable_id": "", "estado": "funcional", "creado_en": "2026-02-24"},
    {"id": "uuid-bien-17", "codigo_inventario": "ACT-017", "nombre": "Router Inalámbrico TP-Link", "categoria_id": 2, "ubicacion_id": 7, "usuario_responsable_id": "uuid-user-4", "estado": "en mantenimiento", "creado_en": "2026-02-25"},
    {"id": "uuid-bien-18", "codigo_inventario": "ACT-018", "nombre": "Bocina Activa JBL", "categoria_id": 4, "ubicacion_id": 1, "usuario_responsable_id": "uuid-user-2", "estado": "funcional", "creado_en": "2026-02-25"},
    {"id": "uuid-bien-19", "codigo_inventario": "ACT-019", "nombre": "Taladro Inalámbrico Dewalt", "categoria_id": 1, "ubicacion_id": 2, "usuario_responsable_id": "uuid-user-5", "estado": "funcional", "creado_en": "2026-02-26"},
    {"id": "uuid-bien-20", "codigo_inventario": "ACT-020", "nombre": "Monitor LG 24 Pulgadas", "categoria_id": 2, "ubicacion_id": 4, "usuario_responsable_id": "", "estado": "funcional", "creado_en": "2026-02-26"},
    {"id": "uuid-bien-21", "codigo_inventario": "ACT-021", "nombre": "Microscopio Binocular", "categoria_id": 3, "ubicacion_id": 5, "usuario_responsable_id": "uuid-user-3", "estado": "funcional", "creado_en": "2026-02-27"},
    {"id": "uuid-bien-22", "codigo_inventario": "ACT-022", "nombre": "Impresora 3D Creality", "categoria_id": 3, "ubicacion_id": 4, "usuario_responsable_id": "uuid-user-4", "estado": "en mantenimiento", "creado_en": "2026-02-27"},
    {"id": "uuid-bien-23", "codigo_inventario": "ACT-023", "nombre": "Multímetro Digital Fluke", "categoria_id": 3, "ubicacion_id": 4, "usuario_responsable_id": "uuid-user-3", "estado": "baja", "creado_en": "2026-02-28"},
    {"id": "uuid-bien-24", "codigo_inventario": "ACT-024", "nombre": "Sofá Recepción 3 Plazas", "categoria_id": 1, "ubicacion_id": 1, "usuario_responsable_id": "uuid-user-2", "estado": "funcional", "creado_en": "2026-03-01"},
    {"id": "uuid-bien-25", "codigo_inventario": "ACT-025", "nombre": "Tractor Podadora", "categoria_id": 5, "ubicacion_id": 2, "usuario_responsable_id": "uuid-user-5", "estado": "en mantenimiento", "creado_en": "2026-03-01"},
    {"id": "uuid-bien-26", "codigo_inventario": "ACT-026", "nombre": "Pantalla Interactiva 65\"", "categoria_id": 4, "ubicacion_id": 8, "usuario_responsable_id": "uuid-user-3", "estado": "funcional", "creado_en": "2026-03-02"},
    {"id": "uuid-bien-27", "codigo_inventario": "ACT-027", "nombre": "Servidor Dell PowerEdge", "categoria_id": 2, "ubicacion_id": 4, "usuario_responsable_id": "uuid-user-4", "estado": "funcional", "creado_en": "2026-03-02"},
    {"id": "uuid-bien-28", "codigo_inventario": "ACT-028", "nombre": "Cámara Canon Rebel T7", "categoria_id": 4, "ubicacion_id": 1, "usuario_responsable_id": "uuid-user-2", "estado": "funcional", "creado_en": "2026-03-03"},
    {"id": "uuid-bien-29", "codigo_inventario": "ACT-029", "nombre": "Extintor PQS 4.5kg", "categoria_id": 1, "ubicacion_id": 7, "usuario_responsable_id": "uuid-user-5", "estado": "baja", "creado_en": "2026-03-03"},
    {"id": "uuid-bien-30", "codigo_inventario": "ACT-030", "nombre": "Proyector BenQ Aula 34", "categoria_id": 4, "ubicacion_id": 5, "usuario_responsable_id": "", "estado": "faltante", "creado_en": "2026-03-04"},
    {"id": "uuid-bien-31", "codigo_inventario": "ACT-031", "nombre": "Rack Comunicaciones", "categoria_id": 2, "ubicacion_id": 3, "usuario_responsable_id": "uuid-user-4", "estado": "funcional", "creado_en": "2026-03-05"},
    {"id": "uuid-bien-32", "codigo_inventario": "ACT-032", "nombre": "Butaca Plegable", "categoria_id": 1, "ubicacion_id": 8, "usuario_responsable_id": "", "estado": "funcional", "creado_en": "2026-03-06"}
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
