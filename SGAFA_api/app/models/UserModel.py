from pydantic import BaseModel, EmailStr
from typing import Optional
from datetime import datetime


class UserCreate(BaseModel):
    nombre:    str
    apellidos: str
    email:     EmailStr
    password:  str
    rol_id:    int = 2   # 2 = resguardante por defecto; 1 = admin


class UserLogin(BaseModel):
    email:    EmailStr
    password: str


class UserResponse(BaseModel):
    id:        str
    nombre:    str
    apellidos: str
    email:     str
    rol_id:    int
    rol:       Optional[str] = None
    creado_en: Optional[str] = None

    class Config:
        from_attributes = True


class Token(BaseModel):
    access_token: str
    token_type:   str = "bearer"
    user:         UserResponse


class TokenData(BaseModel):
    email: Optional[str] = None


class PasswordChange(BaseModel):
    password_actual: str
    password_nuevo:  str
