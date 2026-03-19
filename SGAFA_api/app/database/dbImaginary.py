from typing import List


buzon_db: List[dict] = [
    {
        "id": 1,
        "codigo": "COD-001",
        "activo": "Laptop Lenovo",
        "tipo": "Activo Faltante",
        "reportado_por": "Kevin Aguilar",
        "area": "Laboratorio 3",
        "estado": "Pendiente",
        "descripcion": "Laptop no encontrada en inventario",
        "foto": None,
        "created_at": "2026-02-11T10:00:00",
    },
    {
        "id": 2,
        "codigo": "COD-002",
        "activo": "Proyector Epson",
        "tipo": "Reporte de Daño",
        "reportado_por": "Ana Ruiz",
        "area": "Aula 5",
        "estado": "En revisión",
        "descripcion": "Proyector con pantalla dañada",
        "foto": None,
        "created_at": "2026-02-10T09:30:00",
    },
    {
        "id": 3,
        "codigo": "COD-003",
        "activo": "Monitor Dell 27\"",
        "tipo": "Activo Fantasma",
        "reportado_por": "Carlos Gómez",
        "area": "Oficina Administrativa",
        "estado": "Resuelto",
        "descripcion": "Monitor sin etiqueta QR",
        "foto": None,
        "created_at": "2026-02-09T08:00:00",
    },
]