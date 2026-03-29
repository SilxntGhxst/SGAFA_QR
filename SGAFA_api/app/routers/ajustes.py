from fastapi import APIRouter, Depends, HTTPException, Body
from sqlalchemy.orm import Session
from typing import Dict, List
from app.database.db import get_db
from app.database.models import AjusteSistema, Usuario
from pydantic import BaseModel
from app.security.auth import role_required

router = APIRouter(
    prefix="/api/ajustes", 
    tags=["Ajustes"],
    dependencies=[Depends(role_required([1]))]
)

class AjusteSchema(BaseModel):
    clave: str
    valor: str
    grupo: str

@router.get("/")
def obtener_ajustes(db: Session = Depends(get_db)):
    ajustes = db.query(AjusteSistema).all()
    # Agrupar por grupo para facilitar la UI
    resultado = {}
    for a in ajustes:
        if a.grupo not in resultado:
            resultado[a.grupo] = []
        resultado[a.grupo].append({"clave": a.clave, "valor": a.valor})
    return resultado

@router.post("/bulk")
def actualizar_ajustes(datos: Dict[str, str] = Body(...), db: Session = Depends(get_db)):
    """
    Actualiza múltiples ajustes a la vez. El body es un dict {clave: valor}
    """
    for clave, valor in datos.items():
        ajuste = db.query(AjusteSistema).filter(AjusteSistema.clave == clave).first()
        if ajuste:
            ajuste.valor = valor
        else:
            # Si no existe, lo creamos en el grupo 'General' por defecto
            nuevo = AjusteSistema(clave=clave, valor=valor, grupo="General")
            db.add(nuevo)
    
    db.commit()
    return {"message": "Ajustes actualizados correctamente"}

@router.get("/{clave}")
def obtener_ajuste_individual(clave: str, db: Session = Depends(get_db)):
    ajuste = db.query(AjusteSistema).filter(AjusteSistema.clave == clave).first()
    if not ajuste:
        raise HTTPException(status_code=404, detail="Ajuste no encontrado")
    return {"clave": ajuste.clave, "valor": ajuste.valor}
