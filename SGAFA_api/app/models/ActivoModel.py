from pydantic import BaseModel
from typing import Optional, Union

class ActivoBase(BaseModel):
    nombre: str
    descripcion: Optional[str] = None
    categoria_id: Union[int, str]
    ubicacion_id: Union[int, str]
    usuario_responsable_id: Optional[str] = None
    estado: str

class ActivoCreate(ActivoBase):
    pass

class ActivoUpdate(BaseModel):
    nombre: Optional[str] = None
    descripcion: Optional[str] = None
    categoria_id: Optional[Union[int, str]] = None
    ubicacion_id: Optional[Union[int, str]] = None
    usuario_responsable_id: Optional[str] = None
    estado: Optional[str] = None

class ActivoResponse(BaseModel):
    id: str
    codigo: str
    nombre: str
    categoria: str
    ubicacion: str
    usuario: str
    estado: str
    fecha: str
    color_badge: Optional[str] = None
