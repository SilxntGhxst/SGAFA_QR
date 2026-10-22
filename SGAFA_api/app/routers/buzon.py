from fastapi import status, HTTPException, Depends, APIRouter
from sqlalchemy.orm import Session
from app.models.BuzonModel import BuzonCreate, BuzonUpdate
from app.database.db import get_db
from app.database.models import Buzon as BuzonDB, Usuario
from app.security.auth import get_current_user

router = APIRouter(
    prefix="/api/buzon",
    tags=["Buzón"],
    dependencies=[Depends(get_current_user)]
)


# GET - Listar todos los reportes
@router.get("/")
async def listar_buzon(
    current_user: Usuario = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    query = db.query(BuzonDB)
    if current_user.rol_id != 1:
        query = query.filter(BuzonDB.usuario_id == current_user.id)
    registros = query.all()
    return {
        "success": True,
        "total": len(registros),
        "data": registros
    }


# GET - Obtener reporte por ID
@router.get("/{id}")
async def obtener_buzon(
    id: int,
    current_user: Usuario = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    query = db.query(BuzonDB).filter(BuzonDB.id == id)
    if current_user.rol_id != 1:
        query = query.filter(BuzonDB.usuario_id == current_user.id)
    registro = query.first()
    if not registro:
        raise HTTPException(status_code=404, detail="Reporte no encontrado.")
    return {"success": True, "data": registro}


# POST - Crear nuevo reporte (desde la app móvil)
@router.post("/", status_code=201)
async def crear_buzon(
    payload: BuzonCreate,
    current_user: Usuario = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    ultimo = db.query(BuzonDB).order_by(BuzonDB.id.desc()).first()
    nuevo_id = (ultimo.id + 1) if ultimo else 1

    nuevo = BuzonDB(
        codigo=f"COD-{str(nuevo_id).zfill(3)}",
        activo=payload.activo,
        tipo=payload.tipo,
        usuario_id=current_user.id,
        reportado_por=f"{current_user.nombre} {current_user.apellidos}",
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
async def actualizar_buzon(
    id: int,
    payload: BuzonUpdate,
    current_user: Usuario = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    query = db.query(BuzonDB).filter(BuzonDB.id == id)
    if current_user.rol_id != 1:
        query = query.filter(BuzonDB.usuario_id == current_user.id)
    registro = query.first()
    if not registro:
        raise HTTPException(status_code=404, detail="Reporte no encontrado.")
    campos = payload.dict(exclude_unset=True)
    if current_user.rol_id != 1:
        campos.pop("estado", None)
        campos.pop("reportado_por", None)
    for campo, valor in campos.items():
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
async def eliminar_buzon(
    id: int,
    current_user: Usuario = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    query = db.query(BuzonDB).filter(BuzonDB.id == id)
    if current_user.rol_id != 1:
        query = query.filter(BuzonDB.usuario_id == current_user.id)
    registro = query.first()
    if not registro:
        raise HTTPException(status_code=404, detail="Reporte no encontrado.")
    db.delete(registro)
    db.commit()
    return {
        "success": True,
        "message": "Reporte eliminado exitosamente."
    }
