from fastapi import APIRouter, Query, HTTPException, status, Depends
from typing import Optional
from sqlalchemy.orm import Session
import uuid as _uuid
from app.models.ActivoModel import ActivoCreate, ActivoUpdate, ActivoResponse
from app.database.db import get_db
from app.database.models import BienMueble, Categoria, Ubicacion, Usuario, EstadoActivo

router = APIRouter(prefix="/api/activos", tags=["Gestión de Activos"])

color_map = {
    "funcional":     "green",
    "mantenimiento": "yellow",
    "baja":          "gray",
}


def _enriquecer(bien: BienMueble, db: Session) -> dict:
    cat  = db.query(Categoria).filter(Categoria.id == bien.categoria_id).first()
    ubi  = db.query(Ubicacion).filter(Ubicacion.id == bien.ubicacion_id).first()
    user = db.query(Usuario).filter(Usuario.id == bien.usuario_responsable_id).first() if bien.usuario_responsable_id else None

    cat_nombre  = cat.categoria if cat else "Sin categoría"
    ubi_nombre  = ubi.nombre if ubi else "Sin ubicación"
    user_nombre = f"{user.nombre} {user.apellidos}" if user else "Sin asignar"
    if hasattr(bien.estado, "value"):
        estado_str = bien.estado.value
    else:
        estado_str = str(bien.estado) if bien.estado else "funcional"
    
    color = color_map.get(estado_str, "blue")

    return ActivoResponse(
        id=str(bien.id),
        codigo=bien.codigo_inventario,
        nombre=bien.nombre,
        descripcion=bien.descripcion or "",
        foto=bien.foto or None,
        categoria=cat_nombre,
        ubicacion=ubi_nombre,
        usuario=user_nombre,
        estado=estado_str.capitalize(),
        fecha=str(bien.creado_at)[:10] if bien.creado_at else "",
        color_badge=color
    ).model_dump()


# 1. STATS — para el dashboard
@router.get("/stats")
def get_stats(db: Session = Depends(get_db)):
    total         = db.query(BienMueble).count()
    funcional     = db.query(BienMueble).filter(BienMueble.estado == EstadoActivo.funcional).count()
    mantenimiento = db.query(BienMueble).filter(BienMueble.estado == EstadoActivo.mantenimiento).count()
    baja          = db.query(BienMueble).filter(BienMueble.estado == EstadoActivo.baja).count()

    return {
        "total_activos": total,
        "activos_faltantes": 0,
        "solicitudes_pendientes": 0,
        "estado_counts": {
            "Funcional":        funcional,
            "En mantenimiento": mantenimiento,
            "Baja":             baja,
        }
    }


# 2. CATÁLOGOS — para llenar los selects en el frontend
@router.get("/catalogos")
def get_catalogos(db: Session = Depends(get_db)):
    categorias = db.query(Categoria).all()
    ubicaciones = db.query(Ubicacion).all()
    usuarios    = db.query(Usuario).all()
    return {
        "categorias": [{"id": c.id, "categoria": c.categoria} for c in categorias],
        "ubicaciones": [{"id": u.id, "nombre": u.nombre, "edificio": u.edificio} for u in ubicaciones],
        "usuarios":    [{"id": str(u.id), "nombre": u.nombre, "apellidos": u.apellidos} for u in usuarios],
    }


# 2b. CREAR nueva categoría
@router.post("/catalogos/categoria", status_code=status.HTTP_201_CREATED)
def crear_categoria(payload: dict, db: Session = Depends(get_db)):
    nombre = (payload.get("categoria") or "").strip()
    if not nombre:
        raise HTTPException(status_code=400, detail="El nombre de la categoría es obligatorio.")
    existing = db.query(Categoria).filter(Categoria.categoria.ilike(nombre)).first()
    if existing:
        return {"success": True, "id": existing.id, "categoria": existing.categoria, "nuevo": False}
    nueva = Categoria(categoria=nombre)
    db.add(nueva)
    db.commit()
    db.refresh(nueva)
    return {"success": True, "id": nueva.id, "categoria": nueva.categoria, "nuevo": True}


# 2c. CREAR nueva ubicación
@router.post("/catalogos/ubicacion", status_code=status.HTTP_201_CREATED)
def crear_ubicacion(payload: dict, db: Session = Depends(get_db)):
    nombre = (payload.get("nombre") or "").strip()
    if not nombre:
        raise HTTPException(status_code=400, detail="El nombre de la ubicación es obligatorio.")
    existing = db.query(Ubicacion).filter(Ubicacion.nombre.ilike(nombre)).first()
    if existing:
        return {"success": True, "id": existing.id, "nombre": existing.nombre, "nuevo": False}
    nueva = Ubicacion(
        nombre=nombre,
        edificio=payload.get("edificio", nombre),
        area=payload.get("area", "General"),
        oficina=payload.get("oficina", "General"),
    )
    db.add(nueva)
    db.commit()
    db.refresh(nueva)
    return {"success": True, "id": nueva.id, "nombre": nueva.nombre, "nuevo": True}



