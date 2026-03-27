import hashlib
from datetime import datetime, timedelta

from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session

from app.database.db import get_db
from app.database.models import Usuario, Rol, Sesion
from app.models.UserModel import UserCreate, UserLogin, UserResponse, Token
from app.security.auth import (
    hash_password, verify_password,
    create_access_token, get_current_user,
    ACCESS_TOKEN_EXPIRE_MINUTES,
)

router = APIRouter(prefix="/api/auth", tags=["Autenticación"])


def _sha256(token: str) -> str:
    return hashlib.sha256(token.encode()).hexdigest()


def _to_response(usuario: Usuario, db: Session) -> UserResponse:
    rol = db.query(Rol).filter(Rol.id == usuario.rol_id).first()
    return UserResponse(
        id=str(usuario.id),
        nombre=usuario.nombre,
        apellidos=usuario.apellidos,
        email=usuario.email,
        rol_id=usuario.rol_id,
        rol=rol.rol if rol else None,
        creado_en=str(usuario.creado_en)[:19] if usuario.creado_en else None,
    )


# ─── POST /api/auth/register ──────────────────────────────────────────────────
@router.post("/register", status_code=status.HTTP_201_CREATED, response_model=UserResponse)
def registrar_usuario(payload: UserCreate, db: Session = Depends(get_db)):
    existe = db.query(Usuario).filter(Usuario.email == payload.email).first()
    if existe:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Ya existe una cuenta con ese correo electrónico.",
        )

    # Validar que el rol existe
    rol = db.query(Rol).filter(Rol.id == payload.rol_id).first()
    if not rol:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail=f"El rol con id={payload.rol_id} no existe.",
        )

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
    return _to_response(nuevo, db)


# ─── POST /api/auth/login ─────────────────────────────────────────────────────
@router.post("/login", response_model=Token)
def iniciar_sesion(payload: UserLogin, db: Session = Depends(get_db)):
    usuario = db.query(Usuario).filter(Usuario.email == payload.email).first()
    if not usuario or not verify_password(payload.password, usuario.clave_acceso):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Correo o contraseña incorrectos.",
            headers={"WWW-Authenticate": "Bearer"},
        )

    expire_delta = timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    token = create_access_token(data={"sub": usuario.email}, expires_delta=expire_delta)

    # Guardar sesión en BD
    sesion = Sesion(
        usuario_id=usuario.id,
        token_hash=_sha256(token),
        expira_en=datetime.utcnow() + expire_delta,
        activo=True,
    )
    db.add(sesion)
    db.commit()

    return Token(
        access_token=token,
        token_type="bearer",
        user=_to_response(usuario, db),
    )


# ─── GET /api/auth/me ─────────────────────────────────────────────────────────
@router.get("/me", response_model=UserResponse)
def obtener_perfil(
    current_user: Usuario = Depends(get_current_user),
    db: Session = Depends(get_db),
):
    return _to_response(current_user, db)


# ─── POST /api/auth/logout ────────────────────────────────────────────────────
@router.post("/logout")
def cerrar_sesion(
    current_user: Usuario = Depends(get_current_user),
    db: Session = Depends(get_db),
):
    # Desactivar TODAS las sesiones activas del usuario
    db.query(Sesion).filter(
        Sesion.usuario_id == current_user.id,
        Sesion.activo == True,
    ).update({"activo": False})
    db.commit()
    return {"success": True, "message": "Sesión cerrada correctamente."}
