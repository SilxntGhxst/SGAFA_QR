from fastapi import status, HTTPException, Depends, APIRouter
from app.models.BuzonModel import BuzonCreate, BuzonUpdate
from app.database.dbImaginary import buzon_db
from datetime import datetime

router = APIRouter(
    prefix="/api/buzon",
    tags=["Buzón"]
)


contador_id = 4  

# GET todos
@router.get("/", tags=["Buzón"])
async def listar_buzon():
    return {
        "success": True,
        "total": len(buzon_db),
        "data": buzon_db
    }


# GET por ID
@router.get("/{id}", tags=["Buzón"])
async def obtener_buzon(id: int):
    for registro in buzon_db:
        if registro["id"] == id:
            return {"success": True, "data": registro}
    raise HTTPException(status_code=404, detail="Reporte no encontrado.")


# POST - Insertar nuevo reporte (usado desde la app móvil)
@router.post("/", tags=["Buzón"], status_code=201)
async def crear_buzon(payload: BuzonCreate):
    global contador_id

    nuevo = {
        "id":           contador_id,
        "codigo":       f"COD-{str(contador_id).zfill(3)}",
        "activo":       payload.activo,
        "tipo":         payload.tipo,
        "reportado_por": payload.reportado_por,
        "area":         payload.area,
        "estado":       "Pendiente",
        "descripcion":  payload.descripcion,
        "foto":         payload.foto,
        "created_at":   datetime.now().isoformat(),
    }

    buzon_db.append(nuevo)
    contador_id += 1

    return {
        "success": True,
        "message": "Reporte creado exitosamente.",
        "data": nuevo
    }


# PUT - Editar reporte
@router.put("/{id}", tags=["Buzón"])
async def actualizar_buzon(id: int, payload: BuzonUpdate):
    for registro in buzon_db:
        if registro["id"] == id:
            campos = payload.dict(exclude_unset=True)
            registro.update(campos)
            return {
                "success": True,
                "message": "Reporte actualizado exitosamente.",
                "data": registro
            }
    raise HTTPException(status_code=404, detail="Reporte no encontrado.")


# DELETE - Eliminar reporte
@router.delete("/{id}", tags=["Buzón"])
async def eliminar_buzon(id: int):
    for i, registro in enumerate(buzon_db):
        if registro["id"] == id:
            eliminado = buzon_db.pop(i)
            return {
                "success": True,
                "message": "Reporte eliminado exitosamente.",
                "data": eliminado
            }
    raise HTTPException(status_code=404, detail="Reporte no encontrado.")
