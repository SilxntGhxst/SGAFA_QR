from pydantic import BaseModel
from typing import Optional, List

class AuditoriaCreate(BaseModel):
    ubicacion_id: int
    usuario_id: str
    fecha_inicio: str
    fecha_fin: str

class AuditoriaEscaneo(BaseModel):
    activo_id: str

class AuditoriaFinalizar(BaseModel):
    resumen_final: str

class AuditoriaResponse(BaseModel):
    id: int
    folio: str
    ubicacion_id: int
    ubicacion_nombre: str
    usuario_id: str
    usuario_nombre: str
    fecha: str
    fecha_inicio: str
    fecha_fin: str
    estado: str
    escaneados: int
    total_esperados: int
    progreso: str
    resumen_final: Optional[str]
    activos_escaneados: List[str]
