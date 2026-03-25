from fastapi import status, HTTPException, Depends, APIRouter
from sqlalchemy.orm import Session
from app.models.BuzonModel import BuzonCreate, BuzonUpdate
from app.database.db import get_db
from app.database.models import Buzon as BuzonDB

router = APIRouter(
    prefix="/api/buzon",
    tags=["Buzón"]
)


# GET - Listar todos los reportes
@router.get("/")
async def listar_buzon(db: Session = Depends(get_db)):
    registros = db.query(BuzonDB).all()
    return {
        "success": True,
        "total": len(registros),
        "data": registros
    }


# GET - Obtener reporte por ID
@router.get("/{id}")
async def obtener_buzon(id: int, db: Session = Depends(get_db)):
    registro = db.query(BuzonDB).filter(BuzonDB.id == id).first()
    if not registro:
        raise HTTPException(status_code=404, detail="Reporte no encontrado.")
    return {"success": True, "data": registro}


# POST - Crear nuevo reporte (desde la app móvil)
@router.post("/", status_code=201)
async def crear_buzon(payload: BuzonCreate, db: Session = Depends(get_db)):
    ultimo = db.query(BuzonDB).order_by(BuzonDB.id.desc()).first()
    nuevo_id = (ultimo.id + 1) if ultimo else 1

    nuevo = BuzonDB(
        codigo=f"COD-{str(nuevo_id).zfill(3)}",
        activo=payload.activo,
        tipo=payload.tipo,
        reportado_por=payload.reportado_por,
        area=payload.area,
        estado="Pendiente",
        descripcion=payload.descripcion,
        foto=payload.foto,
    )
    db.add(nuevo)
    db.commit()
    db.refresh(nuevo)
    return {
        "success": True,
        "message": "Reporte creado exitosamente.",
        "data": nuevo
    }


# PUT - Actualizar reporte
@router.put("/{id}")
async def actualizar_buzon(id: int, payload: BuzonUpdate, db: Session = Depends(get_db)):
    registro = db.query(BuzonDB).filter(BuzonDB.id == id).first()
    if not registro:
        raise HTTPException(status_code=404, detail="Reporte no encontrado.")
    for campo, valor in payload.dict(exclude_unset=True).items():
        setattr(registro, campo, valor)
    db.commit()
    db.refresh(registro)
    return {
        "success": True,
        "message": "Reporte actualizado exitosamente.",
        "data": registro
    }


# DELETE - Eliminar reporte
@router.delete("/{id}")
async def eliminar_buzon(id: int, db: Session = Depends(get_db)):
    registro = db.query(BuzonDB).filter(BuzonDB.id == id).first()
    if not registro:
        raise HTTPException(status_code=404, detail="Reporte no encontrado.")
    db.delete(registro)
    db.commit()
    return {
        "success": True,
        "message": "Reporte eliminado exitosamente."
    }
