INSERT INTO bienes_muebles (id, codigo_inventario, nombre, descripcion, marca, modelo, "QR", categoria_id, ubicacion_id, usuario_responsable_id, estado) 
VALUES 
(gen_random_uuid(), 'ACT-001', 'Monitor Dell 24', 'Monitor LED 24 pulgadas', 'Dell', 'P2419H', 12345, 1, 1, 'b5716d2a-b201-4eb1-bdf8-5d937b8181c5', 'funcional'), 
(gen_random_uuid(), 'ACT-002', 'Laptop HP ProBook', 'HP ProBook 450 G8', 'HP', '450 G8', 67890, 1, 1, 'b5716d2a-b201-4eb1-bdf8-5d937b8181c5', 'funcional') 
ON CONFLICT (codigo_inventario) DO NOTHING;

INSERT INTO auditorias (folio, ubicacion_id, usuario_id, fecha_inicio, fecha_fin, estado, escaneados, total_esperados, resumen_final)
VALUES
('AUD-2026-001', 1, 'b5716d2a-b201-4eb1-bdf8-5d937b8181c5', '2026-03-20', '2026-04-20', 'Pendiente', 0, 2, '');
