from fastapi import APIRouter, Depends, Query
from sqlalchemy.orm import Session
from typing import Optional
from app.database.db import get_db
from app.database.models import BienMueble, Auditoria, Buzon, Categoria, Ubicacion, Usuario

router = APIRouter(prefix="/api/reportes", tags=["Reportes"])

@router.get("/activos")
def reporte_activos(
    categoria_id: Optional[int] = Query(None),
    ubicacion_id: Optional[int] = Query(None),
    estado:       Optional[str] = Query(None),
    db: Session = Depends(get_db)
):
    query = db.query(BienMueble)
    if categoria_id: query = query.filter(BienMueble.categoria_id == categoria_id)
    if ubicacion_id: query = query.filter(BienMueble.ubicacion_id == ubicacion_id)
    if estado:       query = query.filter(BienMueble.estado == estado.lower())
    
    activos = query.all()
    resultado = []
    for a in activos:
        cat  = db.query(Categoria).filter(Categoria.id == a.categoria_id).first()
        ubi  = db.query(Ubicacion).filter(Ubicacion.id == a.ubicacion_id).first()
        user = db.query(Usuario).filter(Usuario.id == a.usuario_responsable_id).first() if a.usuario_responsable_id else None
        
        resultado.append({
            "codigo":      a.codigo_inventario,
            "nombre":      a.nombre,
            "categoria":   cat.categoria if cat else "N/A",
            "ubicacion":   ubi.nombre if ubi else "N/A",
            "estado":      a.estado.value if hasattr(a.estado, "value") else str(a.estado),
            "responsable": f"{user.nombre} {user.apellidos}" if user else "Sin asignar"
        })
    return {"data": resultado}

@router.get("/auditorias")
def reporte_auditorias(db: Session = Depends(get_db)):
    auditorias = db.query(Auditoria).all()
    resultado = []
    for aud in auditorias:
        ubi  = db.query(Ubicacion).filter(Ubicacion.id == aud.ubicacion_id).first()
        user = db.query(Usuario).filter(Usuario.id == aud.usuario_id).first()
        
        resultado.append({
            "folio":            aud.folio,
            "ubicacion":        ubi.nombre if ubi else "N/A",
            "fecha":            str(aud.fecha)[:10] if aud.fecha else "N/A",
            "progreso":         f"{aud.escaneados}/{aud.total_esperados}",
            "resultado_final":  aud.resumen_final or "En curso",
            "estado":           aud.estado
        })
    return {"data": resultado}

@router.get("/discrepancias")
def reporte_discrepancias(db: Session = Depends(get_db)):
    buzon = db.query(Buzon).all()
    resultado = []
    for b in buzon:
        resultado.append({
            "codigo_activo": b.codigo or "N/A",
            "activo_nombre": b.activo,
            "tipo_dano":     b.tipo,
            "reportado_por": b.reportado_por or "Anónimo",
            "area":          b.area,
            "estado":        b.estado,
            "fecha":         str(b.created_at)[:10] if b.created_at else "N/A"
        })
    return {"data": resultado}
