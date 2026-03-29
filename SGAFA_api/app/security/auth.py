import os
import hashlib
from datetime import datetime, timedelta
from typing import Optional

from jose import JWTError, jwt
from passlib.context import CryptContext
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
from sqlalchemy.orm import Session

from app.database.db import get_db
from app.database.models import Usuario, Sesion

# ─── CONFIGURACIÓN ────────────────────────────────────────────────────────────

SECRET_KEY = os.getenv("SECRET_KEY", "sgafa-qr-super-secret-key-2026-change-in-production")
ALGORITHM  = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 60 * 8   # 8 horas

pwd_context    = CryptContext(schemes=["bcrypt"], deprecated="auto", bcrypt__rounds=12)
oauth2_scheme  = OAuth2PasswordBearer(tokenUrl="/api/auth/login")


# ─── VALIDACIÓN DE CONTRASEÑA ────────────────────────────────────────────────
def validate_password_strength(password: str):
    """
    Valida que la contraseña tenga:
    - Al menos 8 caracteres.
    - Al menos una letra mayúscula.
    - Al menos un símbolo/carácter especial.
    """
    import re
    if len(password) < 8:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="La contraseña debe tener al menos 8 caracteres."
        )
    if not re.search(r"[A-Z]", password):
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="La contraseña debe incluir al menos una letra mayúscula."
        )
    if not re.search(r"\d", password):
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="La contraseña debe incluir al menos un número."
        )
    return True


# ─── UTILIDADES ───────────────────────────────────────────────────────────────

def hash_password(password: str) -> str:
    return pwd_context.hash(password)


def verify_password(plain: str, hashed: str) -> bool:
    return pwd_context.verify(plain, hashed)


def create_access_token(data: dict, expires_delta: Optional[timedelta] = None) -> str:
    to_encode = data.copy()
    expire    = datetime.utcnow() + (expires_delta or timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES))
    to_encode.update({"exp": expire})
    return jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)


def _sha256(token: str) -> str:
    """Hash del JWT para guardarlo en BD sin exponer el token real."""
    return hashlib.sha256(token.encode()).hexdigest()


# ─── DEPENDENCIA: usuario actual ──────────────────────────────────────────────

credentials_exception = HTTPException(
    status_code=status.HTTP_401_UNAUTHORIZED,
    detail="Credenciales inválidas o sesión expirada.",
    headers={"WWW-Authenticate": "Bearer"},
)


def get_current_user(
    token: str = Depends(oauth2_scheme),
    db: Session = Depends(get_db),
) -> Usuario:
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        email: str = payload.get("sub")
        if email is None:
            raise credentials_exception
    except JWTError:
        raise credentials_exception

    # Verificar que la sesión existe y está activa en BD
    token_hash = _sha256(token)
    sesion = (
        db.query(Sesion)
        .filter(
            Sesion.token_hash == token_hash,
            Sesion.activo == True,
            Sesion.expira_en > datetime.utcnow(),
        )
        .first()
    )
    if not sesion:
        raise credentials_exception

    usuario = db.query(Usuario).filter(Usuario.email == email).first()
    if not usuario:
        raise credentials_exception

    return usuario


def get_current_user_optional(
    token: Optional[str] = Depends(oauth2_scheme),
    db: Session = Depends(get_db),
) -> Optional[Usuario]:
    """Igual que get_current_user pero no lanza error si no hay token."""
    try:
        return get_current_user(token=token, db=db)
    except HTTPException:
        return None


def role_required(roles: list[int]):
    """
    Dependencia para verificar si el usuario tiene uno de los roles permitidos.
    Uso: user: Usuario = Depends(role_required([1, 3]))
    """
    def role_checker(user: Usuario = Depends(get_current_user)):
        if user.rol_id not in roles:
            raise HTTPException(
                status_code=status.HTTP_403_FORBIDDEN,
                detail="No tienes permisos para realizar esta acción."
            )
        return user
    return role_checker
