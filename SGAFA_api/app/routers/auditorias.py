from fastapi import APIRouter, HTTPException, Query, status, Depends
from typing import Optional
from datetime import datetime
from sqlalchemy.orm import Session
import uuid as _uuid
from app.models.AuditoriaModel import AuditoriaCreate, AuditoriaEscaneo, AuditoriaFinalizar
from app.database.db import get_db
from app.database.models import Auditoria, AuditoriaActivo, BienMueble, Ubicacion, Usuario
from app.security.auth import get_current_user, role_required
import uuid as _uuid

router = APIRouter(prefix="/api/auditorias", tags=["Auditorías"], dependencies=[Depends(get_current_user)])


# GET - Listar auditorías con datos enriquecidos
@router.get("/")
def listar_auditorias(
    usuario_id: Optional[str] = Query(None),
    estado:     Optional[str] = Query(None),
    current_user: Usuario = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    query = db.query(Auditoria)
    es_admin = current_user.rol_id == 1
    if usuario_id:
        try:
            val_uuid = _uuid.UUID(usuario_id.strip())
        except ValueError:
            raise HTTPException(status_code=400, detail="Formato de usuario_id inválido.")
        if not es_admin and val_uuid != current_user.id:
            raise HTTPException(status_code=403, detail="No tienes permisos para consultar las auditorías de este usuario.")
        query = query.filter(Auditoria.usuario_id == val_uuid)
    elif not es_admin:
        query = query.filter(Auditoria.usuario_id == current_user.id)
    if estado:
        query = query.filter(Auditoria.estado.ilike(estado))

    auditorias = query.all()
    resultado  = []

    for aud in auditorias:
        ubi       = db.query(Ubicacion).filter(Ubicacion.id == aud.ubicacion_id).first()
        user      = db.query(Usuario).filter(Usuario.id == aud.usuario_id).first()
        escaneados_rows = db.query(AuditoriaActivo).filter(AuditoriaActivo.auditoria_id == aud.id).all()

        lista_activos = []
        for row in escaneados_rows:
            bien = db.query(BienMueble).filter(BienMueble.id == row.bien_id).first()
            if bien:
                lista_activos.append({
                    "id":     str(bien.id),
                    "codigo": bien.codigo_inventario,
                    "nombre": bien.nombre
                })

        resultado.append({
            "id":               aud.id,
            "folio":            aud.folio,
            "ubicacion_id":     aud.ubicacion_id,
            "ubicacion_nombre": ubi.nombre if ubi else "Sin ubicación",
            "usuario_id":       str(aud.usuario_id),
            "usuario_nombre":   f"{user.nombre} {user.apellidos}" if user else "Desconocido",
            "fecha":            str(aud.fecha)[:10] if aud.fecha else "",
            "fecha_inicio":     aud.fecha_inicio or "",
            "fecha_fin":        aud.fecha_fin or "",
            "estado":           aud.estado,
            "escaneados":       aud.escaneados,
            "total_esperados":  aud.total_esperados,
            "progreso":         f"{aud.escaneados}/{aud.total_esperados}",
            "resumen_final":    aud.resumen_final or "",
            "activos_list":     lista_activos,
        })

    return {"success": True, "total": len(resultado), "data": resultado}


# POST - Crear nueva auditoría
@router.post("/", status_code=status.HTTP_201_CREATED)
def crear_auditoria(
    payload: AuditoriaCreate,
    current_user: Usuario = Depends(role_required([1])),
    db: Session = Depends(get_db)
):
    total_esperados = db.query(BienMueble).filter(
        BienMueble.ubicacion_id == payload.ubicacion_id
    ).count()

    ultimo = db.query(Auditoria).order_by(Auditoria.id.desc()).first()
    nuevo_id = (ultimo.id + 1) if ultimo else 1
    folio    = f"AUD-{datetime.now().year}-{str(nuevo_id).zfill(3)}"

    nueva = Auditoria(
        folio=folio,
        ubicacion_id=payload.ubicacion_id,
        usuario_id=_uuid.UUID(payload.usuario_id.strip()) if isinstance(payload.usuario_id, str) and len(payload.usuario_id.strip()) > 10 else None,
        fecha_inicio=payload.fecha_inicio,
        fecha_fin=payload.fecha_fin,
        estado="Pendiente",
        escaneados=0,
        total_esperados=total_esperados,
        resumen_final=""
    )
    db.add(nueva)
    db.commit()
    db.refresh(nueva)
    return {
        "success": True,
        "message": "Auditoría programada con éxito.",
        "data": {"id": nueva.id, "folio": nueva.folio, "estado": nueva.estado}
    }


# PUT - Registrar escaneo de un activo en la auditoría
@router.put("/{id}/escanear")
def procesar_escaneo(
    id: int,
    payload: AuditoriaEscaneo,
    current_user: Usuario = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    query = db.query(Auditoria).filter(Auditoria.id == id)
    if current_user.rol_id != 1:
        query = query.filter(Auditoria.usuario_id == current_user.id)
    aud = query.first()
    if not aud:
        raise HTTPException(status_code=404, detail="Auditoría no encontrada.")

    try:
        bien_uuid = _uuid.UUID(payload.activo_id.strip())
    except ValueError:
        raise HTTPException(status_code=400, detail="El ID del activo no tiene formato UUID válido.")
    bien = db.query(BienMueble).filter(
        BienMueble.id == bien_uuid,
        BienMueble.ubicacion_id == aud.ubicacion_id,
    ).first()
    if not bien:
        raise HTTPException(status_code=404, detail="El activo no pertenece a la ubicación de esta auditoría.")

    ya_escaneado = db.query(AuditoriaActivo).filter(
        AuditoriaActivo.auditoria_id == id,
        AuditoriaActivo.bien_id == bien_uuid
    ).first()

    if ya_escaneado:
        raise HTTPException(status_code=400, detail="Este activo ya fue contabilizado en esta auditoría.")

    db.add(AuditoriaActivo(auditoria_id=id, bien_id=bien_uuid))
    aud.escaneados += 1
    if aud.estado == "Pendiente":
        aud.estado = "En Progreso"

    db.commit()
    db.refresh(aud)
    return {
        "success": True,
        "message": "Progreso computado a la auditoría.",
        "data": {"id": aud.id, "escaneados": aud.escaneados, "estado": aud.estado}
    }


# PUT - Finalizar auditoría
@router.put("/{id}/finalizar")
def finalizar_auditoria(
    id: int,
    payload: AuditoriaFinalizar,
    current_user: Usuario = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    query = db.query(Auditoria).filter(Auditoria.id == id)
    if current_user.rol_id != 1:
        query = query.filter(Auditoria.usuario_id == current_user.id)
    aud = query.first()
    if not aud:
        raise HTTPException(status_code=404, detail="Auditoría no encontrada.")

    aud.estado        = "Completada"
    aud.resumen_final = payload.resumen_final
    db.commit()
    db.refresh(aud)
    return {
        "success": True,
        "message": "Auditoría completada exitosamente.",
        "data": {"id": aud.id, "estado": aud.estado}
    }


# PUT - Actualizar auditoría (genérico)
@router.put("/{id}")
def actualizar_auditoria(
    id: int,
    payload: AuditoriaCreate,
    current_user: Usuario = Depends(role_required([1])),
    db: Session = Depends(get_db)
):
    aud = db.query(Auditoria).filter(Auditoria.id == id).first()
    if not aud:
        raise HTTPException(status_code=404, detail="Auditoría no encontrada.")

    # Recalcular total esperados si la ubicación ha cambiado
    if aud.ubicacion_id != payload.ubicacion_id:
        total_esperados = db.query(BienMueble).filter(
            BienMueble.ubicacion_id == payload.ubicacion_id
        ).count()
        aud.total_esperados = total_esperados
        aud.ubicacion_id    = payload.ubicacion_id

    aud.usuario_id   = _uuid.UUID(payload.usuario_id.strip()) if isinstance(payload.usuario_id, str) and len(payload.usuario_id.strip()) > 10 else None
    aud.fecha_inicio = payload.fecha_inicio
    aud.fecha_fin    = payload.fecha_fin

    db.commit()
    db.refresh(aud)
    return {
        "success": True,
        "message": "Auditoría actualizada con éxito.",
        "data": {"id": aud.id, "folio": aud.folio}
    }


# DELETE - Eliminar auditoría
@router.delete("/{id}")
def eliminar_auditoria(
    id: int,
    current_user: Usuario = Depends(role_required([1])),
    db: Session = Depends(get_db)
):
    aud = db.query(Auditoria).filter(Auditoria.id == id).first()
    if not aud:
        raise HTTPException(status_code=404, detail="Auditoría no encontrada.")
    
    # Eliminar también los registros de activos escaneados (cascada manual si no está en el modelo)
    db.query(AuditoriaActivo).filter(AuditoriaActivo.auditoria_id == id).delete()
    
    db.delete(aud)
    db.commit()
    return {"success": True, "message": "Auditoría eliminada permanentemente."}
