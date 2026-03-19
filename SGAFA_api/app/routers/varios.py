from fastapi import APIRouter


router = APIRouter(
   tags=["Varios"],
)


@router.get("/")
async def bienvenida():
    return {"mensaje": "SGAFA QR - API Buzón de Discrepancias activa"}