from pydantic import BaseModel
from typing import Optional

# Basado en la tabla 'categorias' del PDF
class CategoriaSchema(BaseModel):
    id: int
    categoria: str

# Basado en la tabla 'ubicaciones' del PDF
class UbicacionSchema(BaseModel):
    id: int
    nombre: str
    edificio: str

# Basado en la tabla 'usuarios' del PDF
class UsuarioSchema(BaseModel):
    id: str # UUID
    nombre: str
    apellidos: str

# ... Bienes Muebles han sido movidos a ActivoModel.py