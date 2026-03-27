from fastapi import APIRouter, HTTPException, Query, status, Depends
from typing import Optional
from datetime import datetime
from sqlalchemy.orm import Session
import uuid as _uuid
from app.models.AuditoriaModel import AuditoriaCreate, AuditoriaEscaneo, AuditoriaFinalizar
from app.database.db import get_db
from app.database.models import Auditoria, AuditoriaActivo, BienMueble, Ubicacion, Usuario
import uuid as _uuid

router = APIRouter(prefix="/api/auditorias", tags=["Auditorías"])


# GET - Listar auditorías con datos enriquecidos
@router.get("/")
def listar_auditorias(
    usuario_id: Optional[str] = Query(None),
    estado:     Optional[str] = Query(None),
    db: Session = Depends(get_db)
):
    query = db.query(Auditoria)
    if usuario_id:
        try:
            val_uuid = _uuid.UUID(usuario_id.strip())
            query = query.filter(Auditoria.usuario_id == val_uuid)
        except ValueError:
            pass # Si no es un UUID válido, simplemente no filtramos por usuario
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
def crear_auditoria(payload: AuditoriaCreate, db: Session = Depends(get_db)):
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
def procesar_escaneo(id: int, payload: AuditoriaEscaneo, db: Session = Depends(get_db)):
    aud = db.query(Auditoria).filter(Auditoria.id == id).first()
    if not aud:
        raise HTTPException(status_code=404, detail="Auditoría no encontrada.")

    try:
        bien_uuid = _uuid.UUID(payload.activo_id.strip())
    except ValueError:
        raise HTTPException(status_code=400, detail="El ID del activo no tiene formato UUID válido.")
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
def finalizar_auditoria(id: int, payload: AuditoriaFinalizar, db: Session = Depends(get_db)):
    aud = db.query(Auditoria).filter(Auditoria.id == id).first()
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
