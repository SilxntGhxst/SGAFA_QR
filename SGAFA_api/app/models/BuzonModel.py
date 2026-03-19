from typing import Optional
from pydantic import BaseModel, Field


class BuzonCreate(BaseModel):
    activo:        str  = Field(..., example="Laptop HP")
    tipo:          str  = Field(..., example="Activo Fantasma")  
    reportado_por: str  = Field(..., example="Santiago Meneses")
    area:          str  = Field(..., example="Laboratorio 3")
    descripcion:   Optional[str] = Field(None, example="Activo sin etiqueta QR")
    foto:          Optional[str] = Field(None, example="base64_o_url_de_foto")


class BuzonUpdate(BaseModel):
    activo:        Optional[str] = None
    tipo:          Optional[str] = None
    reportado_por: Optional[str] = None
    area:          Optional[str] = None
    estado:        Optional[str] = None  
    descripcion:   Optional[str] = None
    foto:          Optional[str] = None