from fastapi import APIRouter, Query
from typing import Optional, List
from app.models.schemas import BienMuebleEnriquecido
from app.database.dbImaginary import (
    bienes_muebles_db, categorias_db, ubicaciones_db, usuarios_db,
    solicitudes_cambio_db, detalle_auditoria_db # <--- Agregamos estas dos para las stats
)

router = APIRouter(prefix="/api/activos", tags=["Gestión de Activos"])

# 1. ENDPOINT DE ESTADÍSTICAS (Para el Dashboard)
@router.get("/stats")
def get_stats(db = None): # TODO: Reemplazar db=None por `db: Session = Depends(get_db)` para PostgreSQL
    # TODO: Cuando haya BD, ejecutar `db.query(BienMueble).count()` etc.
    
    total = len(bienes_muebles_db)
    
    estado_counts = {
        "Asignado": len([b for b in bienes_muebles_db if b["estado"].lower() == "asignado"]),
        "Mantenimiento": len([b for b in bienes_muebles_db if b["estado"].lower() == "mantenimiento"]),
        "Disponible": len([b for b in bienes_muebles_db if b["estado"].lower() == "disponible"]),
        "Prestado": len([b for b in bienes_muebles_db if b["estado"].lower() == "prestado"]),
        "Faltante": len([b for b in bienes_muebles_db if b["estado"].lower() == "faltante"]),
        "Funcional": len([b for b in bienes_muebles_db if b["estado"].lower() == "funcional"]),
    }

    return {
        "total_activos": total,
        "activos_faltantes": len([d for d in detalle_auditoria_db if d["estado_encontrado"] == "Faltante"]),
        "solicitudes_pendientes": len([s for s in solicitudes_cambio_db if s["estatus"] == "Pendiente"]),
        "estado_counts": estado_counts
    }

# 2. ENDPOINT DE CATÁLOGOS (Para llenar los Selects en el Frontend)
@router.get("/catalogos")
def get_catalogos(db = None): # TODO: Inyectar DB (PostgreSQL)

    return {
        "categorias": categorias_db,
        "ubicaciones": ubicaciones_db
    }

# 3. ENDPOINT UNIVERSAL DE INVENTARIO (Para Dashboard, Web y Móvil)
@router.get("/", response_model=List[BienMuebleEnriquecido])
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

    if limit:
        resultados = resultados[offset : offset + limit]
    else:
        resultados = resultados[offset:]

    inventario_enriquecido = []
    for bien in resultados:
        cat = next((c["categoria"] for c in categorias_db if c["id"] == bien["categoria_id"]), "Sin categoría")
        ubi = next((u["nombre"] for u in ubicaciones_db if u["id"] == bien["ubicacion_id"]), "Sin ubicación")
        user_obj = next((u for u in usuarios_db if u["id"] == bien["usuario_responsable_id"]), None)
        user_name = f"{user_obj['nombre']} {user_obj['apellidos']}" if user_obj else "Sin asignar"
        
        inventario_enriquecido.append(
            BienMuebleEnriquecido(
                id=bien["id"],
                codigo=bien["codigo_inventario"],
                nombre=bien["nombre"],
                categoria=cat,
                ubicacion=ubi,
                usuario=user_name,
                estado=bien["estado"],
                fecha=bien["creado_en"]
            )
        )

    return inventario_enriquecido