# 3. LISTAR con filtros y paginación
@router.get("/")
def get_activos(
    search:       Optional[str] = Query(None),
    categoria_id: Optional[int] = Query(None),
    estado:       Optional[str] = Query(None),
    ubicacion_id: Optional[int] = Query(None),
    limit:        Optional[int] = Query(None),
    offset:       Optional[int] = Query(0),
    db: Session = Depends(get_db)
):
    query = db.query(BienMueble)

    if search:
        s = f"%{search.lower()}%"
        query = query.filter(
            BienMueble.nombre.ilike(s) | BienMueble.codigo_inventario.ilike(s)
        )
    if categoria_id:
        query = query.filter(BienMueble.categoria_id == categoria_id)
    if estado:
        query = query.filter(BienMueble.estado == estado.lower())
    if ubicacion_id:
        query = query.filter(BienMueble.ubicacion_id == ubicacion_id)

    total = query.count()
    bienes = query.offset(offset).limit(limit).all() if limit else query.offset(offset).all()

    return {
        "data":  [_enriquecer(b, db) for b in bienes],
        "total": total
    }


# 4. OBTENER por código (usado al escanear QR)
@router.get("/{codigo}")
def get_activo_por_codigo(codigo: str, db: Session = Depends(get_db)):
    bien = db.query(BienMueble).filter(BienMueble.codigo_inventario == codigo).first()
    if not bien:
        raise HTTPException(status_code=404, detail="Activo no encontrado.")
    return {"success": True, "data": _enriquecer(bien, db)}


def _resolver_categoria(valor, db: Session) -> int:
    """Acepta ID numérico o nombre de categoría y devuelve el ID."""
    if isinstance(valor, int):
        return valor
    if str(valor).isdigit():
        return int(valor)
    cat = db.query(Categoria).filter(Categoria.categoria.ilike(str(valor))).first()
    if not cat:
        raise HTTPException(status_code=400, detail=f"Categoría '{valor}' no encontrada. Verifica que exista en la BD.")
    return cat.id

def _resolver_ubicacion(valor, db: Session) -> int:
    """Acepta ID numérico o nombre de ubicación y devuelve el ID."""
    if isinstance(valor, int):
        return valor
    if str(valor).isdigit():
        return int(valor)
    ubi = db.query(Ubicacion).filter(Ubicacion.nombre.ilike(str(valor))).first()
    if not ubi:
        raise HTTPException(status_code=400, detail=f"Ubicación '{valor}' no encontrada. Verifica que exista en la BD.")
    return ubi.id


# 5. CREAR activo
@router.post("/", status_code=status.HTTP_201_CREATED)
def crear_activo(payload: ActivoCreate, db: Session = Depends(get_db)):
    total = db.query(BienMueble).count()
    codigo = f"ACT-{str(total + 1).zfill(3)}"

    cat_id = _resolver_categoria(payload.categoria_id, db)
    ubi_id = _resolver_ubicacion(payload.ubicacion_id, db)

    nuevo = BienMueble(
        codigo_inventario=codigo,
        nombre=payload.nombre,
        descripcion=payload.descripcion,
        foto=payload.foto,
        categoria_id=cat_id,
        ubicacion_id=ubi_id,
        usuario_responsable_id=payload.usuario_responsable_id if payload.usuario_responsable_id else None,
        estado=payload.estado.lower(),
    )
    db.add(nuevo)
    db.commit()
    db.refresh(nuevo)
    return {
        "success": True,
        "message": "Activo creado exitosamente.",
        "data": _enriquecer(nuevo, db)
    }


# 6. EDITAR activo
@router.put("/{id}")
def actualizar_activo(id: str, payload: ActivoUpdate, db: Session = Depends(get_db)):
    try:
        val_uuid = _uuid.UUID(id.strip())
    except ValueError:
        raise HTTPException(status_code=400, detail="Formato de ID inválido.")
    
    bien = db.query(BienMueble).filter(BienMueble.id == val_uuid).first()
    if not bien:
        raise HTTPException(status_code=404, detail="Activo no encontrado.")

    campos = payload.dict(exclude_unset=True)
    if "estado" in campos and campos["estado"]:
        val = campos["estado"].lower().strip()
        # Mapear estados comunes o prevenir errores de Enum
        if "óptimo" in val or "optimo" in val: val = "funcional"
        if "desgaste" in val: val = "mantenimiento"
        if "dañado" in val: val = "baja"
        
        # Validar contra el Enum real
        if val not in ["funcional", "mantenimiento", "baja"]:
            val = "funcional" # Fallback seguro
            
        campos["estado"] = val
        
    if "categoria_id" in campos and campos["categoria_id"]:
        campos["categoria_id"] = _resolver_categoria(campos["categoria_id"], db)
    if "ubicacion_id" in campos and campos["ubicacion_id"]:
        campos["ubicacion_id"] = _resolver_ubicacion(campos["ubicacion_id"], db)

    for campo, valor in campos.items():
        setattr(bien, campo, valor)

    db.commit()
    db.refresh(bien)
    return {
        "success": True,
        "message": "Activo actualizado exitosamente.",
        "data": _enriquecer(bien, db)
    }


# 7. ELIMINAR activo
@router.delete("/{id}")
def eliminar_activo(id: str, db: Session = Depends(get_db)):
    try:
        val_uuid = _uuid.UUID(id.strip())
    except ValueError:
        raise HTTPException(status_code=400, detail="Formato de ID inválido.")
    
    bien = db.query(BienMueble).filter(BienMueble.id == val_uuid).first()
    if not bien:
        raise HTTPException(status_code=404, detail="Activo no encontrado.")
    db.delete(bien)
    db.commit()
    return {"success": True, "message": "Activo eliminado exitosamente."}
