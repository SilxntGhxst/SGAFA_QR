from fastapi import APIRouter, HTTPException, Query, status
from typing import Optional
from datetime import datetime
from app.models.AuditoriaModel import AuditoriaCreate, AuditoriaEscaneo, AuditoriaFinalizar
from app.database.dbImaginary import auditorias_db, bienes_muebles_db, ubicaciones_db, usuarios_db

router = APIRouter(prefix="/api/auditorias", tags=["Auditorías"])

contador_auditoria = 3

@router.get("/")
def listar_auditorias(
    usuario_id: Optional[str] = Query(None, description="Filtra por ID de resguardante/auditor"),
    estado: Optional[str] = Query(None, description="Filtro de estado"),
    db = None
):
    resultados = auditorias_db

    if usuario_id:
        resultados = [r for r in resultados if r["usuario_id"] == usuario_id]
    if estado:
        resultados = [r for r in resultados if r["estado"].lower() == estado.lower()]

    auditorias_enriquecidas = []
    
    for aud in resultados:
        ubi = next((u["nombre"] for u in ubicaciones_db if u["id"] == aud["ubicacion_id"]), "Sin ubicación")
        user_obj = next((u for u in usuarios_db if u["id"] == aud["usuario_id"]), None)
        user_name = f"{user_obj['nombre']} {user_obj['apellidos']}" if user_obj else "Desconocido"

        # Expandimos los códigos de los activos escaneados para mostrarlos visualmente en Web
        lista_activos = []
        for a_id in aud.get("activos_escaneados", []):
            b_obj = next((b for b in bienes_muebles_db if b["id"] == a_id), None)
            if b_obj:
                lista_activos.append({"id": b_obj["id"], "codigo": b_obj["codigo_inventario"], "nombre": b_obj["nombre"]})

        auditorias_enriquecidas.append({
            "id": aud["id"],
            "folio": aud["folio"],
            "ubicacion_id": aud["ubicacion_id"],
            "ubicacion_nombre": ubi,
            "usuario_id": aud["usuario_id"],
            "usuario_nombre": user_name,
            "fecha": aud.get("fecha", ""),
            "fecha_inicio": aud.get("fecha_inicio", aud.get("fecha", "")), 
            "fecha_fin": aud.get("fecha_fin", aud.get("fecha", "")),
            "estado": aud["estado"],
            "escaneados": aud["escaneados"],
            "total_esperados": aud["total_esperados"],
            "progreso": f'{aud["escaneados"]}/{aud["total_esperados"]}',
            "resumen_final": aud.get("resumen_final", ""),
            "activos_list": lista_activos
        })

    return {
        "success": True,
        "total": len(auditorias_enriquecidas),
        "data": auditorias_enriquecidas
    }

@router.post("/", status_code=status.HTTP_201_CREATED)
def crear_auditoria(payload: AuditoriaCreate, db = None):
    global contador_auditoria

    activos_en_ubicacion = [b for b in bienes_muebles_db if b["ubicacion_id"] == payload.ubicacion_id]
    total_esperados = len(activos_en_ubicacion)

    nuevo_id = contador_auditoria
    folio = f"AUD-{datetime.now().year}-{str(nuevo_id).zfill(3)}"

    nueva_auditoria = {
        "id": nuevo_id,
        "folio": folio,
        "ubicacion_id": payload.ubicacion_id,
        "usuario_id": payload.usuario_id,
        "fecha": datetime.now().strftime("%Y-%m-%d"),
        "fecha_inicio": payload.fecha_inicio,
        "fecha_fin": payload.fecha_fin,
        "estado": "Pendiente",
        "escaneados": 0,
        "total_esperados": total_esperados,
        "resumen_final": "",
        "activos_escaneados": []
    }

    auditorias_db.append(nueva_auditoria)
    contador_auditoria += 1

    return {
        "success": True,
        "message": "Auditoría programada con éxito.",
        "data": nueva_auditoria
    }

@router.put("/{id}/escanear")
def procesar_escaneo_auditoria(id: int, payload: AuditoriaEscaneo, db = None):
    for aud in auditorias_db:
        if aud["id"] == id:
            
            # Verificamos si el activo ya fue escaneado antes en esta misma auditoría para no duplicar
            lista_escaneados = aud.get("activos_escaneados", [])
            if payload.activo_id not in lista_escaneados:
                lista_escaneados.append(payload.activo_id)
                aud["activos_escaneados"] = lista_escaneados
                aud["escaneados"] += 1
            else:
                raise HTTPException(status_code=400, detail="Este activo ya fue contabilizado en esta auditoría.")
            
            if aud["estado"] == "Pendiente":
                aud["estado"] = "En Progreso"

            # Nota: El cambio a Completada ahora será explícito mediante el reporte final, 
            # pero podemos pre-avisar que alcanzó la cuota.
            
            return {
                "success": True,
                "message": "Progreso computado a la auditoría.",
                "data": aud
            }

    raise HTTPException(status_code=404, detail="Auditoría no encontrada.")

@router.put("/{id}/finalizar")
def finalizar_auditoria(id: int, payload: AuditoriaFinalizar, db = None):
    for aud in auditorias_db:
        if aud["id"] == id:
            aud["estado"] = "Completada"
            aud["resumen_final"] = payload.resumen_final
            
            return {
                "success": True,
                "message": "Auditoría completada exitosamente.",
                "data": aud
            }

    raise HTTPException(status_code=404, detail="Auditoría no encontrada.")
