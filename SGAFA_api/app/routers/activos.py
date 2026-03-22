from fastapi import APIRouter, Query, HTTPException, status
from typing import Optional, List
from app.models.ActivoModel import ActivoCreate, ActivoUpdate, ActivoResponse
from app.database.dbImaginary import (
    bienes_muebles_db, categorias_db, ubicaciones_db, usuarios_db,
    solicitudes_cambio_db, detalle_auditoria_db # <--- Agregamos estas dos para las stats
)

router = APIRouter(prefix="/api/activos", tags=["Gestión de Activos"])

contador_activo_id = 4 # Empezamos simulando ID's incrementales (uuid-bien-4)
@router.get("/stats")
def get_stats(db = None): # TODO: Reemplazar db=None por `db: Session = Depends(get_db)` para PostgreSQL
    # TODO: Cuando haya BD, ejecutar `db.query(BienMueble).count()` etc.
    
    total = len(bienes_muebles_db)
    
    estado_counts = {
        "Funcional": len([b for b in bienes_muebles_db if b["estado"].lower() == "funcional"]),
        "En mantenimiento": len([b for b in bienes_muebles_db if b["estado"].lower() == "en mantenimiento"]),
        "Baja": len([b for b in bienes_muebles_db if b["estado"].lower() == "baja"]),
        "Faltante": len([b for b in bienes_muebles_db if b["estado"].lower() == "faltante"]),
    }

    return {
        "total_activos": total,
        "activos_faltantes": estado_counts["Faltante"],
        "solicitudes_pendientes": len([s for s in solicitudes_cambio_db if s["estatus"] == "Pendiente"]),
        "estado_counts": estado_counts
    }

# 2. ENDPOINT DE CATÁLOGOS (Para llenar los Selects en el Frontend)
@router.get("/catalogos")
def get_catalogos(db = None): # TODO: Inyectar DB (PostgreSQL)

    return {
        "categorias": categorias_db,
        "ubicaciones": ubicaciones_db,
        "usuarios": usuarios_db
    }

# 3. ENDPOINT UNIVERSAL DE INVENTARIO (Para Dashboard, Web y Móvil)
@router.get("/")
def get_activos(
    search: Optional[str] = Query(None, description="Búsqueda general"),
    categoria_id: Optional[int] = Query(None, description="Filtro por ID de categoría"),
    estado: Optional[str] = Query(None, description="Filtro por estado"),
    limit: Optional[int] = Query(None, description="Límite de resultados"),
    offset: Optional[int] = Query(0, description="Para paginación"),
    db = None # TODO: Inyectar DB `db: Session = Depends(get_db)` para consulta a PostgreSQL
):
    resultados = bienes_muebles_db

    if search:
        s = search.lower()
        resultados = [b for b in resultados if s in b["nombre"].lower() or s in b["codigo_inventario"].lower()]
    if categoria_id:
        resultados = [b for b in resultados if b["categoria_id"] == categoria_id]
    if estado:
        resultados = [b for b in resultados if b["estado"].lower() == estado.lower()]

    total_count = len(resultados)

    if limit:
        resultados = resultados[offset : offset + limit]
    else:
        resultados = resultados[offset:]

    inventario_enriquecido = []
    
    # Asignación de colores para el dashboard/badges
    color_map = {
        'funcional': 'green',
        'en mantenimiento': 'yellow',
        'baja': 'gray',
        'faltante': 'red'
    }

    for bien in resultados:
        cat = next((c["categoria"] for c in categorias_db if c["id"] == bien["categoria_id"]), "Sin categoría")
        ubi = next((u["nombre"] for u in ubicaciones_db if u["id"] == bien["ubicacion_id"]), "Sin ubicación")
        user_obj = next((u for u in usuarios_db if u["id"] == bien.get("usuario_responsable_id")), None)
        user_name = f"{user_obj['nombre']} {user_obj['apellidos']}" if user_obj else "Sin asignar"
        
        c_badge = color_map.get(bien["estado"].lower(), "blue")

        inventario_enriquecido.append(
            ActivoResponse(
                id=bien["id"],
                codigo=bien["codigo_inventario"],
                nombre=bien["nombre"],
                categoria=cat,
                ubicacion=ubi,
                usuario=user_name,
                estado=bien["estado"].capitalize(),
                fecha=bien["creado_en"],
                color_badge=c_badge
            ).model_dump()
        )

    return {
        "data": inventario_enriquecido,
        "total": total_count
    }

