from fastapi import APIRouter, HTTPException, status, Depends
from sqlalchemy.orm import Session
from typing import Optional

from app.database.db import get_db
from app.database.models import Usuario, Rol
from app.models.UserModel import UserCreate, UserResponse
from app.security.auth import hash_password, verify_password, get_current_user, validate_password_strength

router = APIRouter(prefix="/api/usuarios", tags=["Usuarios"])


def _enriquecer_usuario(u: Usuario, db: Session) -> dict:
    rol = db.query(Rol).filter(Rol.id == u.rol_id).first()
    return {
        "id":        str(u.id),
        "nombre":    u.nombre,
        "apellidos": u.apellidos,
        "email":     u.email,
        "rol_id":    u.rol_id,
        "rol":       rol.rol if rol else "Sin rol",
        "creado_en": str(u.creado_en)[:10] if u.creado_en else "",
    }


# GET - Listar todos los usuarios
@router.get("/")
def listar_usuarios(db: Session = Depends(get_db)):
    usuarios = db.query(Usuario).all()
    return {
        "success": True,
        "total":   len(usuarios),
        "data":    [_enriquecer_usuario(u, db) for u in usuarios],
    }


# GET - Obtener usuario por ID
@router.get("/{id}")
def obtener_usuario(id: str, db: Session = Depends(get_db)):
    import uuid as _uuid
    usuario = db.query(Usuario).filter(Usuario.id == _uuid.UUID(id)).first()
    if not usuario:
        raise HTTPException(status_code=404, detail="Usuario no encontrado.")
    return {"success": True, "data": _enriquecer_usuario(usuario, db)}


# POST - Crear usuario (solo admin)
@router.post("/", status_code=status.HTTP_201_CREATED)
def crear_usuario(payload: UserCreate, db: Session = Depends(get_db)):
    if db.query(Usuario).filter(Usuario.email == payload.email).first():
        raise HTTPException(status_code=400, detail="El correo ya está registrado.")

    if payload.password:
        validate_password_strength(payload.password)

    nuevo = Usuario(
        nombre=payload.nombre,
        apellidos=payload.apellidos,
        email=payload.email,
        clave_acceso=hash_password(payload.password),
        rol_id=payload.rol_id,
    )
    db.add(nuevo)
    db.commit()
    db.refresh(nuevo)
    return {"success": True, "message": "Usuario creado exitosamente.", "data": _enriquecer_usuario(nuevo, db)}


# PUT - Actualizar usuario
@router.put("/{id}")
def actualizar_usuario(id: str, payload: dict, db: Session = Depends(get_db)):
    import uuid as _uuid
    usuario = db.query(Usuario).filter(Usuario.id == _uuid.UUID(id)).first()
    if not usuario:
        raise HTTPException(status_code=404, detail="Usuario no encontrado.")

    campos_permitidos = {"nombre", "apellidos", "email", "rol_id"}
    for campo, valor in payload.items():
        if campo in campos_permitidos and valor is not None:
            setattr(usuario, campo, valor)

    if "password" in payload and payload["password"]:
        # Verificar contraseña actual antes de permitir el cambio
        current_password = payload.get("current_password", "")
        if not current_password:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail="Debes proporcionar tu contraseña actual para cambiarla."
            )
        if not verify_password(current_password, usuario.clave_acceso):
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="La contraseña actual no es correcta."
            )
        validate_password_strength(payload["password"])
        usuario.clave_acceso = hash_password(payload["password"])

    db.commit()
    db.refresh(usuario)
    return {"success": True, "message": "Usuario actualizado.", "data": _enriquecer_usuario(usuario, db)}


# DELETE - Eliminar usuario
@router.delete("/{id}")
def eliminar_usuario(id: str, db: Session = Depends(get_db)):
    import uuid as _uuid
    usuario = db.query(Usuario).filter(Usuario.id == _uuid.UUID(id)).first()
    if not usuario:
        raise HTTPException(status_code=404, detail="Usuario no encontrado.")
    db.delete(usuario)
    db.commit()
    return {"success": True, "message": "Usuario eliminado exitosamente."}