def procesar_nuevos_catalogos(payload):
    if isinstance(payload.categoria_id, str):
        if payload.categoria_id.isdigit():
            payload.categoria_id = int(payload.categoria_id)
        else:
            nuevo_id = max([c["id"] for c in categorias_db]) + 1 if categorias_db else 1
            categorias_db.append({"id": nuevo_id, "categoria": payload.categoria_id})
            payload.categoria_id = nuevo_id

    if isinstance(payload.ubicacion_id, str):
        if payload.ubicacion_id.isdigit():
            payload.ubicacion_id = int(payload.ubicacion_id)
        else:
            nuevo_id = max([u["id"] for u in ubicaciones_db]) + 1 if ubicaciones_db else 1
            ubicaciones_db.append({"id": nuevo_id, "nombre": payload.ubicacion_id, "edificio": "Otro"})
            payload.ubicacion_id = nuevo_id

    if payload.usuario_responsable_id and not payload.usuario_responsable_id.startswith("uuid-"):
        nuevo_id = f"uuid-user-{len(usuarios_db) + 1}"
        parts = payload.usuario_responsable_id.split(" ", 1)
        nombre = parts[0]
        apellidos = parts[1] if len(parts) > 1 else ""
        usuarios_db.append({"id": nuevo_id, "nombre": nombre, "apellidos": apellidos})
        payload.usuario_responsable_id = nuevo_id

    return payload

# 4. ENDPOINT POST - CREAR ACTIVO
@router.post("/", status_code=status.HTTP_201_CREATED)
def crear_activo(payload: ActivoCreate, db = None):
    global contador_activo_id
    from datetime import datetime
    
    nuevo_id = f"uuid-bien-{contador_activo_id}"
    codigo_gen = f"ACT-{str(contador_activo_id).zfill(3)}"
    
    payload = procesar_nuevos_catalogos(payload)

    nuevo = {
        "id": nuevo_id,
        "codigo_inventario": codigo_gen,
        "nombre": payload.nombre,
        "descripcion": payload.descripcion,
        "categoria_id": payload.categoria_id,
        "ubicacion_id": payload.ubicacion_id,
        "usuario_responsable_id": payload.usuario_responsable_id,
        "estado": payload.estado.lower(),
        "creado_en": datetime.now().strftime("%Y-%m-%d")
    }
    
    bienes_muebles_db.append(nuevo)
    contador_activo_id += 1
    
    return {
        "success": True,
        "message": "Activo creado exitosamente.",
        "data": nuevo
    }

# 5. ENDPOINT PUT - EDITAR ACTIVO
@router.put("/{id}")
def actualizar_activo(id: str, payload: ActivoUpdate, db = None):
    payload = procesar_nuevos_catalogos(payload)

    for registro in bienes_muebles_db:
        if registro["id"] == id:
            campos = payload.dict(exclude_unset=True)
            if "estado" in campos and campos["estado"]:
                campos["estado"] = campos["estado"].lower()
            registro.update(campos)
            return {
                "success": True,
                "message": "Activo actualizado exitosamente.",
                "data": registro
            }
    raise HTTPException(status_code=404, detail="Activo no encontrado.")

# 6. ENDPOINT DELETE - ELIMINAR ACTIVO
@router.delete("/{id}")
def eliminar_activo(id: str, db = None):
    for i, registro in enumerate(bienes_muebles_db):
        if registro["id"] == id:
            eliminado = bienes_muebles_db.pop(i)
            return {
                "success": True,
                "message": "Activo eliminado exitosamente.",
                "data": eliminado
            }
    raise HTTPException(status_code=404, detail="Activo no encontrado.")